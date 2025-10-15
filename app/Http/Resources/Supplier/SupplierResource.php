<?php

namespace App\Http\Resources\Supplier;

use App\Http\Resources\Ingredient\IngredientResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'company_detail'       => $this->company_detail,
            'transaction_history' => $this->null,//$this->transaction()->count(),
            'slug' => $this->slug,
            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
            'deleted_at'  => $this->deleted_at?->toDateTimeString(),
            'ingredient' => new IngredientResource($this->whenLoaded('ingredient')),

        ];
    }
}
