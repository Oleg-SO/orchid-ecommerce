<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use AsSource, Filterable, Attachable, Searchable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'old_price',
        'quantity',
        'sku',
        'article',
        'warranty',
        'is_hit',
        'is_new',
        'sales_count',
        'views',
        'rating',
        'brand_id',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'is_hit' => 'boolean',
        'is_new' => 'boolean',
        'price' => 'float',
        'old_price' => 'float',
        'quantity' => 'integer',
        'views' => 'integer',
        'sales_count' => 'integer',
        'rating' => 'float',
        'warranty' => 'integer',
    ];

    protected $allowedFilters = [
        'name',
        'active',
        'price',
        'is_hit',
        'is_new',
        'brand_id',
    ];

    protected $allowedSorts = [
        'name',
        'price',
        'created_at',
        'views',
        'sales_count',
        'rating',
    ];

    /**
     * Связь с категориями (многие ко многим)
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Связь с брендом
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Связь с отзывами
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Только одобренные отзывы
     */
    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    /**
     * Связь с заказами (через корзину)
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class)
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    /**
     * Получить URL первого изображения
     */
    public function getImageUrlAttribute()
    {
        $attachment = $this->attachment()->first();
        if ($attachment) {
            return $attachment->url();
        }
        return null;
    }

    /**
     * Получить все изображения
     */
    public function getImagesAttribute()
    {
        return $this->attachment()->get();
    }

    /**
     * Проверка на наличие скидки
     */
    public function getHasDiscountAttribute()
    {
        return $this->old_price && $this->old_price > $this->price;
    }

    /**
     * Процент скидки
     */
    public function getDiscountPercentAttribute()
    {
        if (!$this->has_discount) {
            return 0;
        }
        return round((1 - $this->price / $this->old_price) * 100);
    }

    /**
     * Проверка наличия на складе
     */
    public function getInStockAttribute()
    {
        return $this->quantity > 0;
    }

    /**
     * Форматированная цена
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, '.', ' ') . ' ₽';
    }

    /**
     * Форматированная старая цена
     */
    public function getFormattedOldPriceAttribute()
    {
        if (!$this->old_price) {
            return null;
        }
        return number_format($this->old_price, 0, '.', ' ') . ' ₽';
    }

    /**
     * Статистика отзывов
     */
    public function getReviewsStatsAttribute()
    {
        $reviews = $this->approvedReviews;
        $total = $reviews->count();

        if ($total === 0) {
            return [
                'average' => 0,
                'total' => 0,
                'counts' => [],
                'percentages' => [],
            ];
        }

        $average = round($reviews->avg('rating'), 1);

        $counts = [];
        $percentages = [];

        for ($i = 1; $i <= 5; $i++) {
            $counts[$i] = $reviews->where('rating', $i)->count();
            $percentages[$i] = round(($counts[$i] / $total) * 100);
        }

        return [
            'average' => $average,
            'total' => $total,
            'counts' => $counts,
            'percentages' => $percentages,
        ];
    }

    /**
     * Увеличить счетчик просмотров
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Скоуп для активных товаров
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Скоуп для хитов продаж
     */
    public function scopeHits($query)
    {
        return $query->where('is_hit', true)->active();
    }

    /**
     * Скоуп для новинок
     */
    public function scopeNew($query)
    {
        return $query->where('is_new', true)->active();
    }

    /**
     * Скоуп для товаров со скидкой
     */
    public function scopeDiscounted($query)
    {
        return $query->whereNotNull('old_price')
            ->whereColumn('old_price', '>', 'price')
            ->active();
    }

    /**
     * Скоуп для товаров в наличии
     */
    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0)->active();
    }

    /**
     * Скоуп для поиска
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%")
              ->orWhere('article', 'like', "%{$search}%");
        });
    }

    /**
     * Скоуп для фильтра по цене
     */
    public function scopePriceRange($query, $min, $max)
    {
        if ($min !== null) {
            $query->where('price', '>=', $min);
        }
        if ($max !== null) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    /**
     * Скоуп для сортировки
     */
    public function scopeSort($query, $sortBy, $sortOrder = 'asc')
    {
        $allowedSorts = ['price', 'name', 'created_at', 'views', 'sales_count', 'rating'];

        if (in_array($sortBy, $allowedSorts)) {
            return $query->orderBy($sortBy, $sortOrder);
        }

        return $query;
    }

    public function specifications()
    {
        return $this->hasMany(Specification::class)->orderBy('sort_order');
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => strip_tags($this->description ?? ''),
            'short_description' => $this->short_description,
            'sku' => $this->sku,
            'article' => $this->article,
            'price' => $this->price,
            'active' => $this->active,
        ];
    }
}
