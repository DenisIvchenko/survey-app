<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            // Композитный индекс: ускоряет WHERE + ORDER BY
            $table->index(['poll_id', 'sort_order'], 'idx_questions_poll_sort');
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex('idx_questions_poll_sort');
        });
    }
};