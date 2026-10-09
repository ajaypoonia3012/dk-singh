@props(['active' => false])

<a {{ $attributes->class([
    'theme-navbar-link block w-full ps-3 pe-4 py-2 border-l-4 text-start text-base font-medium focus:outline-none transition duration-150 ease-in-out',
    'theme-surface-muted theme-text-primary theme-border' => $active,
    'border-transparent' => ! $active,
]) }}>
    {{ $slot }}
</a>
