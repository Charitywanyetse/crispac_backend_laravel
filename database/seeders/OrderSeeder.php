<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('email', 'customer@test.com')->first();
        $products = Product::all();
        
        if (!$user || $products->isEmpty()) {
            return;
        }
        
        // Create sample orders
        $orders = [
            [
                'order_number' => 'ORD-1001',
                'subtotal' => 5000,
                'tax' => 900,
                'shipping_cost' => 5000,
                'total' => 10900,
                'status' => 'delivered',
                'payment_status' => 'paid',
                'payment_method' => 'credit_card',
                'shipping_address' => '123 Main St, Kampala',
                'billing_address' => '123 Main St, Kampala',
            ],
            [
                'order_number' => 'ORD-1002',
                'subtotal' => 1800,
                'tax' => 324,
                'shipping_cost' => 5000,
                'total' => 7124,
                'status' => 'shipped',
                'payment_status' => 'paid',
                'payment_method' => 'mobile_money',
                'shipping_address' => '456 Kampala Rd',
                'billing_address' => '456 Kampala Rd',
            ],
            [
                'order_number' => 'ORD-1003',
                'subtotal' => 3500,
                'tax' => 630,
                'shipping_cost' => 5000,
                'total' => 9130,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'cash',
                'shipping_address' => '789 Jinja Road',
                'billing_address' => '789 Jinja Road',
            ],
        ];
        
        foreach ($orders as $orderData) {
            $order = Order::create(array_merge($orderData, ['user_id' => $user->id]));
            
            // Add order items
            $product = $products->random();
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => rand(1, 3),
                'unit_price' => $product->price,
                'subtotal' => $product->price * rand(1, 3),
            ]);
        }
    }
}