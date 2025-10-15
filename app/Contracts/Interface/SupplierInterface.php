<?php
namespace   App\Contracts\Interface;

use App\Models\Ingredient;
use App\Models\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;
interface SupplierInterface
{
    public function forceDelete(mixed $id): bool ;
    public function restore(mixed $id): bool ;
    public function update(mixed $id, array $data): Supplier;
    public function delete(mixed $id): bool;
    public function store(array $data): Supplier;
    public function trash(array $filters = []): LengthAwarePaginator;
    public function findById(mixed $id): ?Supplier;
    public function paginate(int $pagination = 10):LengthAwarePaginator;
    public function getWithFilters(array $filters = []);
    public function findBySlug(string|int $slug): Supplier;


}