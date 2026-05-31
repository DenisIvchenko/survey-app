<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('polls', function (Blueprint $table) {
            // 🔹 Флаг тестового режима
            $table->boolean('is_test')->default(false)->after('is_completed');
            
            // 🔹 Настройки теста (хранятся как JSON)
            $table->json('test_settings')->nullable()->after('is_test');
            /*
            Пример содержимого test_settings:
            {
                "points_per_question": 10,
                "time_limit": 30,           // минут на весь тест
                "time_per_question": 60,    // секунд на вопрос
                "show_timer": true,
                "grading_scale": "five_point", // five_point | verbal | percent
                "passing_score": 60,        // проходной балл в %
                "grade_2_max": 59,
                "grade_3_min": 60, "grade_3_max": 74,
                "grade_4_min": 75, "grade_4_max": 89,
                "grade_5_min": 90,
                "shuffle_questions": false,
                "show_correct_answers": true,
                "one_attempt": false
            }
            */
        });
    }

    public function down(): void
    {
        Schema::table('polls', function (Blueprint $table) {
            $table->dropColumn(['is_test', 'test_settings']);
        });
    }
};