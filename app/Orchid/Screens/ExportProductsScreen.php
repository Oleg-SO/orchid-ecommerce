<?php

namespace App\Orchid\Screens;

use App\Services\ImportExportService;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class ExportProductsScreen extends Screen
{
    public $name = 'Экспорт товаров';
    public $description = 'Выгрузка товаров в CSV файл';

    public function query(): array
    {
        return [
            'total_products' => \App\Models\Product::count(),
        ];
    }

    public function commandBar(): array
    {
        return [
            Button::make('Экспортировать')
                ->method('export')
                ->icon('bs.download')
                ->rawClick(),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Select::make('format')
                    ->title('Формат файла')
                    ->options([
                        'csv' => 'CSV (разделитель |)',
                    ])
                    ->value('csv'),

                Select::make('fields')
                    ->title('Поля для экспорта')
                    ->options([
                        'basic' => 'Основные поля',
                        'full' => 'Все поля (включая характеристики)',
                    ])
                    ->value('full'),

                CheckBox::make('with_categories')
                    ->title('Категории')
                    ->placeholder('Включить колонку с категориями')
                    ->value(true),

                CheckBox::make('with_specs')
                    ->title('Характеристики')
                    ->placeholder('Включить характеристики')
                    ->value(true),
            ])->title('Настройки экспорта'),
        ];
    }

    public function export(Request $request, ImportExportService $service)
    {
        $params = $request->validate([
            'format' => 'required|in:csv',
            'fields' => 'required|in:basic,full',
            'with_categories' => 'nullable',
            'with_specs' => 'nullable',
        ]);

        $params['with_categories'] = $request->boolean('with_categories');
        $params['with_specs'] = $request->boolean('with_specs');

        // Получаем callback из сервиса
        $callback = $service->exportProducts($params);

        // Возвращаем streamed response
        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="products_' . date('Y-m-d_Hi') . '.csv"',
        ]);
    }
}