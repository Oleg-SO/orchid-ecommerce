<?php

namespace App\Orchid\Layouts\Product;

use App\Models\Category;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class ProductEditLayout extends Rows
{
    protected function fields(): array
    {
        return [
            Input::make('product.name')
                ->title('Название')
                ->required()
                ->placeholder('Введите название товара'),

            Input::make('product.slug')
                ->title('URL')
                ->required()
                ->placeholder('url-tovara'),

            Relation::make('product.categories.')
                ->title('Категории')
                ->fromModel(Category::class, 'name')
                ->multiple()
                ->applyScope('defaultOrder')
                ->help('Выберите категории для товара'),

            Input::make('product.price')
                ->title('Цена')
                ->type('number')
                ->step('0.01')
                ->required(),

            Input::make('product.old_price')
                ->title('Старая цена')
                ->type('number')
                ->step('0.01'),

            Input::make('product.quantity')
                ->title('Количество на складе')
                ->type('number')
                ->value(0),

            Input::make('product.sku')
                ->title('Артикул (SKU)')
                ->placeholder('ART-001'),

            Quill::make('product.description')
                ->title('Описание')
                ->placeholder('Полное описание товара'),

            Upload::make('product.attachment')
                ->title('Изображения')
                ->subtitle('Загрузите фотографии товара')
                ->maxFiles(5)
                ->acceptedFiles('image/*')
                ->groups('products')
                ->help('Можно загрузить до 5 изображений в форматах: jpg, png, webp'),

            CheckBox::make('product.active')
                ->title('Активен')
                ->placeholder('Показывать на сайте')
                ->value(true)
                ->sendTrueOrFalse(),
        ];
    }
}