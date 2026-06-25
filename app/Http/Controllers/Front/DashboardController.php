<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ActionPlan;
use App\Models\CoachNote;
use App\Models\DietPlan;
use App\Models\DietCompletion;
use App\Models\Membership;
use App\Models\Notification;
use App\Models\Order;
use App\Models\ProgressLog;
use App\Models\WorkoutCompletion;
use App\Models\WorkoutPlan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard with consolidated fitness and progress metrics.
     */
    public function index(): View
    {
        $userId = auth()->id();
        $user = auth()->user();

        // 1. Core Profile & Subscription Metrics
        $membership = Membership::where('user_id', $userId)
    ->where('status', true)
    ->where('expires_at', '>=', now())
    ->latest()
    ->first();

        $daysRemaining = $membership ? (int) now()->diffInDays($membership->expires_at, false) : null;
        $ordersCount = Order::where('user_id', $userId)->count();

        // 2. Workout Metrics
        $totalWorkouts = WorkoutPlan::count();
        $completedWorkouts = WorkoutCompletion::where('user_id', $userId)->count();
        $workoutCompliance = $totalWorkouts > 0 ? round(($completedWorkouts / $totalWorkouts) * 100) : 0;
        
        $currentWorkout = WorkoutPlan::latest()->first();
        $currentWorkoutCompleted = $currentWorkout 
            ? WorkoutCompletion::where('user_id', $userId)->where('workout_plan_id', $currentWorkout->id)->exists()
            : false;

        // 3. Diet Metrics
        $totalDiets = DietPlan::count();
        $completedDiets = DietCompletion::where('user_id', $userId)->count();
        $dietCompliance = $totalDiets > 0 ? round(($completedDiets / $totalDiets) * 100) : 0;

        $currentDiet = DietPlan::latest()->first();
        $currentDietCompleted = $currentDiet 
            ? DietCompletion::where('user_id', $userId)->where('diet_plan_id', $currentDiet->id)->exists()
            : false;

        // 4. Coaching & Interaction
        $latestCoachNote = CoachNote::where('user_id', $userId)
            ->where('is_visible', true)
            ->latest()
            ->first();

        // 5. Check-in & Progress Metrics

        $checkIns = ProgressLog::where('user_id', $userId)
    ->orderBy('created_at')
    ->get();

        $totalCheckIns = $checkIns->count();
        
        $firstCheckIn = $checkIns->first();
        $latestCheckIn = $checkIns->last();

        $weightChange = null;
        $journeyDays = 0;
        $goalProgress = 0;
        $nextCheckInDate = null;
        $daysUntilCheckIn = null;

        if ($firstCheckIn && $latestCheckIn) {
            $weightChange = $firstCheckIn->weight - $latestCheckIn->weight;
            $journeyDays = max(1, $firstCheckIn->created_at->diffInDays($latestCheckIn->created_at));
            
            $nextCheckInDate = $latestCheckIn->created_at->copy()->addDays(7);
            $daysUntilCheckIn = max(0, (int) ceil(now()->diffInDays($nextCheckInDate, false)));
        }

        // Calculate Goal Progress based on User Settings
        if ($user && $user->weight && $latestCheckIn && $latestCheckIn->weight) {
            $goal = strtolower($user->goal ?? '');
            
            if ($goal === 'weight loss' && $firstCheckIn) {

    $lostWeight =
        $firstCheckIn->weight -
        $latestCheckIn->weight;
$gainedWeight =
    $latestCheckIn->weight -
    $firstCheckIn->weight;

    $goalProgress = min(
        100,
        max(
            0,
            round(($lostWeight / 10) * 100)
        )
    );
}
        }

        // 6. Badges & Milestones Achievement System
        $badges = $this->calculateBadges($completedWorkouts, $completedDiets, $totalCheckIns, $weightChange);

        // 7. Chart Data Compilation
        $weightLabels = $checkIns->values()->map(fn($item, $index) => 'Week ' . ($index + 1))->toArray();
        $weightData = $checkIns->pluck('weight')->map(fn($weight) => (float) $weight)->toArray();

        // 8. Action Plans & System Notifications
        $unreadNotifications = Notification::where('user_id', $userId)->where('is_read', false)->count();

        $actionPlansStats = ActionPlan::where('user_id', $userId)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN is_completed = 1 THEN 1 ELSE 0 END) as completed
            ')
            ->first();

        $pendingActionPlans = ActionPlan::where('user_id', $userId)
            ->where('is_completed', false)
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard', [
            'membership'             => $membership,
            'orders'                 => $ordersCount,
            'daysRemaining'          => $daysRemaining,
            'completedWorkouts'      => $completedWorkouts,
            'totalWorkouts'          => $totalWorkouts,
            'workoutCompliance'      => $workoutCompliance,
            'currentWorkout'         => $currentWorkout,
            'currentWorkoutCompleted' => $currentWorkoutCompleted,
            'completedDiets'         => $completedDiets,
            'totalDiets'             => $totalDiets,
            'dietCompliance'         => $dietCompliance,
            'currentDiet'            => $currentDiet,
            'currentDietCompleted'   => $currentDietCompleted,
            'latestCoachNote'        => $latestCoachNote,
            'firstCheckIn'           => $firstCheckIn,
            'latestCheckIn'          => $latestCheckIn,
            'weightChange'           => $weightChange,
            'journeyDays'            => $journeyDays,
            'goalProgress'           => $goalProgress,
            'totalCheckIns'          => $totalCheckIns,
            'checkInStreak'          => $totalCheckIns, // Kept to match your logic fallback
            'nextCheckInDate'        => $nextCheckInDate,
            'daysUntilCheckIn'       => $daysUntilCheckIn,
            'weightLabels'           => $weightLabels,
            'weightData'             => $weightData,
            'badges'                 => $badges,
            'pendingActionPlans'     => $pendingActionPlans,
            'unreadNotifications'    => $unreadNotifications,
            'completedActionPlans'   => $actionPlansStats->completed ?? 0,
            'totalActionPlans'       => $actionPlansStats->total ?? 0,
        ]);
    }

    /**
     * Determine milestone badges earned by the user.
     */
    private function calculateBadges(int $workouts, int $diets, int $checkIns, ?float $weightChange): array
    {
        $badges = [];

        if ($workouts >= 1) {
            $badges[] = '💪 First Workout Completed';
        }
        if ($diets >= 1) {
            $badges[] = '🥗 First Diet Completed';
        }
        if ($checkIns >= 1) {
            $badges[] = '📈 First Check-In Submitted';
        }
        if ($weightChange !== null && $weightChange >= 5) {
    $badges[] = '🔥 5kg Lost';
}
        if ($checkIns >= 4) {
            $badges[] = '🏆 4 Week Streak';
        }

        return $badges;
    }
}