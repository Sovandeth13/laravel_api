<?php
namespace App\Policies;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartPolicy
{
    use HandlesAuthorization;

    // Determine whether the user can view any carts
    public function viewAny(User $user)
    {
        // Maybe only allow users to view their own carts, so true here is fine
        return true;
    }

    // Determine whether the user can view a specific cart
    public function view(User $user, Cart $cart)
    {
        return $user->id === $cart->user_id;
    }

    // Determine whether the user can create carts
    public function create(User $user)
    {
        // Usually any authenticated user can create a cart
        return true;
    }

    // Determine whether the user can update the cart
    public function update(User $user, Cart $cart)
    {
        return $user->id === $cart->user_id;
    }

    // Determine whether the user can delete the cart
    public function delete(User $user, Cart $cart)
    {
        return $user->id === $cart->user_id;
    }

    // Optional: restore method, if using soft deletes
    public function restore(User $user, Cart $cart)
    {
        return $user->id === $cart->user_id;
    }

    // Optional: force delete method
    public function forceDelete(User $user, Cart $cart)
    {
        return $user->id === $cart->user_id;
    }
}
