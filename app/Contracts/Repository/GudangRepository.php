<?php
namespace App\Contracts\Repository;

use App\Helpers\QueryFilterHelper;
use App\Models\Gudang;
use App\Models\Ingredient;
use Illuminate\Pagination\LengthAwarePaginator;

class GudangRepository
{
    protected Gudang $model;
    public function __construct(Gudang $model)
    {
        $this->model = $model;
    }

    public function paginate(int $pagination = 10):LengthAwarePaginator
    {
        return $this->model->orderBy('created_at', 'desc')->paginate($pagination);
    }

    public function findById(mixed $id): ?Gudang
    {
        return $this->model->where('id', $id )->firstOrFail();
    }
}