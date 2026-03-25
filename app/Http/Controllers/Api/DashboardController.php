<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Fetch real data from your tables later
        $data = [
            'user' => $user,
            'stats' => [
                'deliveries' => 24,
                'rating' => 4.8,
                'on_time' => 98,
            ],
            'recent_orders' => [
                ['id' => 1001, 'total' => 45000, 'status' => 'Delivered'],
                ['id' => 1002, 'total' => 38000, 'status' => 'Shipped'],
            ],
        ];

        return response()->json($data);
    }
}