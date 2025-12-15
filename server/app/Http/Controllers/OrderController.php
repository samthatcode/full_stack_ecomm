<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function getCustomerOrders()
    {
        $orders = Order::with('user')->latest()->paginate(20);

        return response()->json([
            'data' => $orders,
            'message' => 'Orders fetched successfully'
        ]);
    }

    public function getSingleOrder($order_id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($order_id);

        return response()->json([
            'data' => $order,
            'message' => 'Order fetched successfully'
        ]);
    }
    public function updateOrderStatus(Request $request, $order_id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order = Order::find($order_id);
        $order->status = $request->status;
        $order->save();

        return response()->json([
            'message' => 'Order status updated successfully'
        ], 200);
    }
}
