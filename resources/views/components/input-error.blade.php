@props(['messages'])

@if($messages)
    <ul {{ $attributes->class(['theme-text-danger', 'text-sm', 'theme-stack-sm']) }} role="alert">
        @foreach((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
