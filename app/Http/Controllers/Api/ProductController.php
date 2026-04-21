<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // Get all products (with filters)
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);
        
        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $products = $query->paginate($request->get('per_page', 20));
        
        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Products retrieved successfully'
        ]);
    }
    
    // Get single product
    public function show($id)
    {
        $product = Product::where('is_active', true)->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product retrieved successfully'
        ]);
    }
    
    // Get product by slug
    public function showBySlug($slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product retrieved successfully'
        ]);
    }
    
    // Get product categories
    public function categories()
    {
        $categories = Product::where('is_active', true)
            ->select('category')
            ->distinct()
            ->whereNotNull('category')
            ->pluck('category');
        
        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Categories retrieved successfully'
        ]);
    }
    
    // Admin: Create product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category' => 'nullable|string',
            'sku' => 'nullable|string|unique:products',
            'images' => 'nullable|array',
            'attributes' => 'nullable|array',
        ]);
        
        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'sku' => $request->sku ?? strtoupper(uniqid()),
            'category' => $request->category,
            'images' => $request->images,
            'attributes' => $request->attributes,
            'is_active' => $request->is_active ?? true,
        ]);
        
        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product created successfully'
        ], 201);
    }
    
    // Admin: Update product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'sometimes|string|max:255|unique:products,name,' . $product->id,
            'price' => 'sometimes|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
            'category' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);
        
        if ($request->has('name') && $request->name !== $product->name) {
            $product->slug = Str::slug($request->name);
        }
        
        $product->update($request->all());
        
        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product updated successfully'
        ]);
    }
    
    // Admin: Delete product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }
    
    // Check stock availability
    public function checkStock(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
        
        $availability = [];
        
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $available = $product->stock_quantity >= $item['quantity'];
            
            $availability[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'requested_quantity' => $item['quantity'],
                'available_quantity' => $product->stock_quantity,
                'available' => $available,
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => $availability,
            'message' => 'Stock checked successfully'
        ]);
    }
}