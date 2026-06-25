@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-100 p-8">

    <div class="max-w-7xl mx-auto">

        <!-- HEADER -->

        <div class="flex items-center justify-between mb-10">

            <div>
                <h1 class="text-4xl font-black text-black">
                    {{ $setting->site_name }} Admin
                </h1>

                <p class="text-gray-500 mt-2">
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

        <div class="grid md:grid-cols-4 gap-6 mb-10">

            <div class="bg-white rounded-3xl p-6 shadow-sm">
                <h3 class="text-gray-500 text-sm mb-3">Programs</h3>
                <div class="text-4xl font-black text-yellow-500">{{ $totalPrograms }}</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm">
                <h3 class="text-gray-500 text-sm mb-3">Clients</h3>
                <div class="text-4xl font-black text-black">{{ $totalClients }}</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm">
                <h3 class="text-gray-500 text-sm mb-3">Transformations</h3>
                <div class="text-4xl font-black text-black">{{ $totalTransformations }}</div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm">
                <h3 class="text-gray-500 text-sm mb-3">Orders</h3>
                <div class="text-4xl font-black text-black">{{ $totalOrders }}</div>
            </div>

        </div>

        <!-- QUICK ACTIONS -->

        <div class="grid md:grid-cols-3 gap-6">

            <a href="#"
               class="bg-black text-white p-8 rounded-3xl hover:scale-105 transition duration-300">

                <h2 class="text-2xl font-bold mb-2">
                    Manage Programs
                </h2>

                <p class="text-gray-300">
                    Add and update coaching programs.
                </p>

            </a>

            <a href="#"
               class="bg-yellow-500 text-black p-8 rounded-3xl hover:scale-105 transition duration-300">

                <h2 class="text-2xl font-bold mb-2">
                    Client Transformations
                </h2>

                <p>
                    Upload before/after transformations.
                </p>

            </a>

            <a href="#"
               class="bg-white p-8 rounded-3xl shadow-sm hover:scale-105 transition duration-300">

                <h2 class="text-2xl font-bold mb-2">
                    Blog Management
                </h2>

                <p class="text-gray-500">
                    Create SEO fitness blogs.
                </p>

            </a>

        </div>

    </div>

</div>

@endsection