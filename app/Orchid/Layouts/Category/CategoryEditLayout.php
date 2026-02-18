<?php

namespace App\Orchid\Layouts\Category;

use App\Models\Category;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class CategoryEditLayout extends Rows
{
    protected function fields(): array
    {
        return [
            Input::make('category.name')
                ->title('Название')
                ->required(),

            Input::make('category.slug')
                ->title('URL')
                ->required(),

            Relation::make('category.parent_id')
                ->title('Родительская категория')
                ->fromModel(Category::class, 'name')
                ->applyScope('defaultOrder')
                ->empty('Корневая категория'),

            Quill::make('category.description')
                ->title('Описание'),

            Upload::make('category.image')
                ->title('Изображение')
                ->maxFiles(1),

            CheckBox::make('category.active')
                ->title('Активна')
                ->placeholder('Показывать на сайте')
                ->value(true),
        ];
    }
}