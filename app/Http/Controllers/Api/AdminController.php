<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $stats = [
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_products' => Product::count(),
            'active_orders' => Order::whereNotIn('status', ['completed', 'cancelled'])->count(),
        ];

        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'customer' => $order->user->name ?? 'Guest',
                    'total' => $order->total,
                    'status' => $order->status,
                    'date' => $order->created_at->format('Y-m-d'),
                ];
            });

        $topProducts = Product::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get()
            ->map(function($product) {
                return [
                    'name' => $product->name,
                    'orders' => $product->orders_count,
                    'revenue' => $product->orders->sum('total') ?? 0,
                ];
            });

        return response()->json([
            'stats' => $stats,
            'recent_orders' => $recentOrders,
            'top_products' => $topProducts,
        ]);
    }

    public function customerDashboard(Request $request)
    {
        $user = $request->user();
        
        $stats = [
            'deliveries' => Order::where('user_id', $user->id)->count(),
            'rating' => 4.8,
            'on_time' => 98,
        ];

        $recentOrders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'total' => $order->total,
                    'status' => $order->status,
                    'garment' => 'Garment',
                    'customer' => $order->user->name,
                ];
            });

        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'stats' => $stats,
            'recent_orders' => $recentOrders,
        ]);
    }

    public function products(Request $request)
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function orders(Request $request)
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
        return response()->json($orders);
    }

    public function customers(Request $request)
    {
        $customers = User::where('role', 'customer')
            ->withCount('orders')
            ->get()
            ->map(function($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'orders' => $customer->orders_count,
                    'total_spent' => $customer->orders->sum('total'),
                    'joined' => $customer->created_at->format('Y-m-d'),
                    'status' => 'Active',
                ];
            });
        return response()->json($customers);
    }

    public function inventory(Request $request)
    {
        return response()->json([
            ['id' => 1, 'name' => 'Cotton Fabric', 'quantity' => 250, 'unit' => 'yards', 'category' => 'Fabrics'],
            ['id' => 2, 'name' => 'Silk Fabric', 'quantity' => 80, 'unit' => 'yards', 'category' => 'Fabrics'],
            ['id' => 3, 'name' => 'Polyester Thread', 'quantity' => 45, 'unit' => 'spools', 'category' => 'Threads'],
        ]);
    }

    public function production(Request $request)
    {
        return response()->json([]);
    }

    public function finance(Request $request)
    {
        return response()->json([
            'revenue' => 2450000,
            'expenses' => 890000,
            'profit' => 1560000,
        ]);
    }

    public function reports(Request $request)
    {
        return response()->json([
            'total_sales' => 2450000,
            'total_orders' => 28,
        ]);
    }
}