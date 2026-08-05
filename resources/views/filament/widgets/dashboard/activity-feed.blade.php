<x-filament-widgets::widget>

<x-filament::section>

<h2 class="text-xl font-bold mb-6">
🔔 Recent Activity
</h2>

<div class="space-y-3">

@foreach($orders as $order)

<div class="flex justify-between border-b pb-2">

<div>
📦
<strong>New Order</strong>

{{ $order->order_number }}

</div>

<div class="text-gray-500">

{{ $order->created_at->diffForHumans() }}

</div>

</div>

@endforeach

@foreach($users as $user)

<div class="flex justify-between border-b pb-2">

<div>
👤
<strong>New User</strong>

{{ $user->name }}

</div>

<div class="text-gray-500">

{{ $user->created_at->diffForHumans() }}

</div>

</div>

@endforeach

@foreach($memberships as $membership)

<div class="flex justify-between border-b pb-2">

<div>
💳
<strong>Membership</strong>

{{ optional($membership->user)->name }}

</div>

<div class="text-gray-500">

{{ $membership->created_at->diffForHumans() }}

</div>

</div>

@endforeach

</div>

</x-filament::section>

</x-filament-widgets::widget>