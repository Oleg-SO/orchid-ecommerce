<?php

namespace App\Orchid\Layouts\Product;

use App\Models\Product;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ProductListLayout extends Table
{
    protected $target = 'products';

    protected function columns(): array
    {
        return [
            // ЕДИНСТВЕННАЯ колонка с чекбоксами
            TD::make('checkbox', ' ')
                ->render(function (Product $product) {
                    return '<div style="display: flex; align-items: center; justify-content: center;">
                        <input type="checkbox" name="selected_products[]" value="' . $product->id . '" class="product-checkbox" style="width: 16px; height: 16px;">
                    </div>';
                })
                ->width('50px'),

            TD::make('image', 'Фото')
                ->width('100px')
                ->render(function (Product $product) {
                    $attachment = $product->attachment()->first();
                    if ($attachment) {
                        return '<img src="' . $attachment->url() . '" width="60" height="60" style="object-fit: cover; border-radius: 4px;">';
                    }
                    return '<div style="width:60px; height:60px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; border-radius:4px;">
                        <span style="font-size:20px; color:#999;">📷</span>
                    </div>';
                }),

            TD::make('name', 'Название')
                ->sort()
                ->filter(),

            TD::make('price', 'Цена')
                ->render(fn (Product $product) => number_format($product->price, 0, '.', ' ') . ' ₽')
                ->sort(),

            TD::make('quantity', 'Количество')
                ->sort(),

            TD::make('active', 'Активен')
                ->render(fn (Product $product) => $product->active ? '✅' : '❌'),

            TD::make('created_at', 'Создано')
                ->sort(),

            TD::make(__('Действия'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Product $product) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        Link::make('Редактировать')
                            ->route('platform.products.edit', $product->id)
                            ->icon('bs.pencil'),

                        Button::make('Удалить')
                            ->icon('bs.trash3')
                            ->confirm('Вы уверены?')
                            ->method('remove')
                            ->parameters(['id' => $product->id]),
                    ])
                ),
        ];
    }
}