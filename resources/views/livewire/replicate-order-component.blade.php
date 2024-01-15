
{{-- resources/views/livewire/replicate-order-component.blade.php --}}

<div>
    {{-- <h1>Current Restaurant ID: {{ $currentRestaurant }}</h1>
    <h2>User Role: {{ $role }}</h2> --}}

    @if($latestOrder)
        <p>Latest Order Number for this Restaurant: {{ $latestOrder->order_number }}</p>

       {{-- Display the replication button with updated styles --}}
       <button wire:click="replicateOrder" style="background-color: #3490dc; color: #000; font-weight: bold; border-radius: 8px; padding: 10px 20px;">Replicate Last Order</button>
    @else
        <p>No orders found for this restaurant.</p>
    @endif
</div>






