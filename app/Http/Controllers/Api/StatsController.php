<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get current year
        $currentYear = date('Y');
        
        // ========== MONTHLY REVENUE ==========
        $monthlyRevenue = [];
        for ($month = 1; $month <= 12; $month++) {
            $revenue = Order::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->where('status', 'delivered')
                ->sum('total');
            
            $monthlyRevenue[] = [
                'month' => date('M', mktime(0, 0, 0, $month, 1)),
                'revenue' => (float) $revenue,
            ];
        }
        
        // ========== TOTAL STATS ==========
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'delivered')->sum('total');
        $totalCustomers = User::where('role', 'customer')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'delivered')->count();
        
        // ========== TODAY'S STATS ==========
        $today = date('Y-m-d');
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $todayRevenue = Order::whereDate('created_at', $today)->where('status', 'delivered')->sum('total');
        $todayCustomers = User::whereDate('created_at', $today)->where('role', 'customer')->count();
        $todayPending = Order::whereDate('created_at', $today)->where('status', 'pending')->count();
        
        // ========== WEEK STATS ==========
        $weekOrders = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $weekRevenue = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', 'delivered')
            ->sum('total');
        $weekCustomers = User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('role', 'customer')
            ->count();
        $weekPending = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', 'pending')
            ->count();
        
        // ========== TOP PRODUCTS ==========
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as sales'),
                DB::raw('SUM(order_items.subtotal) as revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('sales', 'desc')
            ->limit(5)
            ->get()
            ->map(function($product) {
                // Calculate growth (simple mock for now)
                $growth = '+' . rand(5, 25) . '%';
                return [
                    'name' => $product->name,
                    'sales' => (int) $product->sales,
                    'revenue' => (float) $product->revenue,
                    'growth' => $growth,
                ];
            });
        
        // ========== RECENT ACTIVITIES ==========
        $recentActivities = [];
        
        // Get recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();
        
        foreach ($recentOrders as $order) {
            $recentActivities[] = [
                'action' => 'New order created',
                'customer' => $order->user ? $order->user->name : 'Guest',
                'amount' => '₱' . number_format($order->total, 2),
                'time' => $order->created_at->diffForHumans(),
            ];
        }
        
        // Get recent completed orders
        $completedOrdersList = Order::where('status', 'delivered')
            ->latest()
            ->take(3)
            ->get();
        
        foreach ($completedOrdersList as $order) {
            $recentActivities[] = [
                'action' => 'Order completed',
                'customer' => $order->user ? $order->user->name : 'Guest',
                'amount' => '₱' . number_format($order->total, 2),
                'time' => $order->updated_at->diffForHumans(),
            ];
        }
        
        // Get recent registered customers
        $newCustomers = User::where('role', 'customer')
            ->latest()
            ->take(3)
            ->get();
        
        foreach ($newCustomers as $customer) {
            $recentActivities[] = [
                'action' => 'New customer registered',
                'customer' => $customer->name,
                'amount' => '',
                'time' => $customer->created_at->diffForHumans(),
            ];
        }
        
        // Sort activities by time (most recent first)
        // For simplicity, we'll just return the first 10
        $recentActivities = array_slice($recentActivities, 0, 10);
        
        // ========== RESPONSE DATA ==========
        $data = [
            // Current period stats (for "This Month" view)
            'total_orders' => $totalOrders,
            'total_revenue' => (float) $totalRevenue,
            'total_customers' => $totalCustomers,
            'pending_orders' => $pendingOrders,
            'completed_orders' => $completedOrders,
            
            // Today's stats
            'today_orders' => $todayOrders,
            'today_revenue' => (float) $todayRevenue,
            'today_customers' => $todayCustomers,
            'today_pending' => $todayPending,
            
            // Week stats
            'week_orders' => $weekOrders,
            'week_revenue' => (float) $weekRevenue,
            'week_customers' => $weekCustomers,
            'week_pending' => $weekPending,
            
            // Monthly revenue chart data
            'monthly_revenue' => $monthlyRevenue,
            
            // Top products
            'top_products' => $topProducts,
            
            // Recent activities
            'recent_activities' => $recentActivities,
        ];
        
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    
    // Additional method for period-specific stats
    public function getStatsByPeriod(Request $request, $period)
    {
        $startDate = null;
        $endDate = now();
        
        switch ($period) {
            case 'today':
                $startDate = now()->startOfDay();
                break;
            case 'week':
                $startDate = now()->startOfWeek();
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                break;
            case 'year':
                $startDate = now()->startOfYear();
                break;
            default:
                $startDate = now()->startOfMonth();
        }
        
        $orders = Order::whereBetween('created_at', [$startDate, $endDate]);
        $customers = User::whereBetween('created_at', [$startDate, $endDate])->where('role', 'customer');
        
        $data = [
            'period' => $period,
            'total_orders' => $orders->count(),
            'total_revenue' => (float) $orders->where('status', 'delivered')->sum('total'),
            'total_customers' => $customers->count(),
            'pending_orders' => $orders->where('status', 'pending')->count(),
            'completed_orders' => $orders->where('status', 'delivered')->count(),
        ];
        
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}