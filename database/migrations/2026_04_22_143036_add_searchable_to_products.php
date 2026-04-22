<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Добавляем полнотекстовый индекс для поиска
            $table->fullText(['name', 'description', 'short_description', 'sku', 'article'], 'products_search_fulltext');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropFullText('products_search_fulltext');
        });
    }
};
