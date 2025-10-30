<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Seller;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
{
    $query = Product::where('is_active', true)
        ->where('stock', '>', 0)
        ->with(['seller.user', 'category']);

    // Category filter
    if ($request->has('category') && $request->category != '') {
        $category = \App\Models\Category::where('slug', $request->category)->first();
        if ($category) {
            $query->where('category_id', $category->id);
        }
    }

    // Search functionality
    if ($request->has('search') && $request->search != '') {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Filter by seller
    if ($request->has('seller_id') && $request->seller_id != '') {
        $query->where('seller_id', $request->seller_id);
    }

    // Sort
    if ($request->has('sort')) {
        switch ($request->sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }
    } else {
        $query->latest();
    }

    $products = $query->paginate(12);
    $sellers = Seller::with('user')->get();
    $categories = \App\Models\Category::withCount('products')->get();

    return view('buyer.products.index', compact('products', 'sellers', 'categories'));
}

    public function show(Product $product)
    {
        if (!$product->is_active || $product->stock <= 0) {
            return redirect()->route('buyer.products.index')->with('error', 'Product not available');
        }

        $product->load('seller.user');
        $relatedProducts = Product::where('seller_id', $product->seller_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->take(4)
            ->get();

        return view('buyer.products.show', compact('product', 'relatedProducts'));
    }
}
