<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $total_revenue = Order::where('status', 'delivered')->sum('total');
        $total_orders = Order::count();
        $total_products = Product::count();
        $low_stock = Product::where('stock_quantity', '<=', 10)->count();
        $recent_orders = Order::with('user')->latest()->take(5)->get();

        return response()->json([
            'data' => [
                'total_revenue' => $total_revenue,
                'total_orders' => $total_orders,
                'total_products' => $total_products,
                'low_stock_items' => $low_stock,
                'recent_orders' => $recent_orders
            ],
            'message' => 'Dashboard data fetched'
        ]);
    }
}
