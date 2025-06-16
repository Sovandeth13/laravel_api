<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->middleware('admin');
        $this->productService = $productService;
    }

    public function index()
    {
       $products = Product::with('category')->get();

    return ProductResource::collection($products);
    }

    public function store(CreateProductRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('product_image')) {
            $data['image'] = $request->file('product_image')->store('products', 'public');
        }
        $product = $this->productService->createProduct($data);
        return new ProductResource($product);
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
    return new ProductResource($product);
    }

    public function update(CreateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('product_image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('product_image')->store('products', 'public');
        }
        $updatedProduct = $this->productService->updateProduct($product, $data);
        return new ProductResource($updatedProduct);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $this->productService->deleteProduct($product);
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
