<?php

namespace App\Contracts\Repository;

use App\Contracts\Interface\TransactionInterface;
use App\Helpers\QueryFilterHelper;
use App\Models\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class TransactionRepository implements TransactionInterface
{
    protected Transaction $model;
    public function __construct(Transaction $model)
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
        $searchColumns = ['user.name', 'custom_id'];

        QueryFilterHelper::applyFilters($query, $filters, $searchColumns);
        QueryFilterHelper::applySorting($query, $filters);

        return $query;
    }

    public function findById(mixed $id): ?Transaction
    {
        return $this->model->with(['user', 'deliveryOrder.user', 'transactionItems.ingredient'])->where('id', $id)->firstOrFail();
    }

    public function delete(mixed $id): bool
    {
        $rule = $this->findById($id);
        return $rule->delete();
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
        $searchColumns = ['user.name'];

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
                    $q->where('user.name', 'like', "%{$keyword}%");
                });
            })
            ->orderBy('deleted_at', 'desc')
            ->paginate($perPage);
    }
}
