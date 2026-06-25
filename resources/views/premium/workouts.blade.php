<!DOCTYPE html>
<html>
<head>
    <title>Premium Workouts</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container py-5">

    <div class="mb-4">
        <h1 class="fw-bold">Premium Workouts</h1>
        <p class="text-muted">
            Your Current Membership:
            <strong class="text-primary text-capitalize">{{ $tier }}</strong>
        </p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm mb-5">
        <div class="card-header bg-success text-white">
            Basic Workouts
        </div>

        <div class="card-body">

            @foreach($basicWorkouts as $workout)

                <div class="mb-4">

                    <h4>{{ $workout['title'] }}</h4>

                    <p>{{ $workout['description'] }}</p>

                </div>

            @endforeach

        </div>
    </div>

    @if($tier === 'pro' || $tier === 'elite')

        <div class="card shadow-sm mb-5">

            <div class="card-header bg-primary text-white">
                Pro Workouts
            </div>

            <div class="card-body">

                @foreach($proWorkouts as $workout)

                    <div class="mb-4">

                        <h4>{{ $workout['title'] }}</h4>

                        <p>{{ $workout['description'] }}</p>

                    </div>

                @endforeach

            </div>

        </div>

    @endif

    @if($tier === 'elite')

        <div class="card shadow-sm mb-5">

            <div class="card-header bg-dark text-white">
                Elite Workouts
            </div>

            <div class="card-body">

                @foreach($eliteWorkouts as $workout)

                    <div class="mb-4">

                        <h4>{{ $workout['title'] }}</h4>

                        <p>{{ $workout['description'] }}</p>

                    </div>

                @endforeach

            </div>

        </div>

    @endif

    <a href="/member/dashboard" class="btn btn-secondary">
        Back to Dashboard
    </a>

</div>

</body>
</html>