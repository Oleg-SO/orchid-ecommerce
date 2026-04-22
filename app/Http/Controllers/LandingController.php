<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand; // ← добавляем модель Brand
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Получаем последние 10 товаров для блока "Новинки"
        $latestProducts = Product::with('categories')
            ->where('active', true)
            ->latest()
            ->take(8)
            ->get();

        // Получаем товары для слайдера "Хиты продаж"
        $hitProducts = Product::with('categories')
            ->where('active', true)
            ->where(function($query) {
                $query->where('is_hit', true)
                      ->orWhere('views', '>', 50)
                      ->orWhere('sales_count', '>', 10);
            })
            ->latest()
            ->take(10)
            ->get();

        // Если хитов нет, показываем любые товары (случайные)
        if ($hitProducts->isEmpty()) {
            $hitProducts = Product::with('categories')
                ->where('active', true)
                ->inRandomOrder()
                ->take(8)
                ->get();
        }

        // Получаем популярные категории
        $popularCategories = Category::where('active', true)
            ->withCount('products')
            ->having('products_count', '>', 0)
            ->take(4)
            ->get();

        if ($popularCategories->isEmpty()) {
            $popularCategories = Category::where('active', true)
                ->take(4)
                ->get();
        }

        // ===== ДОБАВЛЯЕМ БРЕНДЫ =====
        $brands = Brand::where('is_active', true)
            ->take(6) // покажем 6 брендов
            ->get();

        // Если брендов нет, создаем пустую коллекцию (чтобы не было ошибки)
        if ($brands->isEmpty()) {
            $brands = collect([]);
        }

        // Передаем все переменные в шаблон
        return view('landing', compact(
            'latestProducts',
            'hitProducts',
            'popularCategories',
            'brands' // ← добавляем brands
        ));
    }
}
