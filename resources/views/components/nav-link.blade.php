@props(['active' => false])

<a {{ $attributes->class([
    'theme-navbar-link inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out',
    'theme-text-primary theme-border' => $active,
    'border-transparent' => ! $active,
]) }}>
    {{ $slot }}
</a>
