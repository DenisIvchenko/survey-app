<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            
            //  1. Вопрос: внешняя ссылка (ОБЯЗАТЕЛЬНА)
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            
            //  2. answer_id: это ИНДЕКС в массиве options (JSON), НЕ внешняя ссылка!
            $table->unsignedInteger('answer_id')->nullable();
            
            //  3. text_value: для текстовых ответов и путей к файлам
            $table->text('text_value')->nullable();
            
            //  4. user_id: привязка к авторизованному пользователю
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            //  5. IP-адрес респондента
            $table->ipAddress('voter_ip');
            
            $table->timestamps();
            
            // Индексы
            $table->index(['question_id', 'answer_id']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};