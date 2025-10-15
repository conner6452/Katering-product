<?php

namespace App\Http\Resources\PreOrder;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreOrderResourcePaginate extends JsonResource
{
    protected $pagination;

    public function __construct($resource, $pagination)
    {
        parent::__construct($resource);
        $this->pagination = $pagination;
    }

    public function toArray(Request $request): array
    {
        return [
            'data' => PreOrderResource::collection($this->resource->items()),
            'paginate' => $this->pagination,
        ];
    }
}
