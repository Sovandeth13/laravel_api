<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Cart;

class OrderController extends Controller
{
    // Place a new order (called by frontend after PayPal payment)
    public function store(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'cartItems' => 'required|array',
            'cartItems.*.id' => 'required|integer',
            'cartItems.*.quantity' => 'required|integer|min:1',
            'cartItems.*.price' => 'required|numeric|min:0',
            'paymentDetails.id' => 'required|string',
            'paymentDetails.payerEmail' => 'nullable|string|email',
        ]);

        $totalPrice = collect($data['cartItems'])->sum(function($i) {
            return $i['quantity'] * $i['price'];
        });
        $totalQuantity = collect($data['cartItems'])->sum('quantity');

        // Create order
        $order = Order::create([
            'user_id'        => $user->id,
            'total_price'    => $totalPrice,
            'total_amount'   => $totalQuantity,
            'status'         => 'processing',
            'payment_method' => 'paypal',
            'payment_status' => 'paid',
            'payment_id'     => $data['paymentDetails']['id'],
        ]);

        // Create order items and decrease product stock
        foreach ($data['cartItems'] as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['id'],
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
            ]);
            $product = Product::find($item['id']);
            if ($product) {
                $product->stock -= $item['quantity'];
                if ($product->stock < 0) $product->stock = 0;
                $product->save();
            }
        }

        // Remove purchased products from user's cart
        $orderedProductIds = collect($data['cartItems'])->pluck('id')->all();
        Cart::where('user_id', $user->id)
            ->whereIn('product_id', $orderedProductIds)
            ->delete();

        return response()->json([
            'message' => 'Order placed and cart cleared!',
            'order_id' => $order->id
        ], 201);
    }

    // Get all orders for this user (with items and product details)
public function myOrders(Request $request)
{
    try {
        $orders = Order::with('orderItems.product')
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json($orders);
    } catch (\Exception $e) {
        \Log::error('Error fetching orders: '.$e->getMessage());
        return response()->json(['message' => 'Failed to fetch orders'], 500);
    }
}



    // Get a single order (with items and product details)
    public function show($id)
    {
        $user = auth()->user();
        $order = Order::where('user_id', $user->id)
            ->with('orderItems.product')
            ->findOrFail($id);
        return response()->json($order);
    }
}
