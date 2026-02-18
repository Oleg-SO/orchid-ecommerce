<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Главная страница каталога со всеми товарами
     */
    public function index()
    {
        $products = Product::with('categories')
            ->where('active', true)
            ->paginate(12);
            
        $categories = Category::where('active', true)
            ->defaultOrder()
            ->withDepth()
            ->get();
            
        return view('catalog.index', compact('products', 'categories'));
    }
    
    /**
     * Страница конкретной категории
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();
            
        $products = $category->products()
            ->where('active', true)
            ->paginate(12);
            
        $categories = Category::where('active', true)
            ->defaultOrder()
            ->withDepth()
            ->get();
            
        return view('catalog.category', compact('category', 'products', 'categories'));
    }
    
    /**
     * Страница конкретного товара
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('active', true)
            ->with('categories')
            ->firstOrFail();
            
        // Похожие товары из тех же категорий
        $relatedProducts = Product::whereHas('categories', function($query) use ($product) {
            $query->whereIn('categories.id', $product->categories->pluck('id'));
        })
        ->where('id', '!=', $product->id)
        ->where('active', true)
        ->limit(4)
        ->get();
            
        return view('catalog.show', compact('product', 'relatedProducts'));
    }
}