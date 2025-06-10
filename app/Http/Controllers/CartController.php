<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    // View cart items for the authenticated user

    /**
 * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Cart[]
 */
    public function index()
    {
       $user = Auth::user();
    $cartItems = $user->carts()->with('product')->get();

    return response()->json($cartItems);
    }

    // Add item to cart
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1'
        ]);

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // Increment quantity
            $cartItem->quantity += $request->quantity ?? 1;
            $cartItem->save();
        } else {
            // New item in cart
            $cartItem = Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity ?? 1
            ]);
        }

        return response()->json($cartItem->load('product'), 201);
    }

    // Update quantity of a specific cart item
    public function update(Request $request, Cart $cart)
    {
        $this->authorize('update', $cart); // Optional, if using policies

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart->update(['quantity' => $request->quantity]);

        return response()->json($cart->load('product'));
    }

    // Delete item from cart
    public function destroy(Cart $cart)
    {
        $this->authorize('delete', $cart); // Optional, if using policies

        $cart->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }
}
