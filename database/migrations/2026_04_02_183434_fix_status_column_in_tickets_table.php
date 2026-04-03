<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Мы меняем тип колонки на обычную строку, тем самым удаляя старый CHECK constraint
            $table->string('status')->default('NEW')->change();
        });
    }

    public function down(): void
    {
        // Возвращать назад не будем, так как это ломает SQLite
    }
};