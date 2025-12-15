<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAllProducts(Request $request)
    {
        $products = Product::query()->with('category');

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
        $product = Product::create($request->all());

        return response()->json([
            'data' =>  $product,
            'message' => 'Product created successfully'
        ], 201);
    }

    public function updateProduct(Request $request, $product_id)
    {
        $product = Product::find($product_id);
        $product->update($request->all());

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
