<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        // Внешний ключ на таблицу клиентов
        $table->foreignId('customer_id')->constrained()->onDelete('cascade');
        
        $table->string('subject'); // Тема
        $table->text('message');   // Текст
        
        // Статус: по умолчанию 'new'
        $table->enum('status', ['new', 'in_progress', 'resolved'])->default('new');
        
        $table->timestamp('manager_responded_at')->nullable(); // Дата ответа менеджера
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
