<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Показать страницу товара
     */
    public function show($slug)
    {
        // Загружаем товар со всеми связями
        $product = Product::with([
            'categories',
            'brand',
            'attachments',
            'reviews' => function($query) {
                $query->where('is_approved', true)->latest();
            }
        ])
        ->where('slug', $slug)
        ->where('active', true)
        ->firstOrFail();

        // Увеличиваем счетчик просмотров
        $product->increment('views');

        // Получаем похожие товары (из тех же категорий)
        $similarProducts = Product::with('categories', 'attachments')
            ->whereHas('categories', function($query) use ($product) {
                $query->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->where('id', '!=', $product->id)
            ->where('active', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Товары, которые покупают с этим товаром
        $alsoBought = Product::with('attachments')
            ->whereHas('orders', function($query) use ($product) {
                $query->whereIn('order_id', $product->orders()->pluck('order_id'));
            })
            ->where('id', '!=', $product->id)
            ->where('active', true)
            ->limit(6)
            ->get();

        // Если нет данных о совместных покупках, показываем популярные
        if ($alsoBought->isEmpty()) {
            $alsoBought = Product::with('attachments')
                ->where('active', true)
                ->orderBy('sales_count', 'desc')
                ->limit(6)
                ->get();
        }

        // Просмотренные товары (из сессии)
        $viewedProducts = $this->getViewedProducts($product->id);

        // Сохраняем текущий товар в просмотренные
        $this->addToViewed($product->id);

        // Статистика отзывов для рейтинга
        $reviewsStats = $this->calculateReviewsStats($product);

        return view('product', compact(
            'product',
            'similarProducts',
            'alsoBought',
            'viewedProducts',
            'reviewsStats'
        ));
    }

    /**
     * Быстрый просмотр товара (для модалок)
     */
    public function quickView($id)
    {
        $product = Product::with(['categories', 'attachments'])
            ->where('id', $id)
            ->where('active', true)
            ->firstOrFail();

        return view('partials.quick-view', compact('product'));
    }

    /**
     * Получить просмотренные товары из сессии
     */
    private function getViewedProducts($currentProductId)
    {
        $viewedIds = session()->get('viewed_products', []);

        // Убираем текущий товар из списка, чтобы не дублировать
        $viewedIds = array_diff($viewedIds, [$currentProductId]);

        // Берем последние 5 просмотренных
        $viewedIds = array_slice(array_reverse($viewedIds), 0, 5);

        if (empty($viewedIds)) {
            return collect([]);
        }

        return Product::with('attachments')
            ->whereIn('id', $viewedIds)
            ->where('active', true)
            ->orderByRaw('FIELD(id, ' . implode(',', $viewedIds) . ')')
            ->get();
    }

    /**
     * Добавить товар в просмотренные
     */
    private function addToViewed($productId)
    {
        $viewed = session()->get('viewed_products', []);

        // Добавляем текущий товар в начало
        array_unshift($viewed, $productId);

        // Убираем дубликаты
        $viewed = array_unique($viewed);

        // Оставляем только последние 20
        $viewed = array_slice($viewed, 0, 20);

        session()->put('viewed_products', $viewed);
    }

    /**
     * Рассчитать статистику отзывов
     */
    private function calculateReviewsStats($product)
    {
        $reviews = $product->reviews;
        $total = $reviews->count();

        if ($total === 0) {
            return [
                'average' => 0,
                'counts' => [],
                'percentages' => []
            ];
        }

        // Средний рейтинг
        $average = round($reviews->avg('rating'), 1);

        // Подсчет количества по звездам
        $counts = [];
        for ($i = 1; $i <= 5; $i++) {
            $counts[$i] = $reviews->where('rating', $i)->count();
        }

        // Проценты
        $percentages = [];
        foreach ($counts as $star => $count) {
            $percentages[$star] = $total > 0 ? round(($count / $total) * 100) : 0;
        }

        return [
            'average' => $average,
            'total' => $total,
            'counts' => $counts,
            'percentages' => $percentages
        ];
    }

    /**
     * Написать отзыв
     */
    public function storeReview(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|min:10|max:5000',
            'advantages' => 'nullable|string|max:1000',
            'disadvantages' => 'nullable|string|max:1000',
            'user_name' => 'required|string|max:255',
            'user_email' => 'nullable|email|max:255',
        ]);

        $product = Product::findOrFail($productId);

        $review = $product->reviews()->create([
            'rating' => $request->rating,
            'title' => $request->title,
            'content' => $request->content,
            'advantages' => $request->advantages,
            'disadvantages' => $request->disadvantages,
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'is_approved' => false, // На модерацию
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Спасибо за отзыв! Он появится после проверки модератором.',
            'review' => $review
        ]);
    }

    /**
     * Проверка наличия товара
     */
    public function checkAvailability($id)
    {
        $product = Product::findOrFail($id);

        return response()->json([
            'in_stock' => $product->quantity > 0,
            'quantity' => $product->quantity,
            'price' => $product->price,
            'old_price' => $product->old_price
        ]);
    }
}
