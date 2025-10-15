<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResourcePaginate extends JsonResource
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
            'data' => TransactionResource::collection($this->resource->items()),
            'paginate' => $this->pagination,
        ];
    }
}
