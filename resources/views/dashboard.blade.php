@extends('layouts.app')

@section('content')
<section class="min-h-screen bg-zinc-50 py-16 text-zinc-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        {{-- ========================================== --}}
        {{-- HEADER SECTION                             --}}
        {{-- ========================================== --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-12">
            <div>
                <p class="text-xs uppercase tracking-widest text-amber-500 font-bold mb-2">
                    Member Dashboard
                </p>
                <h1 class="text-4xl font-black text-zinc-900 tracking-tight mb-2">
                    Welcome back, {{ auth()->user()->name }}
                </h1>
                <p class="text-lg text-zinc-500 max-w-2xl">
                    Manage your fitness journey, membership tier, daily workouts, and custom nutrition parameters.
                </p>
            </div>

            <div class="shrink-0">
                @if($membership)
                    <a href="{{ route('member.my-plan') }}" class="bg-zinc-900 hover:bg-zinc-800 text-white px-6 py-3.5 rounded-xl font-bold tracking-wide text-sm transition duration-200 shadow-md inline-block">
                        My Fitness Plan
                    </a>
                @else
                    <a href="/plans" class="bg-amber-500 hover:bg-amber-600 text-zinc-950 px-6 py-3.5 rounded-xl font-bold tracking-wide text-sm transition duration-200 shadow-md inline-block">
                        Upgrade Membership
                    </a>
                @endif
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- PRIMARY CORE STATS                         --}}
        {{-- ========================================== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            {{-- ACTIVE PLAN CARD --}}
            <div class="bg-white rounded-3xl p-8 border border-zinc-100 shadow-sm flex flex-col justify-between">
                <div>
                    <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-4">
                        Active Membership
                    </p>
                    @if($membership)
                        <h2 class="text-3xl font-black text-zinc-900 tracking-tight">
                            {{ $membership->plan->name }}
                        </h2>
                        <div class="flex items-center gap-1.5 text-emerald-600 font-bold text-sm mt-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Status: Active
                        </div>
                        <div class="mt-4 pt-4 border-t border-zinc-100 space-y-1 text-sm text-zinc-500">
                            <p>Expires: <span class="font-medium text-zinc-700">{{ $membership->expires_at->format('d M Y') }}</span></p>
                            <p class="text-amber-600 font-semibold">{{ $daysRemaining }} Days Remaining</p>
                        </div>
                    @else
                        <h2 class="text-3xl font-black text-zinc-400 tracking-tight">
                            No Active Plan
                        </h2>
                        <p class="text-rose-500 font-bold text-sm mt-2">Inactive Access</p>
                    @endif
                </div>
                <div class="mt-6">
                    <a href="{{ $membership ? route('member.my-plan') : '/plans' }}" class="text-sm font-semibold text-zinc-900 hover:text-amber-500 underline underline-offset-4 transition">
                        {{ $membership ? 'View My Plan' : 'Choose a Plan' }} →
                    </a>
                </div>
            </div>

            {{-- WORKOUT COMPLIANCE --}}
            <div class="bg-white rounded-3xl p-8 border border-zinc-100 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider">
                        Workout Compliance
                    </p>
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <h2 class="text-5xl font-black text-blue-600 tracking-tight mb-2">
                    {{ $workoutCompliance }}%
                </h2>
                <p class="text-sm text-zinc-500 font-medium">
                    {{ $completedWorkouts }} of {{ $totalWorkouts }} workouts log completed
                </p>
            </div>

            {{-- DIET COMPLIANCE --}}
            <div class="bg-white rounded-3xl p-8 border border-zinc-100 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider">
                        Diet Compliance
                    </p>
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>
                </div>
                <h2 class="text-5xl font-black text-emerald-600 tracking-tight mb-2">
                    {{ $dietCompliance }}%
                </h2>
                <p class="text-sm text-zinc-500 font-medium">
                    Nutrition macro-adherence rating
                </p>
            </div>

            {{-- ACHIEVEMENTS SYSTEM --}}
            <div class="bg-white rounded-3xl p-8 border border-zinc-100 shadow-sm flex flex-col justify-between">
                <div>
                    <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-4">
                        Unlocked Trophies
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @forelse($badges as $badge)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                🏆 {{ $badge }}
                            </span>
                        @empty
                            <p class="text-sm text-zinc-400 italic">No achievements unlocked yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        {{-- ========================================== --}}
        {{-- QUICK ACCESS INTERACTION GRID              --}}
        {{-- ========================================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            {{-- CURRENT WORKOUT --}}
            <div class="bg-white rounded-3xl p-6 border border-zinc-100 shadow-sm flex flex-col justify-between">
                <div>
                    <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-3">Current Workout</p>
                    @if($currentWorkout)
                        <h3 class="text-xl font-bold text-zinc-900 tracking-tight mb-2">{{ $currentWorkout->title }}</h3>
                        <span class="inline-block text-xs font-bold px-2.5 py-1 rounded-md {{ $currentWorkoutCompleted ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ $currentWorkoutCompleted ? 'Completed' : 'In Progress' }}
                        </span>
                    @else
                        <h3 class="text-lg font-medium text-zinc-400">No Workout Assigned</h3>
                    @endif
                </div>
                <div class="mt-6 pt-4 border-t border-zinc-50">
                    @if($currentWorkout)
                        <a href="/workout-plans/{{ $currentWorkout->id }}" class="text-sm font-bold text-zinc-900 hover:text-amber-500 transition">Open Schedule →</a>
                    @endif
                </div>
            </div>

            {{-- CURRENT DIET --}}
            <div class="bg-white rounded-3xl p-6 border border-zinc-100 shadow-sm flex flex-col justify-between">
                <div>
                    <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-3">Current Diet Plan</p>
                    @if($currentDiet)
                        <h3 class="text-xl font-bold text-zinc-900 tracking-tight mb-2">{{ $currentDiet->title }}</h3>
                        <span class="inline-block text-xs font-bold px-2.5 py-1 rounded-md {{ $currentDietCompleted ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ $currentDietCompleted ? 'Completed' : 'In Progress' }}
                        </span>
                    @else
                        <h3 class="text-lg font-medium text-zinc-400">No Diet Assigned</h3>
                    @endif
                </div>
                <div class="mt-6 pt-4 border-t border-zinc-50">
                    @if($currentDiet)
                        <a href="/diet-plans/{{ $currentDiet->id }}" class="text-sm font-bold text-zinc-900 hover:text-amber-500 transition">Open Menu →</a>
                    @endif
                </div>
            </div>

            {{-- WORKOUT PLANS ACCESS LINK --}}
            <a href="{{ auth()->user()->hasBasicAccess() ? '/workout-plans' : '/plans' }}" class="group rounded-3xl p-6 shadow-sm flex flex-col justify-between transition duration-200 transform hover:-translate-y-1 {{ auth()->user()->hasBasicAccess() ? 'bg-zinc-900 text-white' : 'bg-zinc-100 text-zinc-700 border border-zinc-200' }}">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-2xl">💪</span>
                        @if(!auth()->user()->hasBasicAccess())
                            <span class="text-xs font-bold tracking-widest uppercase px-2 py-1 bg-zinc-200 text-zinc-800 rounded">Locked</span>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold tracking-tight {{ auth()->user()->hasBasicAccess() ? 'text-white' : 'text-zinc-900' }}">
                        Premium Workouts
                    </h3>
                    <p class="text-xs mt-2 {{ auth()->user()->hasBasicAccess() ? 'text-zinc-400' : 'text-zinc-500' }} leading-relaxed">
                        Access high-tier hyper-optimized system workout models and splits.
                    </p>
                </div>
                <span class="text-sm font-bold mt-6 group-hover:underline underline-offset-4">Explore Routines →</span>
            </a>

            {{-- DIET PLANS ACCESS LINK --}}
            <a href="{{ auth()->user()->hasProAccess() ? '/diet-plans' : '/plans' }}" class="group rounded-3xl p-6 shadow-sm flex flex-col justify-between transition duration-200 transform hover:-translate-y-1 {{ auth()->user()->hasProAccess() ? 'bg-amber-500 text-zinc-950' : 'bg-zinc-100 text-zinc-700 border border-zinc-200' }}">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-2xl">🥗</span>
                        @if(!auth()->user()->hasProAccess())
                            <span class="text-xs font-bold tracking-widest uppercase px-2 py-1 bg-zinc-200 text-zinc-800 rounded">Pro Lock</span>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold tracking-tight text-zinc-950">
                        Premium Diet Plans
                    </h3>
                    <p class="text-xs mt-2 text-zinc-800 leading-relaxed opacity-80">
                        Access target-specific custom nutrition pathways and templates.
                    </p>
                </div>
                <span class="text-sm font-bold mt-6 group-hover:underline underline-offset-4">Explore Nutrition →</span>
            </a>

        </div>

        {{-- ========================================== --}}
        {{-- SECONDARY DETAILED TRACKING SECTIONS      --}}
        {{-- ========================================== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

            {{-- NEXT CHECK-IN --}}
            <div class="bg-white rounded-3xl p-8 border border-zinc-100 shadow-sm flex flex-col justify-between">
                <div>
                    <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-4">Next Coach Check-In</p>
                    @if($latestCheckIn)
                        <div class="space-y-3">
                            <div>
                                <span class="text-[10px] font-bold uppercase text-zinc-400 tracking-wider">Last Sync</span>
                                <p class="font-bold text-zinc-800">{{ $latestCheckIn->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="pt-2 border-t border-zinc-50">
                                <span class="text-[10px] font-bold uppercase text-amber-500 tracking-wider">Deadline Due</span>
                                <p class="font-black text-zinc-900 text-lg">{{ $nextCheckInDate->format('d M Y') }}</p>
                                <p class="text-xs font-semibold text-zinc-500 mt-0.5">({{ $daysUntilCheckIn }} days remaining)</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="mt-8 pt-4 border-t border-zinc-100">
                    
                </div>
            </div>

            {{-- ACTION PLAN --}}
            <div class="bg-white rounded-3xl p-8 border border-zinc-100 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Weekly Objectives</p>
                        <span class="text-xl">📋</span>
                    </div>
                    <h2 class="text-5xl font-black text-zinc-900 tracking-tight mt-2">
                        {{ $completedActionPlans }}<span class="text-zinc-300 font-light text-3xl">/{{ $totalActionPlans }}</span>
                    </h2>
                    <p class="text-xs font-semibold text-zinc-400 mt-1">Milestones Completed This Week</p>
                </div>
                <div class="mt-8 pt-4 border-t border-zinc-100">
                    <a href="{{ route('member.action-plan') }}" class="inline-flex items-center text-sm font-bold text-zinc-900 hover:text-amber-500 transition">
                        View Action Items →
                    </a>
                </div>
            </div>

            {{-- NOTIFICATIONS --}}
            <div class="bg-white rounded-3xl p-8 border border-zinc-100 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Alert Center</p>
                        @if($unreadNotifications > 0)
                            <span class="text-[11px] font-bold bg-rose-50 text-rose-600 px-2.5 py-1 rounded-full border border-rose-100">
                                {{ $unreadNotifications }} unread
                            </span>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold text-zinc-900 tracking-tight mb-2">🔔 System Notifications</h3>
                    <p class="text-xs text-zinc-500 leading-relaxed">
                        Stay linked dynamically to real-time adjustments from your personal trainers and coaches.
                    </p>
                </div>
                <div class="mt-8 pt-4 border-t border-zinc-100">
                    <a href="{{ route('member.notifications') }}" class="inline-flex items-center text-sm font-bold text-zinc-900 hover:text-amber-500 transition">
                        View Updates Grid →
                    </a>
                </div>
            </div>

        </div>

        {{-- ========================================== --}}
        {{-- UTILITY TOOLS SUB-LINKS GRID               --}}
        {{-- ========================================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- PROGRESS TRACKER LINK --}}
            <a href="{{ route('member.progress') }}" class="group bg-white rounded-2xl p-6 border border-zinc-100 shadow-sm flex items-center gap-4 transition duration-200 transform hover:-translate-y-0.5">
                <span class="text-3xl bg-zinc-50 p-2 rounded-xl">📈</span>
                <div>
                    <h4 class="font-bold text-zinc-900 group-hover:text-amber-500 transition">Progress Tracker</h4>
                    <p class="text-xs text-zinc-400">Log custom dimensions</p>
                </div>
            </a>

            {{-- TRANSFORMATIONS LINK --}}
            <a href="{{ route('member.transformations') }}" class="group bg-white rounded-2xl p-6 border border-zinc-100 shadow-sm flex items-center gap-4 transition duration-200 transform hover:-translate-y-0.5">
                <span class="text-3xl bg-zinc-50 p-2 rounded-xl">🏆</span>
                <div>
                    <h4 class="font-bold text-zinc-900 group-hover:text-amber-500 transition">Photo Log Timeline</h4>
                    <p class="text-xs text-zinc-400">Visual physical timeline</p>
                </div>
            </a>

            {{-- PROGRESS REPORT DOWNLOAD --}}
            <a href="{{ route('member.progress-report') }}" class="group bg-white rounded-2xl p-6 border border-zinc-100 shadow-sm flex items-center gap-4 transition duration-200 transform hover:-translate-y-0.5">
                <span class="text-3xl bg-zinc-50 p-2 rounded-xl">📄</span>
                <div>
                    <h4 class="font-bold text-zinc-900 group-hover:text-amber-500 transition">Download PDF Report</h4>
                    <p class="text-xs text-zinc-400">Export transformation stats</p>
                </div>
            </a>

            {{-- PROFILE MANAGEMENT --}}
            <a href="{{ route('member.profile') }}" class="group bg-white rounded-2xl p-6 border border-zinc-100 shadow-sm flex items-center gap-4 transition duration-200 transform hover:-translate-y-0.5">
                <span class="text-3xl bg-zinc-50 p-2 rounded-xl">👤</span>
                <div>
                    <h4 class="font-bold text-zinc-900 group-hover:text-amber-500 transition">Account Profile</h4>
                    <p class="text-xs text-zinc-400">Manage credentials & keys</p>
                </div>
            </a>
        </div>

        {{-- ========================================== --}}
        {{-- COACH NOTE & TRANSFORMATION LOG INTERACTIVE--}}
        {{-- ========================================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            
            {{-- COACH FEEDBACK OVERVIEW --}}
            <div class="bg-white rounded-3xl p-8 border border-zinc-100 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Latest Coach Note</p>
                        <span class="text-sm text-zinc-400">💬</span>
                    </div>
                    @if($latestCoachNote)
                        <blockquote class="text-zinc-600 italic border-l-2 border-amber-400 pl-4 my-4 text-sm leading-relaxed">
                            "{{ Str::limit($latestCoachNote->note, 140) }}"
                        </blockquote>
                        <span class="text-xs text-zinc-400 block mt-2 font-medium">{{ $latestCoachNote->created_at->format('d M Y') }}</span>
                    @else
                        <p class="text-zinc-400 text-sm italic my-6">Your head coach hasn't logged notes yet this cycle.</p>
                    @endif
                </div>
                <div class="pt-4 border-t border-zinc-100 mt-6">
                    <a href="{{ route('member.coach-notes') }}" class="text-xs font-bold text-zinc-900 hover:text-amber-500 transition uppercase tracking-wider">
                        Read Historical Vault →
                    </a>
                </div>
            </div>

            {{-- TRANSFORMATION SUMMARY DATA BLOCK --}}
            <div class="bg-white rounded-3xl p-8 border border-zinc-100 shadow-sm lg:col-span-2 flex flex-col justify-between">
                <div>
                    <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-6">Transformation Metrics Summary</p>
                    @if($firstCheckIn && $latestCheckIn)
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 border-b border-zinc-100 pb-6">
                            <div>
                                <p class="text-[10px] uppercase font-bold text-zinc-400 tracking-wider mb-1">Starting Baseline</p>
                                <h3 class="text-2xl font-black text-zinc-900">{{ $firstCheckIn->weight }} kg</h3>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-zinc-400 tracking-wider mb-1">Current Metric</p>
                                <h3 class="text-2xl font-black text-emerald-600">{{ $latestCheckIn->weight }} kg</h3>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-zinc-400 tracking-wider mb-1">Net Differential</p>
                                @if($weightChange > 0)
                                    <h3 class="text-2xl font-black text-emerald-600">{{ $weightChange }} kg Delta Lost</h3>
                                @elseif($weightChange < 0)
                                    <h3 class="text-2xl font-black text-rose-500">{{ abs($weightChange) }} kg Delta Gained</h3>
                                @else
                                    <h3 class="text-2xl font-black text-zinc-400">Equilibrium</h3>
                                @endif
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 pt-4 text-xs font-medium text-zinc-500">
                            <p>Active Program Run: <span class="text-zinc-800 font-bold">{{ $journeyDays }} Days</span></p>
                            <p>Data Verification Check-Ins: <span class="text-zinc-800 font-bold">{{ $totalCheckIns }} Logged</span></p>
                        </div>
                    @else
                        <p class="text-zinc-400 text-sm italic my-6">Submit initial check-in forms to formulate progress graphs.</p>
                    @endif
                </div>
                <div class="mt-6 flex justify-end">
                    <a href="{{ route('member.progress-report') }}" class="px-5 py-2.5 bg-zinc-950 hover:bg-zinc-800 text-white font-bold rounded-xl transition text-xs shadow-sm">
                        Download Comprehensive Metrics Report
                    </a>
                </div>
            </div>

        </div>

        {{-- ========================================== --}}
        {{-- VISUAL CHART RENDERING GRAPH               --}}
        {{-- ========================================== --}}
        <div class="bg-white rounded-3xl p-8 border border-zinc-100 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Weight Progress Metrics (Chronological Timeline)</p>
                <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
            </div>
            <div class="h-[320px]">
                <canvas id="weightChart"></canvas>
            </div>
        </div>

    </div>
</section>

{{-- Goal Progress --}}

<div class="bg-white rounded-3xl p-8 shadow-xl">

    <div class="flex justify-between items-center mb-6">

        <h3 class="text-xl font-bold text-zinc-900">

            🎯 Goal Progress

        </h3>

        <span class="font-bold text-yellow-500">

            {{ $goalProgress }}%

        </span>

    </div>

    <div class="w-full bg-zinc-200 rounded-full h-4 mb-4">

        <div
            class="bg-yellow-500 h-4 rounded-full"
            style="width: {{ $goalProgress }}%">
        </div>

    </div>

    <p class="text-sm text-zinc-500">

        Goal:
        {{ auth()->user()->goal ?? 'Not Set' }}

    </p>

</div>

{{-- ========================================== --}}
{{-- VISUAL HIDDEN DOM METADATA PRE-CACHE BLOCK --}}
{{-- ========================================== --}}
<div class="hidden" aria-hidden="true">
    <span data-meta="orders">{{ $orders }}</span>
    <span data-meta="completed-workouts">{{ $completedWorkouts }}</span>
    <span data-meta="completed-diets">{{ $completedDiets }}</span>
    <span data-meta="streak">{{ $checkInStreak }}</span>
</div>

{{-- SCRIPTS INJECTION --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartCanvas = document.getElementById('weightChart');
        if (chartCanvas) {
            new Chart(chartCanvas, {
                type: 'line',
                data: {
                    labels: @json($weightLabels),
                    datasets: [{
                        label: 'Weight Data Matrix (kg)',
                        data: @json($weightData),
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.05)',
                        borderWidth: 3,
                        pointBackgroundColor: '#f59e0b',
                        pointHoverRadius: 6,
                        tension: 0.35,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { 
                            beginAtZero: false,
                            grid: { color: '#f4f4f5' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection