<?php

namespace App\Orchid\Screens\Category;

use App\Models\Category;
use App\Orchid\Layouts\Category\CategoryEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class CategoryEditScreen extends Screen
{
    public $category;
    public $name = 'Создание категории';

    public function query(Category $category): array
    {
        $this->category = $category;
        if ($category->exists) {
            $this->name = 'Редактирование: ' . $category->name;
        }
        return ['category' => $category];
    }

    public function commandBar(): array
    {
        return [
            Button::make('Сохранить')->method('save')->icon('bs.save'),
            Button::make('Удалить')->method('remove')->icon('bs.trash')->canSee($this->category->exists),
        ];
    }

    public function layout(): array
    {
        return [CategoryEditLayout::class];
    }

    public function save(Request $request, Category $category)
    {
        $data = $request->get('category');
        if (empty($data['parent_id'])) $data['parent_id'] = null;
        
        $category->fill($data)->save();
        Alert::info('Сохранено');
        return redirect()->route('platform.categories');
    }

    public function remove(Category $category)
    {
        $category->delete();
        Alert::info('Удалено');
        return redirect()->route('platform.categories');
    }
}