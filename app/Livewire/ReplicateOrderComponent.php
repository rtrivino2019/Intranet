<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ReplicateOrderComponent extends Component
{
    public $selectedOrderId = null;
    public $latestOrder;

    public $currentRestaurant;
    public $restaurantOptions;
    public $desiredRestaurantId = '';

    public function mount()
    {
        $user = Auth::user();
        $this->restaurantOptions = $user->restaurants->pluck('name', 'id')->toArray();

        $url = request()->url();
        preg_match('/admin\/(\d+)\/replicate-order-page/', $url, $matches);
        $this->currentRestaurant = isset($matches[1]) ? $matches[1] : null;

        $this->latestOrder = $this->getLatestOrder();
    }

    public function getLatestOrder()
    {
        $currentRestaurantId = $this->currentRestaurant;

        return Order::whereHas('restaurants', function ($query) use ($currentRestaurantId) {
            $query->where('restaurant_id', $currentRestaurantId);
        })->latest()->first();
    }




    public function replicateOrder()
    {
        // Get the latest order details
        $latestOrder = $this->latestOrder;

        // Ensure there is a latest order
        if ($latestOrder) {
            // Replicate the order with a prefix 'W' for the order_number
            $replicatedOrder = $latestOrder->replicate();
            $replicatedOrder->order_number = 'D' . $latestOrder->order_number;
            $replicatedOrder->save();

            // Associate the replicated order with the current restaurant
            $replicatedOrder->restaurants()->attach($this->currentRestaurant);

            // Optionally, update $this->latestOrder to the replicated order
            $this->latestOrder = $this->getLatestOrder();

            // Replicate order items for the new order
            foreach ($latestOrder->orderItems as $originalOrderItem) {
                $replicatedOrderItem = $originalOrderItem->replicate();

                // Update the order_id for the replicated order item
                $replicatedOrderItem->order_id = $replicatedOrder->id;

                // Set product_amount to zero for the replicated order item
                $replicatedOrderItem->product_amount = 0;

                // Save the replicated order item
                $replicatedOrderItem->save();
            }

            // You can add any additional logic or notifications here
            session()->flash('message', 'Order replicated successfully!');
        }
    }


    public function render()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();

        return view('livewire.replicate-order-component', [
            'role' => $role,
            'currentRestaurant' => $this->currentRestaurant,
            'latestOrder' => $this->latestOrder,
        ]);
    }
}






