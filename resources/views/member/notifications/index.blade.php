@extends('layouts.app')

@section('content')

<section class="min-h-screen theme-surface-muted theme-section">

<div class="theme-page-container">

    <div class="mb-16">

        <p class="uppercase tracking-[3px] theme-text-primary font-bold mb-4">
            Notifications
        </p>

        <h1 class="text-5xl font-black">
            Notification Center
        </h1>

        <p class="theme-text-neutral mt-3">
            {{ $unreadCount }} unread notifications
        </p>

    </div>

    <div class="theme-stack-lg">

        @forelse($notifications as $notification)

            <div class="theme-card theme-radius theme-card-padding theme-shadow">

                <div class="flex justify-between items-start">

                    <div>

                        <h2 class="text-2xl font-black">

                            {{ $notification->title }}

                        </h2>

                        <p class="mt-4 theme-text-neutral">

                            {{ $notification->message }}

                        </p>

                        <p class="text-sm theme-text-neutral mt-4">

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
                                class="px-4 py-2 theme-radius theme-surface-strong theme-text-on-strong"
                            >
                                Mark Read
                            </button>

                        </form>

                    @endif

                </div>

            </div>

        @empty

            <div class="theme-card theme-radius theme-card-padding-lg theme-shadow">

                No notifications available.

            </div>

        @endforelse

    </div>

</div>

</section>

@endsection
