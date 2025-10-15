<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserResourcePaginate extends ResourceCollection
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
            'data' => UserCreateResource::collection($this->collection),
            'paginate' => $this->pagination,
        ];
    }
}
