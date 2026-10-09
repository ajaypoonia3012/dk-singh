<div class="card theme-surface-strong theme-text-on-strong p-4 p-md-5 rounded-4 my-5 border-0" data-contextual-cta="true">
    <div class="row align-items-center">
        <div class="col-lg-9">
            <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-2 d-inline-block">{{ $cta['badge'] }}</span>
            <h3 class="theme-text-on-strong fw-bold mb-2">{{ $cta['heading'] }}</h3>
            <p class="theme-text-on-strong-50 mb-4" style="max-width: 650px;">{{ $cta['description'] }}</p>
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <a href="{{ $cta['button_url'] }}" class="btn btn-warning btn-lg fw-bold px-4">{{ $cta['button_text'] }} &rarr;</a>
                @if(!empty($cta['secondary_button_text']) && !empty($cta['secondary_button_url']))
                    <a href="{{ $cta['secondary_button_url'] }}" class="btn btn-outline-light btn-lg px-4">{{ $cta['secondary_button_text'] }}</a>
                @endif
                <a href="{{ route('transformations.index') }}" class="btn btn-link theme-text-on-strong text-decoration-none">View Client Results &rarr;</a>
            </div>
        </div>
    </div>
</div>
