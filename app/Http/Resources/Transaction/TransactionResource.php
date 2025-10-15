<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'custom_id' => $this->custom_id,
            'user' => [
                'id' => $this->user_id,
                'name' => $this->user?->name,
            ],
            'delivery_order' => [
                'id' => $this->delivery_order_id,
                'driver_name' => $this->deliveryOrder?->user?->name
            ],
            'created_at' => $this->created_at?->toDateTimeString(),
            'transaction_items' => $this->transactionItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'ingredient_id' => $item->ingredient_id,
                    'ingredient_name' => $item->ingredient?->name,
                    'ingredient_price' => $item->ingredient?->price,
                    'quantity' => $item->quantity,
                    'total_price' => $item->total_price
                ];
            }),
            'total_price' => $this->total_price,
        ];
    }
}
