<?php

namespace App\Orchid\Screens\Category;

use App\Models\Category;
use App\Orchid\Layouts\Category\CategoryListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class CategoryScreen extends Screen
{
    public $name = 'Категории';
    public $description = 'Управление категориями товаров';

    public function query(): array
    {
        return [
            'categories' => Category::defaultOrder()->withDepth()->get()
        ];
    }

    public function commandBar(): array
    {
        return [
            Link::make('Создать')
                ->icon('bs.plus-circle')
                ->route('platform.categories.create')
        ];
    }

    public function layout(): array
    {
        return [
            CategoryListLayout::class
        ];
    }

    public function remove($id)
    {
        Category::find($id)?->delete();
        Alert::info('Категория удалена');
        return redirect()->route('platform.categories');
    }
}