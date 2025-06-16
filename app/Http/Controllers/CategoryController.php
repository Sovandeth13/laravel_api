<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // List all categories
  public function index()
{
    $categories = Category::all()->map(function($cat) {
        $firstProduct = $cat->products()->first();
        if ($firstProduct && $firstProduct->image_url) {
            // If starts with http(s), use as is; else, prepend asset()
            $img = $firstProduct->image_url;
            if (!preg_match('/^https?:\/\//', $img)) {
                $img = asset('storage/' . ltrim($img, '/'));
            }
            $cat->preview_image = $img;
        } else {
            $cat->preview_image = 'https://placehold.co/150x150?text=No+Image';
        }
        return $cat;
    });
    return response()->json($categories);
}

    // Create new category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create($request->all());
        return response()->json($category, 201);
    }

    // Show category details
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    // Update category
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());
        return response()->json($category);
    }

    // Delete category
    public function destroy($id)
    {
        Category::destroy($id);
        return response()->json(['message' => 'Category deleted successfully']);
    }
    public function products($id)
{
    $category = Category::findOrFail($id);
    // Assuming you have a relationship defined in Category model
    $products = $category->products; // Or paginate: $category->products()->paginate(10)
    return response()->json($products);
}
}
