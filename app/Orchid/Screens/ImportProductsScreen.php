<?php

namespace App\Orchid\Screens;

use App\Services\ImportExportService;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class ImportProductsScreen extends Screen
{
    public $name = 'Импорт товаров';
    public $description = 'Загрузка товаров из CSV файла';

    public function query(): array
    {
        return [];
    }

    /**
     * Кнопки действий
     */
    public function commandBar(): array
    {
        return [
            Button::make('Скачать пример CSV')
                ->method('downloadExample')
                ->icon('bs.download')
                ->rawClick(),

            Button::make('Начать импорт')
                ->method('import')
                ->icon('bs.upload')
                ->class('btn btn-success')
                ->confirm('Вы уверены, что хотите начать импорт? Это может занять некоторое время.')
        ];
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Upload::make('file')
                    ->title('CSV файл с товарами')
                    ->subtitle('Загрузите CSV файл')
                    ->help('Формат: CSV с разделителем | (вертикальная черта). Скачайте пример для образца.')
                    ->acceptedFiles('.csv')
                    ->maxFiles(1)
                    ->required(),

                Select::make('mode')
                    ->title('Режим импорта')
                    ->options([
                        'create_update' => 'Создавать новые и обновлять существующие',
                        'create_only' => 'Только создавать новые',
                        'update_only' => 'Только обновлять существующие',
                    ])
                    ->value('create_update')
                    ->help('Как обрабатывать товары, которые уже есть в базе'),

                Select::make('match_by')
                    ->title('Поиск существующих товаров по')
                    ->options([
                        'slug' => 'URL (slug)',
                        'sku' => 'Артикул (SKU)',
                        'article' => 'Артикул производителя',
                    ])
                    ->value('slug')
                    ->help('Поле для сопоставления товаров при обновлении'),
            ])->title('Параметры импорта'),
        ];
    }

    /**
     * Запуск импорта
     */
/**
 * Запуск импорта
 */
    public function import(Request $request, ImportExportService $service)
    {
        $request->validate([
            'file' => 'required',
            'mode' => 'required|in:create_update,create_only,update_only',
            'match_by' => 'required|in:slug,sku,article',
        ]);

        // Получаем загруженный файл
        $file = $request->get('file');
        if (empty($file) || !is_array($file) || empty($file[0])) {
            Alert::error('Файл не загружен');
            return redirect()->back();
        }

        // Получаем ID файла
        $attachmentId = $file[0];
        
        // Ищем attachment в базе данных
        $attachment = \Orchid\Attachment\Models\Attachment::find($attachmentId);

        if (!$attachment) {
            Alert::error('Файл не найден в системе (ID: ' . $attachmentId . ')');
            return redirect()->back();
        }

        // Получаем путь к файлу (правильный способ)
        $filePath = storage_path('app/public/' . $attachment->physicalPath());
        
        // Альтернативный вариант, если не работает:
        // $filePath = $attachment->getFullPath();

        if (!file_exists($filePath)) {
            Alert::error('Физический файл не найден по пути: ' . $filePath);
            return redirect()->back();
        }

        try {
            $results = $service->importProducts($filePath, [
                'mode' => $request->mode,
                'match_by' => $request->match_by,
            ], function($rowNumber, $data, $error = null) {
                // Прогресс можно логировать
            });

            Alert::info(sprintf(
                'Импорт завершен: всего %d, создано %d, обновлено %d, ошибок %d',
                $results['total'],
                $results['created'],
                $results['updated'],
                count($results['errors'])
            ));

            if (!empty($results['errors'])) {
                Alert::warning('Ошибки: ' . implode('; ', array_slice($results['errors'], 0, 3)));
            }

        } catch (\Exception $e) {
            Alert::error('Ошибка импорта: ' . $e->getMessage());
        }

        // Удаляем загруженный файл после обработки
        if ($attachment) {
            $attachment->delete();
        }

        return redirect()->route('platform.import');
    }

    /**
     * Скачать пример CSV
     */
    public function downloadExample(ImportExportService $service)
    {
        return $service->downloadExampleCsv();
    }
}