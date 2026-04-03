<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Превращаем модель в массив для ответа.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'subject'    => $this->subject,
            'status'     => $this->status,
            'message'    => $this->message,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}