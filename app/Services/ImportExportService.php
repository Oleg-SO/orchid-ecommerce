<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Specification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Closure;
use Orchid\Attachment\Models\Attachment;

class ImportExportService
{
    /**
     * Экспорт товаров в CSV
     */
    public function exportProducts(array $params = []): Closure
    {
        $query = Product::with(['categories', 'specifications', 'attachments']);
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

        if (!empty($params['with_images'])) {
            $headers[] = 'image_paths';
        }

        return $headers;
    }

    /**
     * Получить пути ко всем изображениям товара
     */
    protected function getImagePaths($product): string
    {
        $images = [];
        foreach ($product->attachments as $attachment) {
            $images[] = $attachment->path . $attachment->name . '.' . $attachment->extension;
        }
        return implode('|', $images);
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
                'image_paths' => $this->getImagePaths($product),
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
     * Импорт фото из локального пути
     */
    protected function importLocalImage($product, $imagePath)
    {
        $fullPath = storage_path("app/public/" . $imagePath);
        
        if (!file_exists($fullPath)) {
            \Log::warning('Локальный файл не найден: ' . $imagePath);
            return;
        }
        
        $pathParts = explode('/', $imagePath);
        $filename = end($pathParts);
        $folder = $pathParts[count($pathParts) - 2] ?? '';
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $mime = mime_content_type($fullPath);
        $size = filesize($fullPath);
        
        $attachment = Attachment::create([
            'name' => $name,
            'original_name' => $filename,
            'extension' => $ext,
            'path' => ($folder ? 'products/' . $folder . '/' : 'products/'),
            'mime' => $mime,
            'size' => $size,
            'disk' => 'public',
            'group' => 'products',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('attachmentable')->insert([
            'attachmentable_type' => 'App\Models\Product',
            'attachmentable_id' => $product->id,
            'attachment_id' => $attachment->id,
        ]);
        
        \Log::info('Локальное фото привязано: ' . $imagePath . ' -> товар ID ' . $product->id);
    }

    /**
     * Импорт фото из URL (скачивание)
     */
    protected function importImageFromUrl($product, $url)
    {
        try {
            // Скачиваем фото
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $imageContent = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode !== 200 || $imageContent === false) {
                \Log::warning('Не удалось скачать изображение: ' . $url . ' (HTTP: ' . $httpCode . ')');
                return;
            }
            
            // Определяем имя файла
            $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
            $filename = $pathInfo['filename'] . '.' . ($pathInfo['extension'] ?? 'jpg');
            $name = $pathInfo['filename'];
            $ext = $pathInfo['extension'] ?? 'jpg';
            
            // Определяем mime тип
            $mime = match($ext) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                default => 'image/jpeg',
            };
            
            // Создаем папку по дате
            $year = date('Y');
            $month = date('m');
            $day = date('d');
            $folder = "products/{$year}/{$month}/{$day}";
            $fullPath = storage_path("app/public/{$folder}");
            
            if (!file_exists($fullPath)) {
                $oldUmask = umask(0);
                mkdir($fullPath, 0777, true);
                umask($oldUmask);
            }
            
            // Сохраняем файл
            $filePath = $fullPath . '/' . $filename;
            file_put_contents($filePath, $imageContent);
            
            // Создаем запись в attachments
            $attachment = Attachment::create([
                'name' => $name,
                'original_name' => $filename,
                'extension' => $ext,
                'path' => $folder . '/',
                'mime' => $mime,
                'size' => strlen($imageContent),
                'disk' => 'public',
                'group' => 'products',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Привязываем фото к товару через таблицу attachmentable
            DB::table('attachmentable')->insert([
                'attachmentable_type' => 'App\Models\Product',
                'attachmentable_id' => $product->id,
                'attachment_id' => $attachment->id,
            ]);
            
            \Log::info('Фото скачано и привязано: ' . $url . ' -> товар ID ' . $product->id);
            
        } catch (\Exception $e) {
            \Log::warning('Ошибка скачивания фото: ' . $e->getMessage());
        }
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
            'image_paths' => !empty($data['image_paths']) ? trim($data['image_paths']) : null,
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

        // Обработка фото
        if (!empty($normalized['image_paths'])) {
            $imagePaths = explode('|', $normalized['image_paths']);
            
            \Log::info('Обработка фото для товара ID ' . $product->id . ': ' . $normalized['image_paths']);
            
            // Удаляем старые фото при обновлении
            if ($product->exists && $product->attachments->count() > 0) {
                foreach ($product->attachments as $oldImage) {
                    $oldImage->delete();
                }
                \Log::info('Удалено старых фото: ' . count($product->attachments));
            }
            
            // Добавляем новые фото
            foreach ($imagePaths as $imagePath) {
                $imagePath = trim($imagePath);
                if (empty($imagePath)) continue;
                
                \Log::info('Обработка пути: ' . $imagePath);
                
                // Проверяем, является ли путь URL
                if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
                    \Log::info('Это URL, скачиваем...');
                    $this->importImageFromUrl($product, $imagePath);
                } else {
                    \Log::info('Это локальный путь');
                    $this->importLocalImage($product, $imagePath);
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
            'category_names', 'spec_names', 'spec_values', 'image_paths'
        ];

        $example = [
            'Дрель ударная BOSCH|drel-bosch|4990|5990|10|BOSCH-001|DB-100|Мощная дрель|Профессиональная дрель|1|3|1|0|Электроинструмент,BOSCH|Мощность,Обороты|1500 Вт,3000 об/мин|https://cdn.vseinstrumenti.ru/images/goods/stroitelnyj-instrument/dreli-elektricheskie/50358/560x504/188774609.jpg',
            'Шуруповерт Makita|shurup-makita|8990||15|MAK-002|SM-200|Аккумуляторный шуруповерт|Для дома и дачи|1|3|0|1|Электроинструмент,Makita|Аккумулятор,Крутящий момент|4 А·ч,60 Н·м|https://cdn.vseinstrumenti.ru/images/goods/stroitelnyj-instrument/shurupoverty/964589/560x504/52439261.jpg',
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