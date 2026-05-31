<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            // 🔹 Полученные баллы за этот ответ (0 или points из вопроса)
            $table->integer('score')->default(0)->after('text_value');
            
            // 🔹 Был ли ответ правильным (для быстрой фильтрации)
            $table->boolean('is_correct')->default(false)->after('score');
            
            // 🔹 Время, затраченное на ответ (в секундах)
            $table->integer('time_spent')->nullable()->after('is_correct');
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropColumn(['score', 'is_correct', 'time_spent']);
        });
    }
};