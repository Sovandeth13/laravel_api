<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discount; // assuming you have a Discount model

class DiscountController extends Controller
{
    // List all discounts (for admin)
    public function index()
    {
        $discounts = Discount::all();

        return response()->json([
            'status' => true,
            'data' => $discounts,
        ]);
    }

    // Store a new discount
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:discounts,code',
            'percentage' => 'required|numeric|min:0|max:100',
            'expires_at' => 'nullable|date',
        ]);

        $discount = Discount::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Discount created!',
            'data' => $discount,
        ]);
    }

    // Show a single discount
    public function show($id)
    {
        $discount = Discount::findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $discount,
        ]);
    }

    // Update a discount
    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|unique:discounts,code,' . $discount->id,
            'percentage' => 'required|numeric|min:0|max:100',
            'expires_at' => 'nullable|date',
        ]);

        $discount->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Discount updated!',
            'data' => $discount,
        ]);
    }

    // Delete a discount
    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();

        return response()->json([
            'status' => true,
            'message' => 'Discount deleted!',
        ]);
    }
}
