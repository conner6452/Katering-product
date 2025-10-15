<?php

namespace App\Contracts\Interface;

use App\Models\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TransactionInterface
{
    public function paginate(int $pagination = 10): LengthAwarePaginator;
    // public function store(array $data): Transaction;
    public function findById(mixed $id): ?Transaction;
    // public function update(mixed $id, array $data): Transaction;
    public function delete(mixed $id): bool;

    public function getWithFilters(array $filters = []);

    public function trash(array $filters = []): LengthAwarePaginator;
    public function restore(mixed $id): bool;
    public function forceDelete(mixed $id): bool;
}
