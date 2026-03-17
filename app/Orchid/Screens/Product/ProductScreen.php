<?php

namespace App\Orchid\Screens\Product;

use App\Models\Product;
use App\Orchid\Layouts\Product\ProductListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Illuminate\Http\Request;

class ProductScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'products' => Product::with('categories')->paginate(20)
        ];
    }

    public function name(): ?string
    {
        return 'Управление товарами';
    }

    public function description(): ?string
    {
        return 'Список всех товаров с возможностью редактирования';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Создать товар')
                ->icon('bs.plus-circle')
                ->route('platform.products.create'),

            // Кнопка массового удаления
            Button::make('Удалить выбранные')
                ->method('bulkDelete')
                ->icon('bs.trash3')
                ->confirm('Вы уверены, что хотите удалить выбранные товары?')
                ->class('btn btn-danger'),
        ];
    }

    public function layout(): iterable
    {
        return [
            ProductListLayout::class
        ];
    }

    public function remove($id)
    {
        $product = Product::find($id);

        if ($product) {
            $product->delete();
            Alert::info('Товар успешно удален');
        } else {
            Alert::warning('Товар не найден');
        }

        return redirect()->route('platform.products');
    }

    public function bulkDelete(Request $request)
    {
        $selectedIds = $request->input('selected_products', []);
        $selectedIds = array_filter($selectedIds);
        
        if (empty($selectedIds)) {
            Alert::warning('Ни одного товара не выбрано');
            return redirect()->back();
        }

        $count = Product::whereIn('id', $selectedIds)->delete();
        
        Alert::success("Удалено товаров: {$count}");
        
        // Полная перезагрузка страницы с Redirect
        return redirect()->route('platform.products');
    }
}