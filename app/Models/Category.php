<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Category extends Model
{
    use NodeTrait, AsSource, Filterable, Attachable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'active'
    ];

    protected $allowedFilters = [
        'name',
        'active'
    ];

    protected $allowedSorts = [
        'name',
        'created_at'
    ];

    protected $casts = [
        'active' => 'boolean',
        '_lft' => 'integer',
        '_rgt' => 'integer',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
