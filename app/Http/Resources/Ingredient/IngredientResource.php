<?php

namespace App\Http\Resources\Ingredient;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IngredientResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'stock'       => $this->stock . $this->detail,
            'harga'       => $this->price,
            'image'       => $this->image,
            'slug' => $this->slug,
            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
            'deleted_at'  => $this->deleted_at?->toDateTimeString(),
        ];
    }
}
