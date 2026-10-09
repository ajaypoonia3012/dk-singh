@extends('layouts.app')

@section('title', 'Exercise Library & Movement Database | DK Singh Fitness')
@section('meta_description', 'Browse the comprehensive DK Singh Exercise Library with form cues, targeted muscle groups, difficulty ratings, and video tutorials.')

@push('meta')
    @php
        $hasActiveFilters = !empty($filters['category']) || !empty($filters['muscle']) || !empty($filters['equipment']) || !empty($filters['difficulty']) || !empty($filters['search']);
    @endphp
    @if($hasActiveFilters)
        <meta name="robots" content="noindex, follow">
    @else
        <meta name="robots" content="index, follow">
    @endif
    <link rel="canonical" href="{{ route('fitness.exercise-library') }}">
    <meta name="description" content="Explore DK Singh Fitness' comprehensive exercise library. Step-by-step biomechanical execution cues, primary muscles worked, common mistakes, and home-to-gym variations.">
@endpush

@section('content')
<div class="container py-4 py-lg-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('fitness.index') }}" class="text-decoration-none">Fitness</a></li>
            <li class="breadcrumb-item active" aria-current="page">Exercise Library</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="mb-4">
        <span class="badge bg-primary text-white px-3 py-2 fw-bold text-uppercase mb-2">Movement Database</span>
        <h1 class="display-4 fw-bold mb-2">DK Singh Exercise Library</h1>
        <p class="lead text-muted" style="max-width: 760px;">
            A comprehensive, biomechanically sound movement database. Browse step-by-step execution cues, muscle targets, breathing recommendations, and safe progression pathways.
        </p>
    </div>

    {{-- Multi-Axis Filter Form --}}
    <div class="card border rounded-4 shadow-sm p-4 mb-5 bg-white">
        <form method="GET" action="{{ route('fitness.exercise-library') }}">
            <div class="row g-3 align-items-end">

                {{-- Keyword Search --}}
                <div class="col-md-12 col-lg-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Search Exercise</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="e.g. Squat, Push-up, Deadlift..." value="{{ $filters['search'] ?? '' }}">
                    </div>
                </div>

                {{-- Category --}}
                <div class="col-6 col-md-3 col-lg-2">
                    <label class="form-label small fw-bold text-uppercase text-muted">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ ($filters['category'] ?? '') === $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Primary Muscle --}}
                <div class="col-6 col-md-3 col-lg-2">
                    <label class="form-label small fw-bold text-uppercase text-muted">Target Muscle</label>
                    <select name="muscle" class="form-select">
                        <option value="">All Muscles</option>
                        @foreach($muscles as $m)
                            <option value="{{ $m }}" {{ ($filters['muscle'] ?? '') === $m ? 'selected' : '' }}>
                                {{ $m }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Equipment --}}
                <div class="col-6 col-md-3 col-lg-2">
                    <label class="form-label small fw-bold text-uppercase text-muted">Equipment</label>
                    <select name="equipment" class="form-select">
                        <option value="">All Equipment</option>
                        @foreach($equipments as $eq)
                            <option value="{{ $eq }}" {{ ($filters['equipment'] ?? '') === $eq ? 'selected' : '' }}>
                                {{ $eq }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Difficulty --}}
                <div class="col-6 col-md-3 col-lg-2">
                    <label class="form-label small fw-bold text-uppercase text-muted">Difficulty</label>
                    <select name="difficulty" class="form-select">
                        <option value="">All Levels</option>
                        @foreach($difficulties as $diff)
                            <option value="{{ $diff }}" {{ ($filters['difficulty'] ?? '') === $diff ? 'selected' : '' }}>
                                {{ $diff }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                <span class="small text-muted">
                    Found <strong>{{ $exercises->total() }}</strong> {{ Str::plural('exercise', $exercises->total()) }}
                </span>
                <div class="d-flex gap-2">
                    @if($hasActiveFilters)
                        <a href="{{ route('fitness.exercise-library') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                            <i class="bi bi-x-circle me-1"></i>Reset
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">
                        Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Exercise Cards Grid --}}
    @if($exercises->count() > 0)
        <div class="row g-4">
            @foreach($exercises as $exercise)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border rounded-4 overflow-hidden bg-white hover-shadow transition-card d-flex flex-column">
                        <div style="height: 200px; overflow: hidden;" class="position-relative bg-light">
                            <img src="{{ $exercise->image_url }}" alt="{{ $exercise->name }}" class="w-100 h-100" style="object-fit: cover;">
                            <span class="position-absolute top-0 start-0 m-3 badge bg-dark bg-opacity-75 text-white">
                                {{ $exercise->exercise_category }}
                            </span>
                            @php
                                $diffClass = match($exercise->difficulty) {
                                    'Beginner' => 'bg-success',
                                    'Intermediate' => 'bg-primary',
                                    'Advanced' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="position-absolute top-0 end-0 m-3 badge {{ $diffClass }} text-white">
                                {{ $exercise->difficulty }}
                            </span>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="fw-bold text-dark mb-2">
                                <a href="{{ route('fitness.exercise-detail', $exercise->slug) }}" class="text-dark text-decoration-none">
                                    {{ $exercise->name }}
                                </a>
                            </h5>
                            <p class="text-muted small mb-3 flex-grow-1">
                                {{ Str::limit($exercise->short_description, 100) }}
                            </p>
                            
                            {{-- Quick Stats Tags --}}
                            <div class="d-flex flex-wrap gap-1 mb-3">
                                <span class="badge bg-light text-dark border small py-1 px-2">
                                    <i class="bi bi-bullseye text-primary me-1"></i>{{ $exercise->primary_muscle }}
                                </span>
                                <span class="badge bg-light text-dark border small py-1 px-2">
                                    <i class="bi bi-tools text-secondary me-1"></i>{{ $exercise->equipment }}
                                </span>
                                @if($exercise->movement_pattern)
                                    <span class="badge bg-light text-dark border small py-1 px-2">
                                        <i class="bi bi-arrow-repeat text-info me-1"></i>{{ $exercise->movement_pattern }}
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('fitness.exercise-detail', $exercise->slug) }}" class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-bold">
                                View Form Cues &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $exercises->links() }}
        </div>
    @else
        <div class="text-center py-5 bg-light rounded-4">
            <i class="bi bi-search fs-1 text-muted"></i>
            <h4 class="fw-bold mt-3">No exercises match your criteria</h4>
            <p class="text-muted">Try selecting a different muscle group, equipment option, or resetting filters.</p>
            <a href="{{ route('fitness.exercise-library') }}" class="btn btn-primary rounded-pill px-4">
                View All Exercises
            </a>
        </div>
    @endif

    {{-- Bottom Educational Strip --}}
    <section class="mt-5 p-4 p-lg-5 rounded-4 bg-light border">
        <h4 class="fw-bold mb-3"><i class="bi bi-shield-check text-primary me-2"></i>DK Singh Form Philosophy</h4>
        <div class="row g-4">
            <div class="col-md-4">
                <h6 class="fw-bold text-dark">Strict Technique Before Load</h6>
                <p class="text-muted small mb-0">Never add weight at the expense of range of motion or spinal alignment. Quality reps build resilient connective tissue.</p>
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold text-dark">Individual Biomechanics</h6>
                <p class="text-muted small mb-0">Femur length, hip socket depth, and arm span dictate your optimal stance and grip width. Find your anatomical sweet spot.</p>
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold text-dark">Mind-Muscle Connection</h6>
                <p class="text-muted small mb-0">Initiate each contraction by actively firing the target muscle rather than swinging momentum through secondary joints.</p>
            </div>
        </div>
    </section>

</div>
@endsection
