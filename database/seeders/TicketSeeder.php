<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Ticket;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем тестового клиента
        $customer = Customer::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Тестовый Пользователь', 'phone' => '+380000000000']
        );

        // Создаем несколько тикетов для него
        Ticket::create([
            'customer_id' => $customer->id,
            'subject' => 'Проблема с авторизацией',
            'message' => 'Не могу зайти в личный кабинет, выдает ошибку 500.',
            'status' => 'NEW',
        ]);

        Ticket::create([
            'customer_id' => $customer->id,
            'subject' => 'Вопрос по оплате',
            'message' => 'Когда придет квитанция за март?',
            'status' => 'CLOSED',
            'admin_comment' => 'Квитанция отправлена на почту.',
        ]);
    }
}