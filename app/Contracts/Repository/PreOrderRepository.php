<?php

namespace App\Contracts\Repository;

use App\Contracts\Interface\PreOrderInterface;
use App\Helpers\QueryFilterHelper;
use App\Models\PreOrder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PreOrderRepository implements PreOrderInterface
{
    protected PreOrder $model;
    public function __construct(PreOrder $model)
    {
        $this->model = $model;
    }

    public function paginate(int $pagination = 10): LengthAwarePaginator
    {
        return $this->model->orderBy('created_at', 'desc')->paginate($pagination);
    }

    public function getWithFilters(array $filters = [])
    {
        $query = $this->model->newQuery();
        $searchColumns = [
            'supplier.name',
            'preOrderLists.ingredient.name',
            'total_price',
        ];

        QueryFilterHelper::applyFilters($query, $filters, $searchColumns);
        QueryFilterHelper::applySorting($query, $filters);

        return $query;
    }

    public function store(array $data): PreOrder
    {
        return DB::transaction(function () use ($data) {
            $preOrder = $this->model->create([
                'supplier_id' => $data['supplier_id'],
                'total_price' => 0,
                'status' => $data['status'] ?? 'process',
            ]);

            foreach ($data['items'] as $item) {
                $preOrder->preOrderLists()->create([
                    'ingredient_id' => $item['ingredient_id'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['total_price'],
                ]);
            }

            $sum = $preOrder->preOrderLists()->sum('total_price');
            $preOrder->update(['total_price' => $sum]);

            return $preOrder->load(['supplier', 'preOrderLists.ingredient']);
        });
    }

    public function findById(mixed $id): ?PreOrder
    {
        return $this->model
            ->with(['supplier', 'preOrderLists.ingredient'])
            ->where('id', $id)
            ->firstOrFail();
    }

    public function delete(mixed $id): bool
    {
        $preOrder = $this->findById($id);
        return $preOrder->delete();
    }

    public function forceDelete(mixed $id): bool
    {
        return $this->model->withTrashed()->findOrFail($id)->forceDelete();
    }

    public function restore(mixed $id): bool
    {
        return $this->model->onlyTrashed()->findOrFail($id)->restore();
    }

    public function trash(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->onlyTrashed();
        $searchColumns = [
            'supplier.name',
            'preOrderLists.ingredient.name',
            'total_price',
        ];

        QueryFilterHelper::applyFilters($query, $filters, $searchColumns);
        QueryFilterHelper::applySorting($query, $filters, 'deleted_at', 'desc');

        $perPage = (int) Arr::get($filters, 'per_page', 15);

        return $query->paginate($perPage);
    }

    public function searchTrashed(string $keyword, int $perPage = 15)
    {
        return $this->model->onlyTrashed()
            ->when($keyword, function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where(
                        'supplier.name',
                        'preOrderLists.ingredient.name',
                        'total_price',
                        'like',
                        "%{$keyword}%"
                    );
                });
            })
            ->orderBy('deleted_at', 'desc')
            ->paginate($perPage);
    }
}
