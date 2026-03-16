<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
      public function index()
    {
        // Получаем последние 10 товара
        $latestProducts = Product::with('categories')
            ->where('active', true)
            ->latest()
            ->take(10)
            ->get();

        // Получаем популярные категории (первые 4)
        $popularCategories = Category::where('active', true)
            ->withCount('products')
            ->having('products_count', '>', 0)
            ->take(4)
            ->get();

        // Если нет категорий с товарами, покажем первые 4 категории
        if ($popularCategories->isEmpty()) {
            $popularCategories = Category::where('active', true)
                ->take(4)
                ->get();
        }

        return view('catalog', compact('latestProducts', 'popularCategories'));
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
