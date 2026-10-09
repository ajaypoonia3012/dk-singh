@extends('layouts.app')

@section('content')

<div class="min-h-screen theme-surface-muted theme-card-padding">

    <div class="max-w-7xl mx-auto">

        <!-- HEADER -->

        <div class="flex items-center justify-between mb-10">

            <div>
                <h1 class="text-4xl font-black theme-text-secondary">
                    {{ $setting->site_name }} Admin
                </h1>

                <p class="theme-text-neutral mt-2">
                    Manage programs, transformations, clients and business.
                </p>
            </div>

            <img
                src="{{ asset('images/logo.png') }}"
                class="w-16 h-16 object-contain"
                alt="Logo"
            >

        </div>

        <!-- STATS -->

        <div class="grid md:grid-cols-4 theme-content-gap mb-10">

            <div class="theme-card theme-radius p-6 theme-shadow">
                <h3 class="theme-text-neutral text-sm mb-3">Programs</h3>
                <div class="text-4xl font-black theme-text-primary">{{ $totalPrograms }}</div>
            </div>

            <div class="theme-card theme-radius p-6 theme-shadow">
                <h3 class="theme-text-neutral text-sm mb-3">Clients</h3>
                <div class="text-4xl font-black theme-text-secondary">{{ $totalClients }}</div>
            </div>

            <div class="theme-card theme-radius p-6 theme-shadow">
                <h3 class="theme-text-neutral text-sm mb-3">Transformations</h3>
                <div class="text-4xl font-black theme-text-secondary">{{ $totalTransformations }}</div>
            </div>

            <div class="theme-card theme-radius p-6 theme-shadow">
                <h3 class="theme-text-neutral text-sm mb-3">Orders</h3>
                <div class="text-4xl font-black theme-text-secondary">{{ $totalOrders }}</div>
            </div>

        </div>

        <!-- QUICK ACTIONS -->

        <div class="grid md:grid-cols-3 theme-content-gap">

            <a href="#"
               class="theme-surface-strong theme-text-on-strong theme-card-padding theme-radius hover:scale-105 transition duration-300">

                <h2 class="text-2xl font-bold mb-2">
                    Manage Programs
                </h2>

                <p class="theme-text-neutral">
                    Add and update coaching programs.
                </p>

            </a>

            <a href="#"
               class="theme-status-warning theme-text-secondary theme-card-padding theme-radius hover:scale-105 transition duration-300">

                <h2 class="text-2xl font-bold mb-2">
                    Client Transformations
                </h2>

                <p>
                    Upload before/after transformations.
                </p>

            </a>

            <a href="#"
               class="theme-card theme-card-padding theme-radius theme-shadow hover:scale-105 transition duration-300">

                <h2 class="text-2xl font-bold mb-2">
                    Blog Management
                </h2>

                <p class="theme-text-neutral">
                    Create SEO fitness blogs.
                </p>

            </a>

        </div>

    </div>

</div>

@endsection
