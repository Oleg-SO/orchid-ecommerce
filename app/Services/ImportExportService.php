<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Specification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Closure;

class ImportExportService
{
    /**
     * Экспорт товаров в CSV
     */
    public function exportProducts(array $params = []): Closure
    {
        $query = Product::with(['categories', 'specifications']);
        $products = $query->get();
        $headers = $this->buildHeaders($params);

        return function() use ($products, $headers, $params) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM
            fputcsv($handle, $headers, '|');

            foreach ($products as $product) {
                $row = $this->buildRow($product, $headers, $params);
                fputcsv($handle, $row, '|');
            }

            fclose($handle);
        };
    }

    /**
     * Построить заголовки CSV на основе параметров
     */
    protected function buildHeaders(array $params): array
    {
        $headers = ['name', 'slug', 'price'];

        if (($params['fields'] ?? 'full') === 'full') {
            $headers = array_merge($headers, [
                'old_price', 'quantity', 'sku', 'article', 'description',
                'short_description', 'active', 'warranty', 'is_hit', 'is_new'
            ]);
        }

        if (!empty($params['with_categories'])) {
            $headers[] = 'category_names';
        }

        if (!empty($params['with_specs'])) {
            $headers[] = 'spec_names';
            $headers[] = 'spec_values';
        }

        return $headers;
    }

    /**
     * Построить строку данных для товара
     */
    protected function buildRow($product, array $headers, array $params): array
    {
        $row = [];

        foreach ($headers as $header) {
            $row[] = match ($header) {
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'old_price' => $product->old_price ?? '',
                'quantity' => $product->quantity ?? 0,
                'sku' => $product->sku ?? '',
                'article' => $product->article ?? '',
                'description' => strip_tags($product->description ?? ''),
                'short_description' => $product->short_description ?? '',
                'active' => $product->active ? '1' : '0',
                'warranty' => $product->warranty ?? 1,
                'is_hit' => $product->is_hit ? '1' : '0',
                'is_new' => $product->is_new ? '1' : '0',
                'category_names' => $product->categories->pluck('name')->join(','),
                'spec_names' => $product->specifications->pluck('name')->join(','),
                'spec_values' => $product->specifications->pluck('value')->join(','),
                default => '',
            };
        }

        return $row;
    }

    /**
     * Импорт товаров из CSV
     */
    public function importProducts($filePath, array $options = [], $callback = null)
    {
        if (!file_exists($filePath)) {
            throw new \Exception('Файл не найден по пути: ' . $filePath);
        }

        $handle = fopen($filePath, 'r');
        
        // Читаем BOM если есть
        $bom = fread($handle, 3);
        if ($bom !== chr(0xEF).chr(0xBB).chr(0xBF)) {
            rewind($handle);
        }
        
        // Читаем заголовки
        $headers = fgetcsv($handle, 0, '|');
        if (!$headers) {
            throw new \Exception('Не удалось прочитать заголовки CSV');
        }
        
        $required = ['name', 'price'];
        $missing = array_diff($required, $headers);
        if (!empty($missing)) {
            throw new \Exception('Отсутствуют обязательные колонки: ' . implode(', ', $missing));
        }
        
        $results = [
            'total' => 0,
            'created' => 0,
            'updated' => 0,
            'errors' => [],
        ];
        
        DB::beginTransaction();
        
        try {
            $rowNumber = 1;
            while (($row = fgetcsv($handle, 0, '|')) !== false) {
                $rowNumber++;
                
                // Пропускаем пустые строки
                if (empty(array_filter($row))) {
                    continue;
                }
                
                // Создаем ассоциативный массив
                $data = [];
                foreach ($headers as $index => $header) {
                    $data[$header] = $row[$index] ?? '';
                }
                
                $results['total']++;
                
                try {
                    $action = $this->importProductRow($data, $options);
                    if ($action === 'created') {
                        $results['created']++;
                    } elseif ($action === 'updated') {
                        $results['updated']++;
                    }
                    
                    if ($callback) {
                        call_user_func($callback, $rowNumber, $data);
                    }
                    
                } catch (\Exception $e) {
                    $results['errors'][] = "Строка {$rowNumber}: {$e->getMessage()}";
                    
                    if ($callback) {
                        call_user_func($callback, $rowNumber, $data, $e->getMessage());
                    }
                }
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        
        fclose($handle);
        
        return $results;
    }

    /**
     * Обработка одной строки товара
     */
    protected function importProductRow($data, array $options = [])
    {
        // Нормализуем данные
        $normalized = [
            'name' => trim($data['name'] ?? ''),
            'slug' => !empty($data['slug']) ? trim($data['slug']) : null,
            'price' => isset($data['price']) && $data['price'] !== '' ? (float)str_replace(',', '.', $data['price']) : 0,
            'old_price' => isset($data['old_price']) && $data['old_price'] !== '' ? (float)str_replace(',', '.', $data['old_price']) : null,
            'quantity' => isset($data['quantity']) && $data['quantity'] !== '' ? (int)$data['quantity'] : 0,
            'sku' => !empty($data['sku']) ? trim($data['sku']) : null,
            'article' => !empty($data['article']) ? trim($data['article']) : null,
            'description' => !empty($data['description']) ? trim($data['description']) : null,
            'short_description' => !empty($data['short_description']) ? trim($data['short_description']) : null,
            'active' => $data['active'] ?? '1',
            'warranty' => isset($data['warranty']) && $data['warranty'] !== '' ? (int)$data['warranty'] : 1,
            'is_hit' => $data['is_hit'] ?? '0',
            'is_new' => $data['is_new'] ?? '0',
            'category_names' => !empty($data['category_names']) ? trim($data['category_names']) : null,
            'spec_names' => !empty($data['spec_names']) ? trim($data['spec_names']) : null,
            'spec_values' => !empty($data['spec_values']) ? trim($data['spec_values']) : null,
        ];

        // Валидация
        $errors = [];
        
        if (empty($normalized['name'])) {
            $errors[] = 'Название товара обязательно';
        }
        
        if ($normalized['price'] <= 0) {
            $errors[] = 'Цена должна быть больше 0';
        }
        
        if (!empty($errors)) {
            throw new \Exception(implode('; ', $errors));
        }

        // Подготовка данных для сохранения
        $productData = [
            'name' => $normalized['name'],
            'slug' => $normalized['slug'] ?? Str::slug($normalized['name']),
            'price' => $normalized['price'],
            'old_price' => $normalized['old_price'],
            'quantity' => $normalized['quantity'],
            'sku' => $normalized['sku'],
            'article' => $normalized['article'],
            'description' => $normalized['description'],
            'short_description' => $normalized['short_description'],
            'active' => $this->parseBoolean($normalized['active']),
            'warranty' => $normalized['warranty'],
            'is_hit' => $this->parseBoolean($normalized['is_hit']),
            'is_new' => $this->parseBoolean($normalized['is_new']),
        ];

        // Определяем режим импорта
        $mode = $options['mode'] ?? 'create_update';
        $matchBy = $options['match_by'] ?? 'slug';

        // Поиск существующего товара
        $product = null;
        
        if ($matchBy === 'sku' && !empty($normalized['sku'])) {
            $product = Product::where('sku', $normalized['sku'])->first();
        }
        if (!$product && $matchBy === 'article' && !empty($normalized['article'])) {
            $product = Product::where('article', $normalized['article'])->first();
        }
        if (!$product && ($matchBy === 'slug' || empty($product)) && !empty($normalized['slug'])) {
            $product = Product::where('slug', $normalized['slug'])->first();
        }

        // Проверка режимов
        if ($product && $mode === 'create_only') {
            return 'skipped';
        }
        
        if (!$product && $mode === 'update_only') {
            return 'skipped';
        }

        // Обновление или создание
        if ($product) {
            $product->update($productData);
            $action = 'updated';
        } else {
            $product = Product::create($productData);
            $action = 'created';
        }

        // Обработка категорий
        if (!empty($normalized['category_names'])) {
            $categoryNames = array_map('trim', explode(',', $normalized['category_names']));
            $categoryIds = [];
            
            foreach ($categoryNames as $name) {
                if (empty($name)) continue;
                
                $category = Category::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name, 'active' => true]
                );
                $categoryIds[] = $category->id;
            }
            
            $product->categories()->sync($categoryIds);
        }

        // Обработка характеристик
        if (!empty($normalized['spec_names']) && !empty($normalized['spec_values'])) {
            $specNames = array_map('trim', explode(',', $normalized['spec_names']));
            $specValues = array_map('trim', explode(',', $normalized['spec_values']));
            
            if (count($specNames) === count($specValues)) {
                // Удаляем старые характеристики
                $product->specifications()->delete();
                
                // Добавляем новые
                foreach ($specNames as $index => $name) {
                    if (empty($name)) continue;
                    
                    Specification::create([
                        'product_id' => $product->id,
                        'name' => $name,
                        'value' => $specValues[$index] ?? '',
                        'sort_order' => $index,
                    ]);
                }
            }
        }

        return $action;
    }

    /**
     * Парсинг булевых значений из CSV
     */
    protected function parseBoolean($value)
    {
        if (is_bool($value)) return $value;
        if (is_numeric($value)) return (bool) $value;
        
        $value = strtolower(trim($value));
        return in_array($value, ['1', 'true', 'yes', 'on', 'да']);
    }

    /**
     * Скачать пример CSV
     */
    public function downloadExampleCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $headers = [
            'name', 'slug', 'price', 'old_price', 'quantity', 'sku', 'article',
            'description', 'short_description', 'active', 'warranty', 'is_hit', 'is_new',
            'category_names', 'spec_names', 'spec_values'
        ];

        $example = [
            'Дрель ударная BOSCH|drel-bosch|4990|5990|10|BOSCH-001|DB-100|Мощная дрель|Профессиональная дрель|1|3|1|0|Электроинструмент,BOSCH|Мощность,Обороты|1500 Вт,3000 об/мин',
            'Шуруповерт Makita|shurup-makita|8990||15|MAK-002|SM-200|Аккумуляторный шуруповерт|Для дома и дачи|1|3|0|1|Электроинструмент,Makita|Аккумулятор,Крутящий момент|4 А·ч,60 Н·м',
        ];

        $callback = function() use ($headers, $example) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM
            fputcsv($handle, $headers, '|');
            foreach ($example as $line) {
                fputcsv($handle, explode('|', $line), '|');
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="example_products.csv"',
        ]);
    }
}