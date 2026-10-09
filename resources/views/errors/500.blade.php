@extends('layouts.app')

@section('title', 'Server Error (500) | DK Singh Fitness & Nutrition')
@section('meta_description', 'An unexpected error occurred. Our team has been notified.')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-20 px-4">
    <div class="max-w-2xl mx-auto text-center">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-red-500/10 text-red-500 mb-8 border border-red-500/20">
            <span class="text-4xl font-black">500</span>
        </div>
        
        <h1 class="text-4xl md:text-5xl font-black theme-text-secondary mb-4 tracking-tight">
            Unexpected Error
        </h1>
        
        <p class="text-lg theme-text-neutral mb-8 leading-relaxed max-w-lg mx-auto">
            Something went wrong while processing your request. Please try refreshing the page or return to the home screen.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-stone-900 font-bold transition shadow-lg shadow-amber-500/20">
                Return Home
            </a>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-stone-800 hover:bg-stone-700 text-stone-100 font-bold transition border border-stone-700">
                Contact Support
            </a>
        </div>
    </div>
</div>
@endsection
