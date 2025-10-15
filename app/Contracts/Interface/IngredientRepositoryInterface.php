<?php
namespace   App\Contracts\Interface;

use App\Models\Ingredient;
use Illuminate\Pagination\LengthAwarePaginator;
interface IngredientRepositoryInterface
{
    public function forceDelete(mixed $id): bool ;
    public function restore(mixed $id): bool ;
    public function update(mixed $id, array $data): Ingredient;
    public function delete(mixed $id): bool;
    public function store(array $data): Ingredient;
    public function trash(array $filters = []): LengthAwarePaginator;
    public function findById(mixed $id): ?Ingredient;
    public function paginate(int $pagination = 10):LengthAwarePaginator;
    public function getWithFilters(array $filters = []);
    public function findBySlug(string|int $slug): Ingredient;


}