<?php
namespace App\Contracts\Repository;

use App\Contracts\Interface\IngredientRepositoryInterface;
use App\Helpers\QueryFilterHelper;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Pagination\LengthAwarePaginator;

class IngredientRepository implements IngredientRepositoryInterface
{
    protected Ingredient $model ;
    public function getWithFilters(array $filters = [])
    {
        $query = $this->model->newQuery();
        $searchColumns = ['name', 'stock', 'detail'];

        QueryFilterHelper::applyFilters($query, $filters, $searchColumns);
        QueryFilterHelper::applySorting($query, $filters);

        return $query;
    }
    public function paginate(int $pagination = 10):LengthAwarePaginator
    {
        return $this->model->orderBy('created_at', 'desc')->paginate($pagination);
    }
    public function findBySlug(string|int $slug): Ingredient
    {
        $query = $this->model->newQuery();
        $model = $query->where('slug', $slug)->first();
        if (!$model) {
            throw new ModelNotFoundException('Ingredient not found');
        }

        return $model;
    }

    public function __construct(Ingredient $model)
    {
        $this->model = $model;
    }
    public function findById(mixed $id): ?Ingredient
    {
        return $this->model->where('id', $id )->firstOrFail();
    }
    public function store(array $data): Ingredient
    {
        return $this->model->create($data);
    }

    public function update(mixed $id, array $data): Ingredient
    {
        $ingredient = $this->findById($id);
        $ingredient->update($data);
        return $ingredient->fresh();
    }

    public function delete(mixed $id): bool
    {
        $ingredient = $this->findById($id);
        return $ingredient->delete();
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