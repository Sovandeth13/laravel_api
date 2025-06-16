<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // List all products (for admin)
    public function index()
    {
        $products = Product::with('category')->paginate(10);
    return response()->json([
        'status' => true,
        'data' => $products,
    ]);
    }

    // Store a new product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            // add other fields
        ]);

        $product = Product::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Product created!',
            'data' => $product
        ]);
    }

    // Show, update, delete - you can add similar methods here
}
