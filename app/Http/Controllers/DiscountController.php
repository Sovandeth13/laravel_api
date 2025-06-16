<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    // List all discounts
    public function index()
    {
         return Discount::all();
    }

    // Create discount
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:discounts',
            'percentage' => 'required|numeric|min:0|max:100',
            'expires_at' => 'nullable|date',
        ]);

        $discount = Discount::create($request->all());
        return response()->json($discount, 201);
    }

    // Show discount
    public function show($id)
    {
        $discount = Discount::findOrFail($id);
        return response()->json($discount);
    }

    // Update discount
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'sometimes|required|string|max:255|unique:discounts,code,' . $id,
            'percentage' => 'sometimes|required|numeric|min:0|max:100',
            'expires_at' => 'nullable|date',
        ]);

        $discount = Discount::findOrFail($id);
        $discount->update($request->all());
        return response()->json($discount);
    }

    // Delete discount
    public function destroy($id)
    {
        Discount::destroy($id);
        return response()->json(['message' => 'Discount deleted successfully']);
    }
}
