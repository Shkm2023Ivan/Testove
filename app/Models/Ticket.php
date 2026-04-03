<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    // Добавляем этот массив. 
    // Все поля, которые приходят из формы, должны быть тут перечислены.
    protected $fillable = ['customer_id', 'subject', 'message', 'status', 'admin_comment'];

    /**
     * Связь: Заявка принадлежит конкретному клиенту
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}