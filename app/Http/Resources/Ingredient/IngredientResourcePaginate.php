<?php

namespace App\Http\Resources\Ingredient;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class IngredientResourcePaginate extends ResourceCollection
{
    protected $pagination;

    public function __construct($resource, $pagination)
    {
        parent::__construct($resource);
        $this->pagination = $pagination;
    }

    /**
     * Transform the resource collection into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => IngredientResource::collection($this->collection),
            'paginate' => $this->pagination,
        ];
    }
}
