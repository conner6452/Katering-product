<?php

namespace App\Contracts\Interface;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DriverAuthInterface
{
    public function getAllDriver(array $filters = []): LengthAwarePaginator;
    public function findByIdDriver(mixed $id): ?User;
    public function storeDriver(array $data): User;
    public function updateDriver(mixed $id, array $data): User;
    public function deleteDriver(mixed $id): bool;
}
