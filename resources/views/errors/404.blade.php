@extends('layouts.app')

@section('title', 'Page Not Found (404) | DK Singh Fitness & Nutrition')
@section('meta_description', 'The page you were looking for could not be found. Explore our workout programs, nutrition guides, and fitness resources.')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-20 px-4">
    <div class="max-w-2xl mx-auto text-center">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-amber-500/10 text-amber-500 mb-8 border border-amber-500/20">
            <span class="text-4xl font-black">404</span>
        </div>
        
        <h1 class="text-4xl md:text-5xl font-black theme-text-secondary mb-4 tracking-tight">
            Page Not Found
        </h1>
        
        <p class="text-lg theme-text-neutral mb-8 leading-relaxed max-w-lg mx-auto">
            We couldn't find the page or resource you were looking for. It may have been moved or updated. Let's get you back on track toward your fitness goals.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4 mb-12">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-stone-900 font-bold transition shadow-lg shadow-amber-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Return Home
            </a>
            <a href="{{ route('fitness.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-stone-800 hover:bg-stone-700 text-stone-100 font-bold transition border border-stone-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Explore Fitness Hub
            </a>
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-stone-800 hover:bg-stone-700 text-stone-100 font-bold transition border border-stone-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Read Expert Articles
            </a>
        </div>

        <div class="p-6 rounded-2xl bg-stone-900/60 border border-stone-800 text-left">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-amber-500 mb-3">Popular Destinations</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                <a href="{{ route('programs.index') }}" class="theme-text-neutral hover:text-amber-400 transition">Programs</a>
                <a href="{{ route('services.index') }}" class="theme-text-neutral hover:text-amber-400 transition">Services</a>
                <a href="{{ route('transformations.index') }}" class="theme-text-neutral hover:text-amber-400 transition">Transformations</a>
                <a href="{{ route('contact') }}" class="theme-text-neutral hover:text-amber-400 transition">Contact Us</a>
            </div>
        </div>
    </div>
</div>
@endsection
