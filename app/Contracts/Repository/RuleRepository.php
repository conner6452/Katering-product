<?php

namespace App\Contracts\Repository;

use App\Contracts\Interface\RuleInterface;
use App\Helpers\QueryFilterHelper;
use App\Models\Rule;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class RuleRepository implements RuleInterface
{
    protected Rule $model;
    public function __construct(Rule $model)
    {
        $this->model = $model;
    }

    public function paginate(int $pagination = 10): LengthAwarePaginator
    {
        return $this->model->orderBy('created_at', 'desc')->paginate($pagination);
    }

    // public function search(string $keyword, int $perPage = 10)
    // {
    //     return $this->model
    //         ->when($keyword, function ($query, $keyword) {
    //             $query->where(function ($q) use ($keyword) {
    //                 $q->where('name', 'like', "%{$keyword}%");
    //             });
    //         })
    //         ->orderBy('created_at', 'desc')
    //         ->paginate($perPage);
    // }

    public function getWithFilters(array $filters = [])
    {
        $query = $this->model->newQuery();
        $searchColumns = ['capacity', 'budget'];

        QueryFilterHelper::applyFilters($query, $filters, $searchColumns);
        QueryFilterHelper::applySorting($query, $filters);

        return $query;
    }

    public function store(array $data): Rule
    {
        return $this->model->create($data);
    }

    public function findById(mixed $id): ?Rule
    {
        return $this->model->where('id', $id)->firstOrFail();
    }

    public function update(mixed $id, array $data): Rule
    {
        $rule = $this->findById($id);
        $rule->update($data);
        return $rule->fresh();
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
        $searchColumns = ['name'];

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
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->orderBy('deleted_at', 'desc')
            ->paginate($perPage);
    }
}
