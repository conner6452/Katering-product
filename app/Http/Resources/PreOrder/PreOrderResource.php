<?php

namespace App\Http\Resources\PreOrder;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreOrderResource extends JsonResource
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
            'supplier' => [
                'id' => $this->supplier_id,
                'name' => $this->supplier?->name,
            ],
            'items' => $this->preOrderLists->map(function ($item) {
                return [
                    'id' => $item->id,
                    'ingredient_id' => $item->ingredient_id,
                    'ingredient_name' => $item->ingredient?->name,
                    'ingredient_price' => $item->ingredient?->price,
                    'quantity' => $item->quantity,
                    'total_price' => $item->total_price,
                ];
            }),
            'total_price' => $this->total_price,
            'status' => $this->status,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
