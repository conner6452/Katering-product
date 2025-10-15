<?php
namespace App\Contracts\Repository;

use App\Contracts\Interface\SupplierInterface;
use App\Helpers\QueryFilterHelper;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Pagination\LengthAwarePaginator;

class SupplierRepository implements SupplierInterface
{
    protected Supplier $model ;
    public function getWithFilters(array $filters = [])
    {
        $query = $this->model->newQuery()->with('ingredient');
        $searchColumns = ['name', 'contact', 'company_detail', 'ingredient_id'];

        QueryFilterHelper::applyFilters($query, $filters, $searchColumns);
        QueryFilterHelper::applySorting($query, $filters);

        return $query;
    }
    public function paginate(int $pagination = 10):LengthAwarePaginator
    {
        return $this->model->orderBy('created_at', 'desc')->paginate($pagination);
    }

    public function __construct(Supplier $model)
    {
        $this->model = $model;
    }
    public function findById(mixed $id): ?Supplier
    {
        return $this->model->with('ingredient')->where('id', $id)->firstOrFail();
    }
        public function findBySlug(string|int $slug): Supplier
    {
        $query = $this->model->newQuery()->with('ingredient');
        $model = $query->where('slug', $slug)->first();
        if (!$model) {
            throw new ModelNotFoundException('Supplier not found');
        }

        return $model;
    }   
    public function store(array $data): Supplier
    {
        $supplier = $this->model->create($data);
        return $supplier->load('ingredient');

    }

    public function update(mixed $id, array $data): Supplier
    {
        $Supplier = $this->findById($id);
        $Supplier->update($data);
        return $Supplier->fresh(['ingredient']);
    }

    public function delete(mixed $id): bool
    {
        $Supplier = $this->findById($id);
        return $Supplier->delete();
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
        $query = $this->model->onlyTrashed()->with('ingredient');
        $searchColumns = ['name'];

        QueryFilterHelper::applyFilters($query, $filters, $searchColumns);
        QueryFilterHelper::applySorting($query, $filters, 'deleted_at', 'desc');

        $perPage = (int) Arr::get($filters, 'per_page', 10);

        return $query->paginate($perPage);
    }
    public function searchTrashed(string $keyword, int $perPage = 10)
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