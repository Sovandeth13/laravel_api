<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
          $cartItems = Cart::with('product')->where('user_id', $request->user()->id)->get();

    $items = $cartItems->map(function ($item) {
        return [
            'id' => $item->id,
            'quantity' => $item->quantity,
            'product' => [
                'id' => $item->product->id,
                'name' => $item->product->name,
                'image_url' => $item->product->image_url, // Make sure this is a full URL!
                'price' => $item->product->price,
                'description' => $item->product->description,
            ],
        ];
    });

    return response()->json(['items' => $items]);
    }

    public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $userId = $request->user()->id;

    // Check if product is already in cart
    $cartItem = Cart::where('user_id', $userId)
                    ->where('product_id', $request->product_id)
                    ->first();

    if ($cartItem) {
        // If exists, update quantity
        $cartItem->quantity += $request->quantity;
        $cartItem->save();
    } else {
        // If not, create new
        $cartItem = Cart::create([
            'user_id' => $userId,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);
    }

    return response()->json($cartItem, 201);
}


    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cartItem = Cart::findOrFail($id);
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json($cartItem);
    }

    public function destroy($id)
    {
        Cart::destroy($id);
        return response()->json(['message' => 'Cart item removed successfully']);
    }
}
