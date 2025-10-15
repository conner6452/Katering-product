<?php

namespace App\Contracts\Interface;

use App\Models\Rule;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RuleInterface
{
    public function paginate(int $pagination = 10): LengthAwarePaginator;
    public function store(array $data): Rule;
    public function findById(mixed $id): ?Rule;
    public function update(mixed $id, array $data): Rule;
    public function delete(mixed $id): bool;

    public function getWithFilters(array $filters = []);

    public function trash(array $filters = []): LengthAwarePaginator;
    public function restore(mixed $id): bool;
    public function forceDelete(mixed $id): bool;
}
