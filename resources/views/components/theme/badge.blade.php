@props(['status' => null])

<span {{ $attributes->class([
    'theme-badge',
    "theme-status-{$status}" => in_array($status, ['success', 'warning', 'danger', 'info', 'neutral'], true),
]) }}>{{ $slot }}</span>
