<?php

namespace App\Http\Resources\Rule;

use App\Http\Resources\RuleList\RuleListResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'capacity'    => $this->capacity,
            'budget'      => $this->budget,
            'description' => $this->description,
            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
            'deleted_at'  => $this->deleted_at?->toDateTimeString(),
        ];
    }
}
