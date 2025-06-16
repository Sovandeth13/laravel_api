<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    // List all orders (with items and product details)
    public function index()
    {
        $orders = Order::with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($orders);
    }

    // Get a single order (with items and product details)
    public function show($id)
    {
        $order = Order::with(['user', 'orderItems.product'])->findOrFail($id);
        return response()->json($order);
    }

    // Update order status (admin only)
    public function updateStatus(Request $request, $id)
    {
        $data = $request->validate([
            'status' => 'required|string',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $data['status'];
        $order->save();

        return response()->json(['message' => 'Status updated']);
    }
}
