<?php

namespace App\Orchid\Screens\Product;

use App\Models\Product;
use App\Orchid\Layouts\Product\ProductEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class ProductEditScreen extends Screen
{
    public $product;

    /**
     * Fetch data to be displayed on the screen.
     */
    public function query(Product $product): iterable
    {
        $this->product = $product;
        
        return [
            'product' => $product
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->product->exists 
            ? 'Редактирование: ' . $this->product->name 
            : 'Создание нового товара';
    }

    /**
     * The description is displayed on the user's screen under the name.
     */
    public function description(): ?string
    {
        return $this->product->exists
            ? 'Изменение параметров товара'
            : 'Добавление нового товара в каталог';
    }

    /**
     * The screen's action buttons.
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Сохранить')
                ->icon('bs.save')
                ->method('save'),
            
            Button::make('Удалить')
                ->icon('bs.trash')
                ->method('remove')
                ->canSee($this->product->exists),
        ];
    }

    /**
     * The screen's layout elements.
     */
    public function layout(): iterable
    {
        return [
            ProductEditLayout::class
        ];
    }

    /**
     * Save the product.
     */
    public function save(Request $request, Product $product)
    {
        $request->validate([
            'product.name' => 'required|string|max:255',
            'product.slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'product.price' => 'required|numeric|min:0',
        ]);

        $data = $request->get('product');
        
        // Обработка активного поля
        if (isset($data['active'])) {
            $data['active'] = in_array($data['active'], ['value', 'on', 'true', '1', 1, true], true);
        } else {
            $data['active'] = false;
        }
        
        // Обработка цены
        if (empty($data['old_price'])) {
            $data['old_price'] = null;
        }
        
        $data['quantity'] = (int) ($data['quantity'] ?? 0);
        
        // Сохраняем товар
        $product->fill($data)->save();
        
        // ВАЖНО: Прикрепляем загруженные изображения
        if ($request->has('product.attachment') && is_array($request->input('product.attachment'))) {
            $product->attachment()->syncWithoutDetaching(
                $request->input('product.attachment', [])
            );
        }
        
        // Синхронизация категорий
        if (isset($data['categories']) && is_array($data['categories'])) {
            $product->categories()->sync($data['categories']);
        }

        Alert::info('Товар успешно сохранен');
        
        return redirect()->route('platform.products');
    }

    /**
     * Remove the product.
     */
    public function remove(Product $product)
    {
        $product->delete();
        
        Alert::info('Товар успешно удален');
        
        return redirect()->route('platform.products');
    }
}