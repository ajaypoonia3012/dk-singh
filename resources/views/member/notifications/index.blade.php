@extends('layouts.app')

@section('content')

<section class="min-h-screen bg-[#f6f3eb] py-20">

<div class="max-w-7xl mx-auto px-6">

    <div class="mb-16">

        <p class="uppercase tracking-[3px] text-yellow-500 font-bold mb-4">
            Notifications
        </p>

        <h1 class="text-5xl font-black">
            Notification Center
        </h1>

        <p class="text-gray-500 mt-3">
            {{ $unreadCount }} unread notifications
        </p>

    </div>

    <div class="space-y-6">

        @forelse($notifications as $notification)

            <div class="bg-white rounded-3xl p-8 shadow-xl">

                <div class="flex justify-between items-start">

                    <div>

                        <h2 class="text-2xl font-black">

                            {{ $notification->title }}

                        </h2>

                        <p class="mt-4 text-gray-600">

                            {{ $notification->message }}

                        </p>

                        <p class="text-sm text-gray-400 mt-4">

                            {{ $notification->created_at->format('d M Y H:i') }}

                        </p>

                    </div>

                    @if(!$notification->is_read)

                        <form
                            method="POST"
                            action="{{ route('member.notifications.read', $notification) }}"
                        >
                            @csrf

                            <button
                                class="px-4 py-2 rounded-xl bg-black text-white"
                            >
                                Mark Read
                            </button>

                        </form>

                    @endif

                </div>

            </div>

        @empty

            <div class="bg-white rounded-3xl p-10 shadow-xl">

                No notifications available.

            </div>

        @endforelse

    </div>

</div>

</section>

@endsection