<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
    Schema::table('questions', function (Blueprint $table) {
        // Добавляем поля БЕЗ after() — так надёжнее
        $table->json('options')->nullable(); 
        $table->integer('sort_order')->default(0);
    });
    }

    public function down(): void
    {
    Schema::table('questions', function (Blueprint $table) {
        $table->dropColumn(['options', 'sort_order']);
    });
    }
};