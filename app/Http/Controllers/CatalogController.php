<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Laravel\Scout\EngineManager;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // ПОИСК через Scout
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');

            // Выполняем поиск через Scout
            $productIds = Product::search($searchTerm)
                ->where('active', true)
                ->get()
                ->pluck('id');

            // Строим запрос для найденных ID
            $query = Product::with('categories')
                ->whereIn('id', $productIds)
                ->where('active', true);
        } else {
            $query = Product::with('categories')->where('active', true);
        }

        // Фильтр по категориям
        if ($request->has('category')) {
            $categorySlugs = (array) $request->input('category');
            $query->whereHas('categories', function($q) use ($categorySlugs) {
                $q->whereIn('slug', $categorySlugs);
            });
        }

        // Фильтр по цене
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // Фильтр по наличию
        if ($request->has('in_stock')) {
            $query->where('quantity', '>', 0);
        }

        // Сортировка
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->latest('id');
        }

        $latestProducts = $query->paginate(12)->appends($request->all())->onEachSide(1);

        $categories = Category::where('active', true)
            ->withCount('products')
            ->defaultOrder()
            ->withDepth()
            ->get();

        return view('catalog', compact('latestProducts', 'categories'));
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
