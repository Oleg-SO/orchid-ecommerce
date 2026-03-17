<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use Orchid\Attachment\Models\Attachment;

// Данные из твоего списка (product_id => old_path)
$photos = [
    6 => 'products/May2023/0cN6PBGviPAkjewX0Thf.jpg',
    417 => 'products/May2023/plaFL44dM6XKyQx6Tomo.jpg',
    419 => 'products/May2023/12CwSxlKsC9qPSwpUu3u.jpg',
    422 => 'products/May2023/WwWK6711MiAli7xtE8bl.jpg',
    424 => 'products/September2022/j6FTV8jdNa86o28CiRPI.jpg',
    437 => 'products/September2022/TjQbveS9GAOe8XaetyOE.jpg',
    440 => 'products/February2021/E8BaKaXdqdSTvWQaminS.jpg',
    444 => 'products/August2022/PUVpT9lbhF8vXrE33SuT.png',
    445 => 'products/February2021/LKhGHJ9M8hsS1sU9lssI.jpg',
    448 => 'products/November2022/N9UzsveSW9Hsj1VDpawS.png',
    453 => 'products/February2021/XSDCymYtWADDUz24G0ty.jpg',
    456 => 'products/June2021/tW851w10P6QseXeCTtOS.jpg',
    461 => 'products/May2022/OK9SkrcWicG3T6JZ0qDM.png',
    462 => 'products/May2022/zXhbrjYQhitHNW5lqqLW.png',
    463 => 'products/May2022/ncTe6cPeZAFVof9uD4X4.png',
    464 => 'products/February2023/mmJfgFamZMv6Xlszk5fK.png',
    465 => 'products/July2022/E0T67vBpPKG8CSeuxmt7.jpg',
    466 => 'products/December2022/FM3Dw94xsQs1KDz1sgeR.png',
    468 => 'products/November2022/R6sw7eU1uyBAWvAeweGP.png',
    469 => 'products/September2022/Km9CGHd1NQaS5KU5PgbN.jpg',
    470 => 'products/July2022/7ItqI0WS2bxHhqMtL0Wk.jpg',
    472 => 'products/July2022/gv62UhLS7E988SlkmhHk.jpg',
    473 => 'products/July2022/mmJQjSkbViTWBfq1dnCE.jpg',
    474 => 'products/July2022/hdJvCKXI58kBr0vTrS29.jpg',
    477 => 'products/November2022/bk5qZcDCUqKcQo6oWNNJ.png',
    478 => 'products/November2022/EMoOwbxdMJ7t7SYIzdAW.png',
    479 => 'products/November2022/W7i7IMlF4VV2VGDhhJCI.png',
    487 => 'products/November2022/PDPjAaEG2dnMGpdwvei0.png',
    488 => 'products/November2022/6PCH3xq0ELkw7CaDgPv4.png',
    489 => 'products/December2022/S47EzABaAh3f4ngqLo2U.png',
    490 => 'products/January2023/eTmKBbeJzWOrLBtKa7ny.png',
    491 => 'products/January2023/qon9IUThbPrWFKW1LKPS.png',
    492 => 'products/January2023/QohP3dlwjB7uXZjG9zVE.png',
    493 => 'products/January2023/pDYYARRdyswuZzDrKWUT.png',
    494 => 'products/January2023/7cpyRWZzi5xxnKS9JEgX.png',
    495 => 'products/February2023/yOLNFYjeELgw9bOLhQeA.png',
    496 => 'products/January2023/pLp1JhDeDlZAbb7QhoQS.png',
    498 => 'products/January2023/FF76MsgBlK87WGM6WpO6.png',
    501 => 'products/February2023/pIyvBvc8u2VbTqtllkbA.png',
    502 => 'products/May2023/RRwB9Nd18RYViLLAeB0A.jpg',
    503 => 'products/May2023/FUxpBr3GqfDaXUMC7uvM.jpg',
    504 => 'products/May2023/vRCqrilMpVl2homPKKKc.jpg',
    505 => 'products/May2023/hcolMonHRiu6YYDJ3aSB.jpg',
    506 => 'products/May2023/4GjIDuITATeO4qmLypXg.jpg',
    508 => 'products/May2023/cVNAsp1HUOhjLuG6W4WR.jpg',
    509 => 'products/May2023/x6ylzkjXc6Iaragfcy4P.jpg',
    510 => 'products/May2023/BvKgmMC3dAsMCAPW5gzC.jpg',
    511 => 'products/May2023/Tt4jIhlC1nRx0pHnRz83.jpg',
    512 => 'products/May2023/RNvY5KLyHGReCynTj51D.jpg',
    513 => 'products/May2023/5YS0m8s3MEyxeEAjleAa.jpg',
    514 => 'products/May2023/PlLnqg3743QW5tHFAwXA.jpg',
    515 => 'products/May2023/NhSSfe4iGiXy9ks46qQv.jpg',
    516 => 'products/May2023/9g1qS35IPjy9hexSwVnq.jpg',
    517 => 'products/May2023/5WsCelwRBw62Fd8TEUM7.jpg',
    518 => 'products/May2023/5QfuL9Ddr3kn0GCnA24t.jpg',
    519 => 'products/May2023/22MYRX6kPAydxdOTSvrC.jpg',
    520 => 'products/May2023/3rvSIJ5ZlEZYs77phEVw.jpg',
    521 => 'products/May2023/gXA9MVB74Qk0WOFyky44.jpg',
    522 => 'products/May2023/7Mqw0xdWbxVnW3qiyipN.jpg',
    523 => 'products/May2023/wl5kf3Z3NUVQN8chsCPc.jpg',
    524 => 'products/May2023/l2gdhz82n9YBfDRsnrFb.jpg',
    525 => 'products/May2023/E7WUQHDbTecYAsblHM6h.jpg',
    526 => 'products/May2023/HDkRIYFM8PzO3Uimk861.jpg',
    527 => 'products/May2023/yqPqZ9MrOEn0bZ5kC5Cv.jpg',
    528 => 'products/May2023/8GGNKu325TdBzZLVcfAa.jpg',
    529 => 'products/May2023/TSy6maIi8sZIEZhRfYbS.jpg',
    530 => 'products/May2023/wDv0JSUA1fOLtpYLdNJR.jpg',
    531 => 'products/May2023/jzCVzHqVTkiMqNTHaWhX.jpg',
    532 => 'products/May2023/kpVgFnSNdg0Wv6MNV2Iy.jpg',
    533 => 'products/May2023/hDxZdR5KIdhomi8Xkhgq.jpg',
    534 => 'products/May2023/LeAZDmGm1A4B0iHjLFhL.jpg',
    535 => 'products/May2023/NpV0lQiSulGreKCtjqFg.jpg',
    536 => 'products/May2023/4KjpAjZb5fVjqierKYag.jpg',
    537 => 'products/May2023/MWeCrYwUraXXMxaDODRs.jpg',
    540 => 'products/August2023/pbEwsFHAgBr2N2GIbvHP.png',
    541 => 'products/August2023/j7EWvBMTsxYymDLFPqsE.png',
    548 => 'products/September2023/26gUdhXYfovSD4orgSFI.jpg',
    549 => 'products/September2023/e9uKgiOiu9guAuvr2nNz.jpg',
    550 => 'products/September2023/MOdrZ873vLzvkbDjvhPD.jpg',
    551 => 'products/September2023/hzPiBUf0MpyJS7MfaCvm.jpg',
    553 => 'products/September2023/8D200G9fe7klzZGwv6g2.png',
    554 => 'products/September2023/M8794xEckAOURjpH1Xiv.png',
    563 => 'products/September2023/AjXuuhUGmmAcJ5T1LaUD.jpg',
    568 => 'products/September2023/8NjGkznOHgBomLlIWhDf.jpg',
    580 => 'products/September2023/yr0rdvj944yyn9aD0qsv.jpg',
    581 => 'products/September2023/5QhcVPvKuK8snJCJcxxJ.jpg',
    582 => 'products/September2023/ulwmHyTsK0Ak8IUYnP1a.jpg',
    585 => 'products/September2023/FIS6TgHVENgvWWtHLh64.jpg',
    592 => 'products/September2023/06VOfDHEKcGWjKUCexSt.jpg',
    594 => 'products/September2023/SbSvpQaP4ujKbN5dT0Oj.png',
    601 => 'products/September2023/OaYMcs2fEY7VvNZiNNY2.png',
    604 => 'products/November2023/OFeoXdJXjElTqYuTOffN.png',
    605 => 'products/June2024/F1I6FM6oB9pIIzKisKHm.png',
    606 => 'products/June2024/CoTORGbnNn0JrSkNMugP.png',
    607 => 'products/June2024/wQaJkL1uR0FjO3gklb5U.png',
    608 => 'products/June2024/uzh3v7oFThLbwDVaDFTk.png',
    609 => 'products/June2024/mncJFtJXQZeXsOsmw0mO.png',
    610 => 'products/June2024/Cz1ANpmQAfVGPNgoLTxD.png',
    611 => 'products/June2024/zEwKZS5YnFZXBrInnmS1.png',
    613 => 'products/June2024/t1BC6VwQQFjyrZqkVBHT.png',
];

$count = 0;
$errors = [];

echo "Начинаем импорт фото в attachments...\n\n";

foreach ($photos as $productId => $oldPath) {
    try {
        // Проверяем существование товара
        $product = Product::find($productId);
        if (!$product) {
            $errors[] = "Товар ID {$productId} не найден";
            continue;
        }

        // Разбираем путь
        $pathParts = explode('/', $oldPath);
        $folder = $pathParts[1]; // May2023, September2022 и т.д.
        $filename = $pathParts[2];
        
        // Разбираем имя файла
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        
        // Проверяем, не привязано ли уже фото
        $exists = Attachment::where('attachmentable_id', $productId)
            ->where('name', $name)
            ->where('extension', $ext)
            ->exists();
            
        if ($exists) {
            echo "⚠ Фото уже есть: товар {$productId} - {$filename}\n";
            continue;
        }

        // Создаем запись в attachments
        $attachment = Attachment::create([
            'name' => $name,
            'original_name' => $filename,
            'extension' => $ext,
            'path' => 'products/' . $folder . '/',
            'attachmentable_id' => $productId,
            'attachmentable_type' => 'App\Models\Product',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $count++;
        echo "✓ Добавлено: товар {$productId} - {$filename}\n";

    } catch (\Exception $e) {
        $errors[] = "Ошибка для товара {$productId}: " . $e->getMessage();
    }
}

echo "\n--- ИТОГ ---\n";
echo "Добавлено фото: {$count}\n";
if (!empty($errors)) {
    echo "Ошибок: " . count($errors) . "\n";
    foreach ($errors as $error) {
        echo "  - {$error}\n";
    }
} else {
    echo "Ошибок: 0\n";
}