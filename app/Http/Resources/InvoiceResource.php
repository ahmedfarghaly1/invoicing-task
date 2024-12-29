<?php

namespace App\Http\Resources;

use App\Services\InvoiceService;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'invoice_id' => $this->id,
            'customer' => $this->customer->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'total_price' => $this->total_price,
            'event_frequency' => (new InvoiceService())->calculateEventFrequency($this->resource),
            'events' => $this->items->map(function ($item) {
                return [
                    'user_email' => $item->user->email,
                    'event_type' => $item->event_type,
                    'event_price' => $item->event_price,
                ];
            }),
        ];
    }
}
