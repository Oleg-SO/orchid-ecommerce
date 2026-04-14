<?php

namespace App\Orchid\Layouts\Category;

use App\Models\Category;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class CategoryEditFormLayout extends Rows
{
    protected $parents;

    public function __construct($parents = null)
    {
        $this->parents = $parents;
    }

    protected function fields(): array
    {
        $options = ['' => 'Корневая категория'];

        if ($this->parents) {
            foreach ($this->parents as $parent) {
                $indent = str_repeat('— ', $parent->depth ?? 0);
                $options[$parent->id] = $indent . $parent->name;
            }
        }

        return [
            Input::make('category.name')
                ->title('Название')
                ->required()
                ->placeholder('Введите название категории'),

            Input::make('category.slug')
                ->title('URL')
                ->required()
                ->placeholder('url-dlya-kategorii'),

            Select::make('category.parent_id')
                ->title('Родительская категория')
                ->options($options)
                ->help('Выберите родительскую категорию.'),

            Quill::make('category.description')
                ->title('Описание')
                ->placeholder('Описание категории'),

            Upload::make('category.image')
                ->title('Изображение')
                ->maxFiles(1)
                ->acceptedFiles('image/*'),

            CheckBox::make('category.active')
                ->title('Активна')
                ->placeholder('Показывать на сайте')
                ->value(true)
                ->sendTrueOrFalse(),
        ];
    }
}
