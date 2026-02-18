<?php

namespace App\Orchid\Screens\Product;

use App\Models\Product;
use App\Orchid\Layouts\Product\ProductListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class ProductScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     */
    public function query(): iterable
    {
        return [
            'products' => Product::with('categories')->paginate(20)
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Управление товарами';
    }

    /**
     * The description is displayed on the user's screen under the name.
     */
    public function description(): ?string
    {
        return 'Список всех товаров с возможностью редактирования';
    }

    /**
     * The screen's action buttons.
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Создать товар')
                ->icon('bs.plus-circle')
                ->route('platform.products.create')
        ];
    }

    /**
     * The screen's layout elements.
     */
    public function layout(): iterable
    {
        return [
            ProductListLayout::class
        ];
    }

    /**
     * Remove the specified product.
     */
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
}