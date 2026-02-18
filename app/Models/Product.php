<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Product extends Model
{
    use AsSource, Filterable, Attachable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'old_price',
        'quantity',
        'sku',
        'active'
    ];

    protected $allowedFilters = [
        'name',
        'active',
        'price'
    ];

    protected $allowedSorts = [
        'name',
        'price',
        'created_at'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}