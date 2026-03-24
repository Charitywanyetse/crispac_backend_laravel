<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->isAdmin()) {
            return $this->getAdminDashboard();
        } else {
            return $this->getCustomerDashboard($user->id);
        }
    }

    private function getAdminDashboard()
    {
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total');

        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->user->name,
                    'total' => $order->total,
                    'status' => $order->status,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => [
                    'total_orders' => $totalOrders,
                    'total_products' => $totalProducts,
                    'total_customers' => $totalCustomers,
                    'total_revenue' => number_format($totalRevenue, 2),
                ],
                'recent_orders' => $recentOrders,
                'popular_products' => [],
                'monthly_sales' => [],
            ]
        ]);
    }

    private function getCustomerDashboard($userId)
    {
        $totalOrders = Order::where('user_id', $userId)->count();
        $totalSpent = Order::where('user_id', $userId)->sum('total');

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => [
                    'total_orders' => $totalOrders,
                    'total_spent' => number_format($totalSpent, 2),
                ],
                'recent_orders' => [],
            ]
        ]);
    }

    public function salesReport(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => []
        ]);
    }

    public function inventoryStatus()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'low_stock' => [],
                'out_of_stock' => [],
                'total_products' => Product::count(),
            ]
        ]);
    }
}