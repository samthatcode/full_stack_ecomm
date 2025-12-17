<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAllProducts(Request $request)
    {
        $products = Product::query()->with('category');

        if ($request->filled('category_id')) {
            $products->where('category_id', $request->category_id);
        }

        if ($request->filled('sort_by')) {
            switch ($request->sort_by) {
                case 'price-low':
                    $products->orderBy('price', 'asc');
                    break;
                case 'price-high':
                    $products->orderBy('price', 'desc');
                    break;
                case 'rating':
                    $products->orderBy('rating', 'desc');
                    break;
                default: // featured
                    $products->where('is_featured', true)->orderBy('created_at', 'desc');
                    break;
            }
        }

        return response()->json([
            'data' =>  $products->paginate('40'),
            'message' => 'Products fetched successfully'
        ], 200);
    }

    public function getSingleProduct($product_id)
    {
        $product = Product::with('category')->find($product_id);

        return response()->json([
            'data' =>  $product,
            'message' => 'Product fetched successfully'
        ], 200);
    }

    public function createProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'weight' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $request['image'] = '/storage/' . $path;
        }
        $product = Product::create($data);

        return response()->json([
            'data' =>  $product,
            'message' => 'Product created successfully'
        ], 201);
    }

    public function updateProduct(Request $request, $product_id)
    {
        $product = Product::findOrFail($product_id);

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product_id,
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'weight' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $path = $request->file('image')->store('products', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $product->update($data);

        return response()->json([
            'data' =>  $product,
            'message' => 'Product updated successfully'
        ], 200);
    }

    public function deleteProduct($product_id)
    {
        $product = Product::find($product_id);
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ], 200);
    }
}
