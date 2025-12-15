<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getProductCategory(Request $request)
    {
        return response()->json([
            'data' =>  Category::all(),
            'message' => 'Product category fetched successfully'
        ], 200);
    }

    public function createProductCategory(Request $request)
    {
        $category = Category::create($request->all());

        return response()->json([
            'data' =>  $category,
            'message' => 'Product category created successfully'
        ], 201);
    }

    public function getSingleCategory($category_id)
    {
        $category = Category::find($category_id);

        return response()->json([
            'data' =>  $category,
            'message' => 'Product category fetched successfully'
        ], 200);
    }

    public function updateCategory(Request $request, $category_id)
    {
        $category = Category::find($category_id);
        $category->update($request->all());

        return response()->json([
            'data' =>  $category,
            'message' => 'Product category updated successfully'
        ], 200);
    }

    public function deleteCategory($category_id)
    {
        $category = Category::find($category_id);
        $category->delete();

        return response()->json([
            'message' => 'Product category deleted successfully'
        ], 200);
    }
}
