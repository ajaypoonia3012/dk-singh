@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto px-6 py-12">

<h1 class="text-4xl font-black mb-8">
Progress Tracker
</h1>

<div class="grid md:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-3xl shadow-xl p-6">
        <div class="text-sm text-gray-500">
            Starting Weight
        </div>

        <div class="text-3xl font-black">
            {{ $startingWeight ?? '--' }}
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl p-6">
        <div class="text-sm text-gray-500">
            Current Weight
        </div>

        <div class="text-3xl font-black">
            {{ $currentWeight ?? '--' }}
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl p-6">
        <div class="text-sm text-gray-500">
            Weight Change
        </div>

        <div class="text-3xl font-black text-green-600">
            {{ $weightLost ?? 0 }}
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl p-6">
        <div class="text-sm text-gray-500">
            Latest BMI
        </div>

        <div class="text-3xl font-black">
            {{ $latest?->bmi ?? '--' }}
        </div>
    </div>

</div>

<div class="grid md:grid-cols-3 gap-6 mb-8">

    <div class="bg-white rounded-3xl shadow-xl p-6">

        <div class="text-sm text-gray-500">
            Starting BMI
        </div>

        <div class="text-3xl font-black">
            {{ $startingBMI ?? '--' }}
        </div>

    </div>

    <div class="bg-white rounded-3xl shadow-xl p-6">

        <div class="text-sm text-gray-500">
            Current BMI
        </div>

        <div class="text-3xl font-black">
            {{ $currentBMI ?? '--' }}
        </div>

    </div>

    <div class="bg-white rounded-3xl shadow-xl p-6">

        <div class="text-sm text-gray-500">
            BMI Change
        </div>

        <div class="text-3xl font-black text-green-600">
            {{ number_format($currentBMI - $startingBMI, 1) }}
        </div>

    </div>

</div>

<div class="grid md:grid-cols-3 gap-6 mb-8">

    <div class="bg-white rounded-3xl shadow-xl p-6">

        <div class="text-sm text-gray-500">
            Starting Body Fat
        </div>

        <div class="text-3xl font-black">
            {{ $startingBodyFat ?? '--' }}
        </div>

    </div>

    <div class="bg-white rounded-3xl shadow-xl p-6">

        <div class="text-sm text-gray-500">
            Current Body Fat
        </div>

        <div class="text-3xl font-black">
            {{ $currentBodyFat ?? '--' }}
        </div>

    </div>

    <div class="bg-white rounded-3xl shadow-xl p-6">

        <div class="text-sm text-gray-500">
            Body Fat Change
        </div>

        <div class="text-3xl font-black text-green-600">
            {{ $bodyFatLost ?? 0 }}
        </div>

    </div>

</div>




@if(!$alreadySubmittedToday)

<a href="{{ route('member.progress.create') }}"
   class="bg-black text-white px-6 py-3 rounded-xl">

    Add Check-In

</a>

@else

<button
    type="button"
    onclick="alert('You have already submitted today\'s check-in.')"
    class="bg-green-600 text-white px-6 py-3 rounded-xl">

    ✓ Today's Check-In Submitted

</button>

@endif


<div class="bg-white rounded-3xl shadow-xl p-8 mt-8">

    <h2 class="text-2xl font-bold mb-6">
        Weight Progress
    </h2>

    <canvas id="weightChart"></canvas>

</div>

@if(session('error'))

<div class="bg-red-100 text-red-700 p-4 rounded-xl mt-6">
    {{ session('error') }}
</div>

@endif

@if(session('success'))

<div class="bg-green-100 text-green-700 p-4 rounded-xl mt-6">
    {{ session('success') }}
</div>

@endif

<div class="mt-8 space-y-6">

@foreach($logs as $log)

<div class="bg-white rounded-3xl shadow-xl p-6">

<h3 class="font-bold text-xl">

{{ $log->created_at->format('d M Y') }}

</h3>

<div class="grid md:grid-cols-4 gap-4 mt-4">

    <div>
        <div class="text-xs text-gray-500">Weight</div>
        <div class="font-bold">{{ $log->weight }} kg</div>
    </div>

    <div>
        <div class="text-xs text-gray-500">BMI</div>
        <div class="font-bold">{{ $log->bmi }}</div>
    </div>

    <div>
        <div class="text-xs text-gray-500">Body Fat</div>
        <div class="font-bold">{{ $log->body_fat }}%</div>
    </div>

    <div>
        <div class="text-xs text-gray-500">Chest</div>
        <div class="font-bold">{{ $log->chest }} cm</div>
    </div>

    <div>
        <div class="text-xs text-gray-500">Waist</div>
        <div class="font-bold">{{ $log->waist }} cm</div>
    </div>

    <div>
        <div class="text-xs text-gray-500">Arms</div>
        <div class="font-bold">{{ $log->arms }} cm</div>
    </div>

    <div>
        <div class="text-xs text-gray-500">Thighs</div>
        <div class="font-bold">{{ $log->thighs }} cm</div>
    </div>

</div>

@if($log->notes)

<p class="mt-3">
@if($log->notes)

<div class="mt-4 p-4 bg-gray-50 rounded-xl">

    <div class="text-sm font-semibold mb-1">
        Notes
    </div>

    <div class="text-gray-700">
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
                class="rounded-xl shadow">

        </div>

    @endif

    @if($log->side_photo)

        <div>

            <div class="text-sm font-medium mb-2">
                Side
            </div>

            <img
                src="{{ asset('storage/'.$log->side_photo) }}"
                class="rounded-xl shadow">

        </div>

    @endif

    @if($log->back_photo)

        <div>

            <div class="text-sm font-medium mb-2">
                Back
            </div>

            <img
                src="{{ asset('storage/'.$log->back_photo) }}"
                class="rounded-xl shadow">

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