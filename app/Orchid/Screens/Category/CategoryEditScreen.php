<?php

namespace App\Orchid\Screens\Category;

use App\Models\Category;
use App\Orchid\Layouts\Category\CategoryEditFormLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Attachment\Models\Attachment;

class CategoryEditScreen extends Screen
{
    public $category;

    public function query(Category $category): iterable
    {
        $this->category = $category;

        return [
            'category' => $category
        ];
    }

    public function name(): ?string
    {
        return $this->category->exists
            ? 'Редактирование: ' . $this->category->name
            : 'Создание новой категории';
    }

    public function description(): ?string
    {
        return $this->category->exists
            ? 'Изменение параметров категории'
            : 'Добавление новой категории в каталог';
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Сохранить')
                ->icon('bs.save')
                ->method('save'),

            Button::make('Удалить')
                ->icon('bs.trash')
                ->method('remove')
                ->canSee($this->category->exists),
        ];
    }

    public function layout(): iterable
    {
        // Получаем доступные родительские категории
        $availableParents = Category::defaultOrder();

        if ($this->category->exists) {
            $availableParents = $availableParents->whereNotDescendantOf($this->category)
                                                 ->where('id', '!=', $this->category->id);
        }

        return [
            new CategoryEditFormLayout($availableParents->get()),
        ];
    }

    public function save(Request $request, Category $category)
    {
        $request->validate([
            'category.name' => 'required|string|max:255',
            'category.slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
        ]);

        $data = $request->get('category');

        // Правильная обработка active
        $data['active'] = isset($data['active']) && in_array($data['active'], ['value', 'on', '1', 1, true], true);

        // Обработка parent_id
        if (empty($data['parent_id'])) {
            $data['parent_id'] = null;
        }

        // Дополнительная проверка: нельзя установить родителя, который является потомком
        if (!empty($data['parent_id']) && $category->exists) {
            $parentCategory = Category::find($data['parent_id']);
            if ($parentCategory && $parentCategory->isDescendantOf($category)) {
                Alert::error('Нельзя сделать потомка родителем текущей категории');
                return redirect()->back();
            }
        }

        $category->fill($data)->save();

        // Обработка фото
        if ($request->has('category.attachment') && is_array($request->input('category.attachment'))) {
            $attachmentIds = $request->input('category.attachment', []);

            foreach ($attachmentIds as $attachmentId) {
                Attachment::where('id', $attachmentId)
                    ->update([
                        'attachmentable_id' => $category->id,
                        'attachmentable_type' => Category::class,
                    ]);
            }

            $category->attachment()->sync($attachmentIds);
        }

        Alert::info('Категория успешно сохранена');

        return redirect()->route('platform.categories');
    }

    public function remove(Category $category)
    {
        foreach ($category->attachment as $attachment) {
            $attachment->delete();
        }

        $category->delete();

        Alert::info('Категория успешно удалена');

        return redirect()->route('platform.categories');
    }
}
