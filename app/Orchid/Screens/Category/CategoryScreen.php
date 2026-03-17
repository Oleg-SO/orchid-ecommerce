<?php

namespace App\Orchid\Screens\Category;

use App\Models\Category;
use App\Orchid\Layouts\Category\CategoryListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Illuminate\Http\Request;

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
                ->route('platform.categories.create'),

            Button::make('Удалить выбранные')
                ->method('bulkDelete')
                ->icon('bs.trash3')
                ->confirm('Вы уверены, что хотите удалить выбранные категории?')
                ->class('btn btn-danger'),
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

    public function bulkDelete(Request $request)
    {
        $selectedIds = $request->input('selected_categories', []);
        $selectedIds = array_filter($selectedIds);
        
        if (empty($selectedIds)) {
            Alert::warning('Ни одной категории не выбрано');
            return redirect()->back();
        }

        $count = Category::whereIn('id', $selectedIds)->delete();
        
        Alert::success("Удалено категорий: {$count}");
        
        return redirect()->route('platform.categories');
    }
}