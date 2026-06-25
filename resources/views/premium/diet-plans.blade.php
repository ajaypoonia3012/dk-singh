<!DOCTYPE html>
<html>
<head>
    <title>Premium Diet Plans</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container py-5">

    <div class="mb-4">
        <h1 class="fw-bold">Premium Diet Plans</h1>

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
            Basic Diet Plans
        </div>

        <div class="card-body">

            @foreach($basicPlans as $plan)

                <div class="mb-4">

                    <h4>{{ $plan['title'] }}</h4>

                    <p>{{ $plan['description'] }}</p>

                </div>

            @endforeach

        </div>

    </div>

    @if($tier === 'pro' || $tier === 'elite')

        <div class="card shadow-sm mb-5">

            <div class="card-header bg-primary text-white">
                Pro Diet Plans
            </div>

            <div class="card-body">

                @foreach($proPlans as $plan)

                    <div class="mb-4">

                        <h4>{{ $plan['title'] }}</h4>

                        <p>{{ $plan['description'] }}</p>

                    </div>

                @endforeach

            </div>

        </div>

    @endif

    @if($tier === 'elite')

        <div class="card shadow-sm mb-5">

            <div class="card-header bg-dark text-white">
                Elite Diet Plans
            </div>

            <div class="card-body">

                @foreach($elitePlans as $plan)

                    <div class="mb-4">

                        <h4>{{ $plan['title'] }}</h4>

                        <p>{{ $plan['description'] }}</p>

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