<?php

namespace App\Contracts\Interface;

use App\Models\PreOrder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PreOrderInterface
{
    public function paginate(int $pagination = 10): LengthAwarePaginator;
    public function store(array $data): PreOrder;
    public function findById(mixed $id): ?PreOrder;
    // public function update(mixed $id, array $data): PreOrder;
    public function delete(mixed $id): bool;

    public function getWithFilters(array $filters = []);

    public function trash(array $filters = []): LengthAwarePaginator;
    public function restore(mixed $id): bool;
    public function forceDelete(mixed $id): bool;
}
