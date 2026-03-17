<?php

namespace App\Orchid\Layouts\Category;

use App\Models\Category;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CategoryListLayout extends Table
{
    protected $target = 'categories';

    protected function columns(): array
    {
        return [
            // Чекбоксы для выбора категорий
            TD::make('checkbox', ' ')
                ->render(function (Category $category) {
                    return '<div style="display: flex; align-items: center; justify-content: center;">
                        <input type="checkbox" name="selected_categories[]" value="' . $category->id . '" class="category-checkbox" style="width: 16px; height: 16px;">
                    </div>';
                })
                ->width('50px'),

            TD::make('name', 'Название')
                ->render(function (Category $category) {
                    $depth = $category->depth ?? 0;
                    return str_repeat('— ', $depth) . $category->name;
                }),

            TD::make('active', 'Активна')
                ->render(fn (Category $category) => $category->active ? '✅' : '❌'),

            TD::make('slug', 'URL'),

            TD::make('created_at', 'Создано'),

            TD::make(__('Действия'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Category $category) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        Link::make('Редактировать')
                            ->route('platform.categories.edit', $category->id)
                            ->icon('bs.pencil'),

                        Button::make('Удалить')
                            ->icon('bs.trash3')
                            ->confirm('Вы уверены?')
                            ->method('remove')
                            ->parameters(['id' => $category->id]),
                    ])),
        ];
    }
}