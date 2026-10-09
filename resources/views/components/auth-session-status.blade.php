@props(['status'])

@if($status)
    <div {{ $attributes->class(['theme-status-success', 'theme-radius', 'px-4', 'py-3', 'text-sm']) }} role="status">
        {{ $status }}
    </div>
@endif
