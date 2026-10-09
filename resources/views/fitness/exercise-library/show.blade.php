@extends('layouts.app')

@section('title', $exercise->seo_title ?? ($exercise->name . ' Technique, Form & Execution Guide | DK Singh Fitness'))
@section('meta_description', $exercise->meta_description ?? Str::limit(strip_tags($exercise->short_description), 155))
@section('canonical', $exercise->canonical_url ?? route('fitness.exercise-detail', $exercise->slug))
@section('og_title', $exercise->name . ' Form & Execution Guide | DK Singh Fitness')
@section('og_description', Str::limit(strip_tags($exercise->short_description), 155))
@section('og_image', $exercise->image_url)
@section('og_type', 'article')

@push('meta')

    {{-- JSON-LD Exercise / HowTo Structured Data --}}
    @php
        $steps = [];
        if (!empty($exercise->execution_steps) && is_array($exercise->execution_steps)) {
            foreach ($exercise->execution_steps as $index => $step) {
                $steps[] = [
                    '@type' => 'HowToStep',
                    'url' => route('fitness.exercise-detail', $exercise->slug) . '#step-' . ($index + 1),
                    'name' => 'Step ' . ($index + 1),
                    'itemListElement' => [
                        [
                            '@type' => 'HowToDirection',
                            'text' => $step,
                        ],
                    ],
                ];
            }
        }

        $howToSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'HowTo',
            'name' => 'How to Perform ' . $exercise->name . ' with Correct Form',
            'description' => $exercise->short_description,
            'image' => $exercise->image_url,
            'totalTime' => 'PT5M',
            'estimatedCost' => [
                '@type' => 'MonetaryAmount',
                'currency' => 'INR',
                'value' => '0',
            ],
            'supply' => [
                [
                    '@type' => 'HowToSupply',
                    'name' => $exercise->equipment,
                ],
            ],
            'tool' => [
                [
                    '@type' => 'HowToTool',
                    'name' => $exercise->equipment,
                ],
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'DK Singh Fitness & Nutrition',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo.png'),
                ],
            ],
        ];

        if (!empty($steps)) {
            $howToSchema['step'] = $steps;
        }

        $faqEntities = [];
        if (!empty($exercise->faqs) && is_array($exercise->faqs)) {
            foreach ($exercise->faqs as $faq) {
                $faqEntities[] = [
                    '@type' => 'Question',
                    'name' => $faq['question'] ?? '',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq['answer'] ?? '',
                    ],
                ];
            }
        }
    @endphp
    <script type="application/ld+json">
    {!! json_encode($howToSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>

    @if(!empty($faqEntities))
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faqEntities,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    @endif
@endpush

@section('content')
<div class="container py-4 py-lg-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('fitness.index') }}" class="text-decoration-none">Fitness</a></li>
            <li class="breadcrumb-item"><a href="{{ route('fitness.exercise-library') }}" class="text-decoration-none">Exercise Library</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $exercise->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">

        {{-- Main Article Content Column --}}
        <div class="col-lg-8">

            {{-- Title & Meta Badges --}}
            <div class="mb-4">
                <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                    <span class="badge bg-primary text-white px-3 py-1">{{ $exercise->exercise_category }}</span>
                    @php
                        $diffClass = match($exercise->difficulty) {
                            'Beginner' => 'bg-success',
                            'Intermediate' => 'bg-info text-white',
                            'Advanced' => 'bg-danger',
                            default => 'bg-secondary'
                        };
                    @endphp
                    <span class="badge {{ $diffClass }} px-3 py-1">{{ $exercise->difficulty }}</span>
                    @if($exercise->movement_pattern)
                        <span class="badge bg-light text-dark border px-3 py-1">{{ $exercise->movement_pattern }} Pattern</span>
                    @endif
                </div>

                <h1 class="display-4 fw-bold mb-3">{{ $exercise->name }}</h1>

                <p class="lead text-muted mb-0">
                    {{ $exercise->short_description }}
                </p>
            </div>

            {{-- Hero Visual / Video --}}
            <div class="mb-5 rounded-4 overflow-hidden shadow-sm border bg-light">
                @if($exercise->video_url)
                    <div class="ratio ratio-16x9">
                        <iframe src="{{ $exercise->video_url }}" title="{{ $exercise->name }}" allowfullscreen></iframe>
                    </div>
                @else
                    <div style="max-height: 480px; overflow: hidden;">
                        <img src="{{ $exercise->image_url }}" alt="{{ $exercise->name }} correct form demonstration" class="w-100 h-100" style="object-fit: cover;">
                    </div>
                @endif
            </div>

            {{-- Setup Instructions --}}
            @if($exercise->setup)
            <section class="mb-5">
                <h3 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Starting Setup & Stance</h3>
                <div class="p-4 rounded-4 bg-light border">
                    <p class="mb-0 text-dark" style="font-size: 1.05rem; line-height: 1.7;">
                        {{ $exercise->setup }}
                    </p>
                </div>
            </section>
            @endif

            {{-- Execution Steps --}}
            @if(!empty($exercise->execution_steps) && is_array($exercise->execution_steps))
            <section class="mb-5">
                <h3 class="fw-bold mb-3"><i class="bi bi-list-ol text-primary me-2"></i>Step-by-Step Execution</h3>
                <div class="d-flex flex-column gap-3">
                    @foreach($exercise->execution_steps as $idx => $step)
                        <div class="d-flex gap-3 p-3 rounded-3 bg-white border" id="step-{{ $idx + 1 }}">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0 fw-bold" style="width: 38px; height: 38px;">
                                {{ $idx + 1 }}
                            </div>
                            <div class="pt-1">
                                <p class="mb-0 text-dark" style="font-size: 1.02rem; line-height: 1.6;">{{ $step }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- Breathing Guidance --}}
            @if($exercise->breathing_guidance)
            <section class="mb-5">
                <div class="p-4 rounded-4 border border-info bg-info bg-opacity-10 d-flex gap-3 align-items-start">
                    <i class="bi bi-wind fs-2 text-info flex-shrink-0"></i>
                    <div>
                        <h4 class="fw-bold text-dark mb-1">Breathing & Intra-Abdominal Pressure</h4>
                        <p class="text-muted mb-0" style="line-height: 1.6;">
                            {{ $exercise->breathing_guidance }}
                        </p>
                    </div>
                </div>
            </section>
            @endif

            {{-- Common Mistakes --}}
            @if(!empty($exercise->common_mistakes) && is_array($exercise->common_mistakes))
            <section class="mb-5">
                <h3 class="fw-bold mb-3"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Common Mistakes to Avoid</h3>
                <div class="list-group list-group-flush rounded-4 border">
                    @foreach($exercise->common_mistakes as $mistake)
                        <div class="list-group-item p-3 d-flex align-items-start gap-3 bg-white">
                            <i class="bi bi-x-circle-fill text-danger fs-5 flex-shrink-0 mt-1"></i>
                            <div>
                                <strong class="text-dark d-block">{{ $mistake }}</strong>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- Variations & Modifications Matrix --}}
            @if($exercise->beginner_modification || $exercise->advanced_variation || $exercise->home_variation || $exercise->gym_variation)
            <section class="mb-5">
                <h3 class="fw-bold mb-3"><i class="bi bi-shuffle text-primary me-2"></i>Variations & Progressions</h3>
                <div class="row g-3">
                    @if($exercise->beginner_modification)
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border bg-light h-100">
                            <span class="badge bg-success mb-2">Beginner Regression</span>
                            <h6 class="fw-bold text-dark mb-1">Modification</h6>
                            <p class="text-muted small mb-0">{{ $exercise->beginner_modification }}</p>
                        </div>
                    </div>
                    @endif

                    @if($exercise->advanced_variation)
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border bg-light h-100">
                            <span class="badge bg-danger mb-2">Advanced Progression</span>
                            <h6 class="fw-bold text-dark mb-1">Variation</h6>
                            <p class="text-muted small mb-0">{{ $exercise->advanced_variation }}</p>
                        </div>
                    </div>
                    @endif

                    @if($exercise->home_variation)
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border bg-light h-100">
                            <span class="badge bg-info text-white mb-2">Home Workout</span>
                            <h6 class="fw-bold text-dark mb-1">Home Alternative</h6>
                            <p class="text-muted small mb-0">{{ $exercise->home_variation }}</p>
                        </div>
                    </div>
                    @endif

                    @if($exercise->gym_variation)
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border bg-light h-100">
                            <span class="badge bg-primary mb-2">Commercial Gym</span>
                            <h6 class="fw-bold text-dark mb-1">Gym Alternative</h6>
                            <p class="text-muted small mb-0">{{ $exercise->gym_variation }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </section>
            @endif

            {{-- Safety Considerations --}}
            @if($exercise->safety_considerations)
            <section class="mb-5">
                <div class="p-4 rounded-4 bg-light border d-flex gap-3 align-items-start">
                    <i class="bi bi-shield-exclamation fs-3 text-warning flex-shrink-0"></i>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Safety & Joint Considerations</h5>
                        <p class="text-muted mb-0 small" style="line-height: 1.6;">
                            {{ $exercise->safety_considerations }}
                        </p>
                    </div>
                </div>
            </section>
            @endif

            {{-- FAQs Accordion --}}
            @if(!empty($exercise->faqs) && is_array($exercise->faqs))
            <section class="mb-5">
                <h3 class="fw-bold mb-3"><i class="bi bi-question-circle text-primary me-2"></i>Frequently Asked Questions</h3>
                <div class="accordion" id="exerciseFaqAccordion">
                    @foreach($exercise->faqs as $fIndex => $faq)
                        <div class="accordion-item border rounded-3 mb-2 overflow-hidden">
                            <h2 class="accordion-header" id="heading-{{ $fIndex }}">
                                <button class="accordion-button {{ $fIndex > 0 ? 'collapsed' : '' }} fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $fIndex }}" aria-expanded="{{ $fIndex === 0 ? 'true' : 'false' }}">
                                    {{ $faq['question'] ?? '' }}
                                </button>
                            </h2>
                            <div id="collapse-{{ $fIndex }}" class="accordion-collapse collapse {{ $fIndex === 0 ? 'show' : '' }}" data-bs-parent="#exerciseFaqAccordion">
                                <div class="accordion-body text-muted" style="line-height: 1.6;">
                                    {{ $faq['answer'] ?? '' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- Contextual Related DK Singh Blog Posts --}}
            @if($relatedArticles->count() > 0)
            <section class="mb-5 pt-4 border-top">
                <h4 class="fw-bold mb-3">Related Guides & Workouts</h4>
                <div class="row g-3">
                    @foreach($relatedArticles as $post)
                        <div class="col-md-4">
                            <div class="card h-100 border rounded-3 p-3 bg-white hover-shadow transition-card">
                                <span class="badge bg-light text-dark border align-self-start mb-2">{{ $post->category ? $post->category->name : 'Fitness' }}</span>
                                <h6 class="fw-bold mb-1">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none">
                                        {{ Str::limit($post->title, 55) }}
                                    </a>
                                </h6>
                                <p class="text-muted small mb-0">{{ Str::limit(strip_tags($post->excerpt ?? $post->content), 70) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
            @endif

        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">

            {{-- Quick Facts Card --}}
            <div class="card border rounded-4 shadow-sm p-4 bg-white mb-4 position-sticky" style="top: 90px;">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                    <i class="bi bi-info-circle-fill text-primary me-2"></i>Quick Facts
                </h5>

                <div class="d-flex flex-column gap-3">
                    <div>
                        <span class="small text-muted text-uppercase fw-bold d-block">Target Muscle</span>
                        <strong class="text-dark fs-6">{{ $exercise->primary_muscle }}</strong>
                    </div>

                    @if(!empty($exercise->secondary_muscles))
                    <div>
                        <span class="small text-muted text-uppercase fw-bold d-block">Secondary Muscles</span>
                        <span class="text-dark small">
                            {{ is_array($exercise->secondary_muscles) ? implode(', ', $exercise->secondary_muscles) : $exercise->secondary_muscles }}
                        </span>
                    </div>
                    @endif

                    <div>
                        <span class="small text-muted text-uppercase fw-bold d-block">Required Equipment</span>
                        <strong class="text-dark small">{{ $exercise->equipment }}</strong>
                    </div>

                    <div>
                        <span class="small text-muted text-uppercase fw-bold d-block">Difficulty</span>
                        <span class="badge {{ $diffClass }}">{{ $exercise->difficulty }}</span>
                    </div>

                    @if($exercise->movement_pattern)
                    <div>
                        <span class="small text-muted text-uppercase fw-bold d-block">Movement Pattern</span>
                        <span class="text-dark small">{{ $exercise->movement_pattern }}</span>
                    </div>
                    @endif

                    <div>
                        <span class="small text-muted text-uppercase fw-bold d-block">Category</span>
                        <a href="{{ route('fitness.exercise-library', ['category' => $exercise->exercise_category]) }}" class="badge bg-light text-primary border text-decoration-none">
                            {{ $exercise->exercise_category }}
                        </a>
                    </div>
                </div>

                {{-- DK Singh Form Check CTA --}}
                <div class="mt-4 pt-3 border-top">
                    <div class="p-3 bg-light rounded-3 text-center">
                        <i class="bi bi-camera-video-fill text-primary fs-3 mb-2 d-inline-block"></i>
                        <h6 class="fw-bold text-dark mb-1">Want Personal Form Feedback?</h6>
                        <p class="text-muted small mb-3">Submit your training clips to Coach DK Singh for bio-mechanical review.</p>
                        <a href="/programs" class="btn btn-primary btn-sm rounded-pill w-100 fw-bold">
                            Join 1-on-1 Coaching
                        </a>
                    </div>
                </div>
            </div>

            {{-- Related Exercises in Same Category --}}
            @if($relatedExercises->count() > 0)
            <div class="card border rounded-4 shadow-sm p-4 bg-white mb-4">
                <h6 class="fw-bold text-dark mb-3">Similar {{ $exercise->exercise_category }} Movements</h6>
                <div class="d-flex flex-column gap-3">
                    @foreach($relatedExercises as $rel)
                        <a href="{{ route('fitness.exercise-detail', $rel->slug) }}" class="d-flex gap-3 text-decoration-none group">
                            <div style="width: 70px; height: 60px; overflow: hidden;" class="rounded-3 flex-shrink-0 bg-light">
                                <img src="{{ $rel->image_url }}" alt="{{ $rel->name }}" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div>
                                <strong class="d-block text-dark small">{{ $rel->name }}</strong>
                                <span class="text-muted small">{{ $rel->primary_muscle }} &bull; {{ $rel->difficulty }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

    </div>

</div>
@endsection
