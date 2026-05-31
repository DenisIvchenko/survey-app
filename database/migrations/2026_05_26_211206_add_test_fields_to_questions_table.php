<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // 🔹 Баллы за правильный ответ на этот вопрос
            $table->integer('points')->default(10)->after('sort_order');
            
            // 🔹 Индексы правильных ответов (для radio/checkbox)
            // Пример: [0] для первого варианта, [0,2] для множественного выбора
            $table->json('correct_answers')->nullable()->after('points');
            
            // 🔹 Ограничение времени на этот вопрос (в секундах)
            // null = использовать общее время теста
            $table->integer('time_limit')->nullable()->after('correct_answers');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['points', 'correct_answers', 'time_limit']);
        });
    }
};