<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // 🔹 Результаты попытки
            $table->integer('total_score')->default(0);        // набранные баллы
            $table->integer('max_score')->default(0);          // максимально возможные
            $table->string('final_grade')->nullable();         // "5", "Зачёт", "85%"
            $table->boolean('is_passed')->default(false);      // сдал/не сдал
            
            // 🔹 Временные метки
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->integer('time_spent')->nullable();         // общее время в секундах
            
            // 🔹 Метаданные
            $table->string('user_agent')->nullable();          // браузер/устройство
            $table->ipAddress('ip_address')->nullable();       // IP пользователя
            
            $table->timestamps();
            
            // 🔹 Индексы для быстрых запросов
            $table->index(['poll_id', 'user_id']);
            $table->index('finished_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_attempts');
    }
};