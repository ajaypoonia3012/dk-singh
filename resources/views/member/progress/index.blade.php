@extends('layouts.app')

@section('content')

<div class="theme-page-container py-12">

<h1 class="text-4xl font-black mb-8">
Progress Tracker
</h1>

<div class="grid md:grid-cols-4 theme-content-gap mb-8">

    <div class="theme-card theme-radius theme-shadow p-6">
        <div class="text-sm theme-text-neutral">
            Starting Weight
        </div>

        <div class="text-3xl font-black">
            {{ $startingWeight ?? '--' }}
        </div>
    </div>

    <div class="theme-card theme-radius theme-shadow p-6">
        <div class="text-sm theme-text-neutral">
            Current Weight
        </div>

        <div class="text-3xl font-black">
            {{ $currentWeight ?? '--' }}
        </div>
    </div>

    <div class="theme-card theme-radius theme-shadow p-6">
        <div class="text-sm theme-text-neutral">
            Weight Change
        </div>

        <div class="text-3xl font-black theme-text-success">
            {{ $weightLost ?? 0 }}
        </div>
    </div>

    <div class="theme-card theme-radius theme-shadow p-6">
        <div class="text-sm theme-text-neutral">
            Latest BMI
        </div>

        <div class="text-3xl font-black">
            {{ $latest?->bmi ?? '--' }}
        </div>
    </div>

</div>

<div class="grid md:grid-cols-3 theme-content-gap mb-8">

    <div class="theme-card theme-radius theme-shadow p-6">

        <div class="text-sm theme-text-neutral">
            Starting BMI
        </div>

        <div class="text-3xl font-black">
            {{ $startingBMI ?? '--' }}
        </div>

    </div>

    <div class="theme-card theme-radius theme-shadow p-6">

        <div class="text-sm theme-text-neutral">
            Current BMI
        </div>

        <div class="text-3xl font-black">
            {{ $currentBMI ?? '--' }}
        </div>

    </div>

    <div class="theme-card theme-radius theme-shadow p-6">

        <div class="text-sm theme-text-neutral">
            BMI Change
        </div>

        <div class="text-3xl font-black theme-text-success">
            {{ number_format($currentBMI - $startingBMI, 1) }}
        </div>

    </div>

</div>

<div class="grid md:grid-cols-3 theme-content-gap mb-8">

    <div class="theme-card theme-radius theme-shadow p-6">

        <div class="text-sm theme-text-neutral">
            Starting Body Fat
        </div>

        <div class="text-3xl font-black">
            {{ $startingBodyFat ?? '--' }}
        </div>

    </div>

    <div class="theme-card theme-radius theme-shadow p-6">

        <div class="text-sm theme-text-neutral">
            Current Body Fat
        </div>

        <div class="text-3xl font-black">
            {{ $currentBodyFat ?? '--' }}
        </div>

    </div>

    <div class="theme-card theme-radius theme-shadow p-6">

        <div class="text-sm theme-text-neutral">
            Body Fat Change
        </div>

        <div class="text-3xl font-black theme-text-success">
            {{ $bodyFatLost ?? 0 }}
        </div>

    </div>

</div>




@if(!$alreadySubmittedToday)

<a href="{{ route('member.progress.create') }}"
   class="theme-surface-strong theme-text-on-strong px-6 py-3 theme-radius">

    Add Check-In

</a>

@else

<button
    type="button"
    onclick="alert('You have already submitted today\'s check-in.')"
    class="theme-status-success theme-text-on-strong px-6 py-3 theme-radius">

    <svg class="inline-block w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" /></svg> Today's Check-In Submitted

</button>

@endif


<div class="theme-card theme-radius theme-shadow theme-card-padding mt-8">

    <h2 class="text-2xl font-bold mb-6">
        Weight Progress
    </h2>

    <canvas id="weightChart"></canvas>

</div>

@if(session('error'))

<div class="theme-status-danger theme-text-danger p-4 theme-radius mt-6">
    {{ session('error') }}
</div>

@endif

@if(session('success'))

<div class="theme-status-success theme-text-success p-4 theme-radius mt-6">
    {{ session('success') }}
</div>

@endif

<div class="mt-8 theme-stack-lg">

@foreach($logs as $log)

<div class="theme-card theme-radius theme-shadow p-6">

<h3 class="font-bold text-xl">

{{ $log->created_at->format('d M Y') }}

</h3>

<div class="grid md:grid-cols-4 gap-4 mt-4">

    <div>
        <div class="text-xs theme-text-neutral">Weight</div>
        <div class="font-bold">{{ $log->weight }} kg</div>
    </div>

    <div>
        <div class="text-xs theme-text-neutral">BMI</div>
        <div class="font-bold">{{ $log->bmi }}</div>
    </div>

    <div>
        <div class="text-xs theme-text-neutral">Body Fat</div>
        <div class="font-bold">{{ $log->body_fat }}%</div>
    </div>

    <div>
        <div class="text-xs theme-text-neutral">Chest</div>
        <div class="font-bold">{{ $log->chest }} cm</div>
    </div>

    <div>
        <div class="text-xs theme-text-neutral">Waist</div>
        <div class="font-bold">{{ $log->waist }} cm</div>
    </div>

    <div>
        <div class="text-xs theme-text-neutral">Arms</div>
        <div class="font-bold">{{ $log->arms }} cm</div>
    </div>

    <div>
        <div class="text-xs theme-text-neutral">Thighs</div>
        <div class="font-bold">{{ $log->thighs }} cm</div>
    </div>

</div>

@if($log->notes)

<p class="mt-3">
@if($log->notes)

<div class="mt-4 p-4 theme-surface-muted theme-radius">

    <div class="text-sm font-semibold mb-1">
        Notes
    </div>

    <div class="theme-text-neutral">
        {{ $log->notes }}
    </div>

</div>

@endif</p>

@endif

@if(
    $log->front_photo ||
    $log->side_photo ||
    $log->back_photo
)

<hr class="my-6">

<h4 class="font-bold text-lg mb-4">
    Transformation Photos
</h4>

<div class="grid md:grid-cols-3 gap-4">

    @if($log->front_photo)

        <div>

            <div class="text-sm font-medium mb-2">
                Front
            </div>

            <img
                src="{{ asset('storage/'.$log->front_photo) }}"
                class="theme-radius shadow">

        </div>

    @endif

    @if($log->side_photo)

        <div>

            <div class="text-sm font-medium mb-2">
                Side
            </div>

            <img
                src="{{ asset('storage/'.$log->side_photo) }}"
                class="theme-radius shadow">

        </div>

    @endif

    @if($log->back_photo)

        <div>

            <div class="text-sm font-medium mb-2">
                Back
            </div>

            <img
                src="{{ asset('storage/'.$log->back_photo) }}"
                class="theme-radius shadow">

        </div>

    @endif

</div>

@endif

</div>

@endforeach

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx = document.getElementById(
    'weightChart'
);

new Chart(ctx, {

    type: 'line',

    data: {

        labels: @json($weightLabels),

        datasets: [{

            label: 'Weight (kg)',

            data: @json($weightData),

            borderWidth: 3,

            tension: 0.4

        }]
    },

    options: {

        responsive: true,

        plugins: {

            legend: {
                display: true
            }

        }

    }

});

</script>


@endsection
