<x-filament-widgets::widget>

<x-filament::section>

<h2 class="text-xl font-bold mb-6">🔔 Recent Activity</h2>

<div class="space-y-2">

    @foreach($orders as $order)
    <div class="flex justify-between items-center border-b pb-2">
        <div>
            📦 <strong>New Order</strong>
            {{ $order->order_number }}
        </div>
        <div class="text-gray-500 text-sm">
            {{ $order->created_at->diffForHumans() }}
        </div>
    </div>
    @endforeach

    @foreach($articles as $article)
    <div class="flex justify-between items-center border-b pb-2">
        <div>
            📝 <strong>{{ $article->status ? 'Published' : 'Draft Saved' }}</strong>
            <a href="/admin/blog-posts/{{ $article->id }}/edit"
               class="text-primary-600 hover:underline ml-1">
                {{ Str::limit($article->title, 50) }}
            </a>
        </div>
        <div class="text-gray-500 text-sm">
            {{ $article->created_at->diffForHumans() }}
        </div>
    </div>
    @endforeach

    @foreach($users as $user)
    <div class="flex justify-between items-center border-b pb-2">
        <div>
            👤 <strong>New User</strong>
            {{ $user->name }}
        </div>
        <div class="text-gray-500 text-sm">
            {{ $user->created_at->diffForHumans() }}
        </div>
    </div>
    @endforeach

    @foreach($memberships as $membership)
    <div class="flex justify-between items-center border-b pb-2">
        <div>
            💳 <strong>Membership</strong>
            {{ optional($membership->user)->name }}
        </div>
        <div class="text-gray-500 text-sm">
            {{ $membership->created_at->diffForHumans() }}
        </div>
    </div>
    @endforeach

</div>

</x-filament::section>

</x-filament-widgets::widget>