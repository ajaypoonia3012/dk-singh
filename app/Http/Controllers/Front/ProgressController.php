<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProgressLog;

class ProgressController extends Controller
{
    public function index()
{
    $logs = ProgressLog::where(
        'user_id',
        auth()->id()
    )
    ->latest()
    ->get();


$chartLogs = $logs->reverse()->values();

$weightLabels = $chartLogs
    ->pluck('created_at')
    ->map(fn ($date) => $date->format('d M'))
    ->toArray();

$weightData = $chartLogs
    ->pluck('weight')
    ->toArray();

    $latest = $logs->first();

$oldest = $logs->last();

$startingBMI = $oldest?->bmi;

$currentBMI = $latest?->bmi;

$bmiChange = null;

if ($startingBMI && $currentBMI) {

    $bmiChange = round(
        $startingBMI - $currentBMI,
        1
    );
}

$startingBodyFat = $oldest?->body_fat;

$currentBodyFat = $latest?->body_fat;

$bodyFatLost = null;

if ($startingBodyFat && $currentBodyFat) {

    $bodyFatLost = round(
        $startingBodyFat - $currentBodyFat,
        1
    );
}

    $startingWeight = $logs->last()?->weight;

    $currentWeight = $latest?->weight;

    $weightLost = null;

    if ($startingWeight && $currentWeight) {

    $weightLost = $startingWeight - $currentWeight;
}

$alreadySubmittedToday = ProgressLog::where(
    'user_id',
    auth()->id()
)
->whereDate('created_at', today())
->exists();

    return view(
        'member.progress.index',
        compact(
    'logs',
    'latest',

    'startingWeight',
    'currentWeight',
    'weightLost',

    'startingBMI',
    'currentBMI',
    'bmiChange',
'weightLabels',
'weightData',
    'startingBodyFat',
    'currentBodyFat',
    'bodyFatLost',
'alreadySubmittedToday'
)
    );
}

    public function create()
{
    return view(
        'member.progress.create'
    );
}

    public function store(Request $request)
{
$alreadySubmittedToday = ProgressLog::where(
    'user_id',
    auth()->id()
)
->whereDate('created_at', today())
->exists();

if ($alreadySubmittedToday) {

    return redirect()
        ->route('member.progress')
        ->with(
            'error',
            'You have already submitted a check-in today.'
        );
}
$user = auth()->user();
$frontPhoto = null;
$sidePhoto = null;
$backPhoto = null;
$bmi = null;

if ($user->height && $request->weight) {

    $heightInMeters = $user->height / 100;

    $bmi = round(
        $request->weight /
        ($heightInMeters * $heightInMeters),
        1
    );
}
if ($request->hasFile('front_photo')) {

    $frontPhoto = $request
        ->file('front_photo')
        ->store('progress', 'public');
}

if ($request->hasFile('side_photo')) {

    $sidePhoto = $request
        ->file('side_photo')
        ->store('progress', 'public');
}

if ($request->hasFile('back_photo')) {

    $backPhoto = $request
        ->file('back_photo')
        ->store('progress', 'public');
}

ProgressLog::create([

    'user_id' => auth()->id(),

    'weight' => $request->weight,
    'bmi' => $bmi,
    'body_fat' => $request->body_fat,

    'chest' => $request->chest,
    'waist' => $request->waist,
    'arms' => $request->arms,
    'thighs' => $request->thighs,

    'front_photo' => $frontPhoto,
    'side_photo' => $sidePhoto,
    'back_photo' => $backPhoto,

    'notes' => $request->notes,
]);
$user->update([

    'weight' => $request->weight,

    'bmi' => $bmi,

]);

return redirect()
    ->route('member.progress')
    ->with(
        'success',
        'Progress Logged Successfully'
    );
}

}