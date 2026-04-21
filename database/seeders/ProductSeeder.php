<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Cargo Service',
                'slug' => 'cargo-service',
                'description' => 'Fast and reliable cargo shipping service for businesses. Track your shipments in real-time.',
                'price' => 2500,
                'stock_quantity' => 100,
                'sku' => 'CRG-001',
                'category' => 'Shipping',
                'images' => json_encode(['https://via.placeholder.com/300x200?text=Cargo']),
                'attributes' => json_encode(['weight' => '1000kg', 'delivery_time' => '3-5 days']),
                'is_active' => true,
            ],
            [
                'name' => 'Express Delivery',
                'slug' => 'express-delivery',
                'description' => 'Same day express delivery service within city limits. Perfect for urgent packages.',
                'price' => 1800,
                'stock_quantity' => 50,
                'sku' => 'EXP-002',
                'category' => 'Delivery',
                'images' => json_encode(['https://via.placeholder.com/300x200?text=Express']),
                'attributes' => json_encode(['weight' => '50kg', 'delivery_time' => 'Same day']),
                'is_active' => true,
            ],
            [
                'name' => 'Warehouse Storage',
                'slug' => 'warehouse-storage',
                'description' => 'Secure warehouse storage solutions for businesses of all sizes.',
                'price' => 1500,
                'stock_quantity' => 200,
                'sku' => 'WRH-003',
                'category' => 'Storage',
                'images' => json_encode(['https://via.placeholder.com/300x200?text=Warehouse']),
                'attributes' => json_encode(['size' => '100sqm', 'duration' => 'Monthly']),
                'is_active' => true,
            ],
            [
                'name' => 'Freight Forwarding',
                'slug' => 'freight-forwarding',
                'description' => 'International freight forwarding services. Sea, air, and land shipping.',
                'price' => 3500,
                'stock_quantity' => 75,
                'sku' => 'FRT-004',
                'category' => 'International',
                'images' => json_encode(['https://via.placeholder.com/300x200?text=Freight']),
                'attributes' => json_encode(['weight' => '5000kg', 'delivery_time' => '10-15 days']),
                'is_active' => true,
            ],
            [
                'name' => 'Customs Clearance',
                'slug' => 'customs-clearance',
                'description' => 'Professional customs clearance and documentation services.',
                'price' => 2000,
                'stock_quantity' => 60,
                'sku' => 'CUS-005',
                'category' => 'Documentation',
                'images' => json_encode(['https://via.placeholder.com/300x200?text=Customs']),
                'attributes' => json_encode(['processing_time' => '2-3 days']),
                'is_active' => true,
            ],
        ];
        
        foreach ($products as $product) {
            Product::create($product);
        }
    }
}