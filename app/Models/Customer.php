<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; // <-- УБЕДИСЬ, ЧТО ЭТА СТРОКА ЕСТЬ

class Customer extends Model
{
    use Notifiable; // Трейт теперь будет подтягиваться из Illuminate\Notifications

    protected $fillable = ['name', 'email', 'phone'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}