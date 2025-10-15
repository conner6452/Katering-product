<?php

namespace App\Http\Resources\Rule;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RuleResourcePaginate extends JsonResource
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
            'data' => RuleResource::collection($this->resource->items()),
            'paginate' => $this->pagination,
        ];
    }
}
