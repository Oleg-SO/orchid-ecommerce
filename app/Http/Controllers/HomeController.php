<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Получаем последние 4 товара
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
        
        return view('home', compact('latestProducts', 'popularCategories'));
    }
}