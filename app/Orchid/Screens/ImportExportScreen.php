<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Specification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportExportService
{
    /**
     * Экспорт товаров
     */
    public function exportProducts(array $params = []): StreamedResponse
    {
        $query = Product::with(['categories', 'specifications']);

        // Фильтры можно добавить позже

        $products = $query->get();

        $headers = $this->buildHeaders($params);

        $callback = function() use ($products, $headers, $params) {
            $handle = fopen('php://output', 'w');

            // BOM для UTF-8
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Заголовки
            fputcsv($handle, $headers, '|');

            foreach ($products as $product) {
                $row = $this->buildRow($product, $headers, $params);
                fputcsv($handle, $row, '|');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="products_' . date('Y-m-d_Hi') . '.csv"',
        ]);
    }

    /**
     * Скачать пример CSV
     */
    public function downloadExampleCsv(): StreamedResponse
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
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
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

    /**
     * Импорт товаров
     */
    public function importProducts($filePath, array $options = [], $progressCallback = null)
    {
        if (!file_exists($filePath)) {
            throw new \Exception('Файл не найден');
        }

        $handle = fopen($filePath, 'r');

        // Читаем BOM
        $bom = fread($handle, 3);
        if ($bom !== chr(0xEF).chr(0xBB).chr(0xBF)) {
            rewind($handle);
        }

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
                $data = array_combine($headers, $row);

                if (empty(array_filter($data))) {
                    continue;
                }

                $results['total']++;

                try {
                    $action = $this->importProductRow($data, $options);
                    if ($action === 'created') {
                        $results['created']++;
                    } else {
                        $results['updated']++;
                    }

                    if ($progressCallback) {
                        call_user_func($progressCallback, $rowNumber, $data);
                    }

                } catch (\Exception $e) {
                    $results['errors'][] = "Строка {$rowNumber}: {$e->getMessage()}";
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

    protected function buildHeaders($params): array
    {
        $headers = ['name', 'slug', 'price'];

        if ($params['fields'] === 'full' || $params['fields'] === 'custom') {
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

    protected function buildRow($product, $headers, $params): array
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

    protected function importProductRow($data, $options)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'active' => 'nullable|in:0,1,true,false,yes,no',
        ]);

        if ($validator->fails()) {
            throw new \Exception(implode(' ', $validator->errors()->all()));
        }

        $productData = [
            'name' => $data['name'],
            'slug' => $data['slug'] ?? Str::slug($data['name']),
            'price' => $data['price'],
            'old_price' => $data['old_price'] ?? null,
            'quantity' => $data['quantity'] ?? 0,
            'sku' => $data['sku'] ?? null,
            'article' => $data['article'] ?? null,
            'description' => $data['description'] ?? null,
            'short_description' => $data['short_description'] ?? null,
            'active' => $this->parseBoolean($data['active'] ?? '1'),
            'warranty' => $data['warranty'] ?? 1,
            'is_hit' => $this->parseBoolean($data['is_hit'] ?? '0'),
            'is_new' => $this->parseBoolean($data['is_new'] ?? '0'),
        ];

        // Поиск товара
        $product = null;
        $matchBy = $options['match_by'] ?? 'slug';

        if ($matchBy === 'slug' && !empty($productData['slug'])) {
            $product = Product::where('slug', $productData['slug'])->first();
        } elseif ($matchBy === 'sku' && !empty($productData['sku'])) {
            $product = Product::where('sku', $productData['sku'])->first();
        } elseif ($matchBy === 'article' && !empty($productData['article'])) {
            $product = Product::where('article', $productData['article'])->first();
        }

        $mode = $options['mode'] ?? 'create_update';

        if ($product && $mode === 'create_only') {
            return 'skipped';
        }

        if (!$product && $mode === 'update_only') {
            return 'skipped';
        }

        if ($product) {
            $product->update($productData);
            $action = 'updated';
        } else {
            $product = Product::create($productData);
            $action = 'created';
        }

        // Категории
        if (!empty($data['category_names'])) {
            $categoryNames = array_map('trim', explode(',', $data['category_names']));
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

        // Характеристики
        if (!empty($data['spec_names']) && !empty($data['spec_values'])) {
            $specNames = array_map('trim', explode(',', $data['spec_names']));
            $specValues = array_map('trim', explode(',', $data['spec_values']));

            if (count($specNames) === count($specValues)) {
                $product->specifications()->delete();

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

    protected function parseBoolean($value)
    {
        if (is_bool($value)) return $value;
        if (is_numeric($value)) return (bool) $value;

        $value = strtolower(trim($value));
        return in_array($value, ['1', 'true', 'yes', 'on', 'да']);
    }
}