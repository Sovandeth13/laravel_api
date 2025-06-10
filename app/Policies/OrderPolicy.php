<?php
namespace App\Policies;
use App\Models\User;
use App\Models\Order;

class OrderPolicy
{
    public function updateStatus(User $user, Order $order)
    {
        // Admin or order owner can update order status
        return $user->is_admin || $user->id === $order->user_id;
    }

    public function update(User $user, Order $order)
    {
        // Only the owner can update order details
        return $user->id === $order->user_id;
    }

    public function delete(User $user, Order $order)
    {
        // Admin or owner can delete the order
        return $user->is_admin || $user->id === $order->user_id;
    }

    public function view(User $user, Order $order)
    {
        // Admin or owner can view the order
        return $user->is_admin || $user->id === $order->user_id;
    }
}
