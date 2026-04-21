<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get real data from database
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        
        // Calculate revenue (assuming you have a 'total' column in orders)
        $totalRevenue = Order::sum('total') ?? 0;
        
        // Get recent orders with user relationship
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number ?? 'ORD-' . $order->id,
                    'customer' => $order->user ? $order->user->name : 'Guest',
                    'amount' => $order->total,
                    'status' => $order->status,
                    'date' => $order->created_at->format('Y-m-d'),
                ];
            });
        
        // Get top products
        $topProducts = Product::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get()
            ->map(function($product) {
                return [
                    'name' => $product->name,
                    'sales' => $product->orders_count,
                    'revenue' => $product->price * $product->orders_count,
                    'growth' => '+'.rand(5, 25).'%', // You can calculate real growth later
                ];
            });
        
        // Recent activities (you can expand this)
        $recentActivities = [
            ['action' => 'New order created', 'details' => 'Order #' . ($recentOrders->first()['id'] ?? 'N/A'), 'time' => 'Just now'],
            ['action' => 'Shipment delivered', 'details' => 'Package arrived', 'time' => '1 hour ago'],
            ['action' => 'Payment received', 'details' => 'UGX ' . number_format($totalRevenue), 'time' => '3 hours ago'],
        ];
        
        $data = [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'stats' => [
                'deliveries' => $totalOrders,
                'rating' => 4.8, // You can calculate this from reviews
                'on_time' => 98, // Calculate from delivery data
            ],
            'dashboard_stats' => [
                'total_orders' => $totalOrders,
                'total_products' => $totalProducts,
                'total_customers' => $totalCustomers,
                'total_revenue' => $totalRevenue,
                'pending_orders' => $pendingOrders,
            ],
            'recent_orders' => $recentOrders,
            'top_products' => $topProducts,
            'recent_activities' => $recentActivities,
        ];
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    
    // Customer specific dashboard
    public function customerDashboard(Request $request)
    {
        $user = $request->user();
        
        $myOrders = Order::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'total' => $order->total,
                    'status' => $order->status,
                    'date' => $order->created_at->format('Y-m-d'),
                ];
            });
        
        $stats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'total_spent' => Order::where('user_id', $user->id)->sum('total'),
            'pending_orders' => Order::where('user_id', $user->id)->where('status', 'pending')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'stats' => $stats,
                'recent_orders' => $myOrders,
            ]
        ]);
    }
}