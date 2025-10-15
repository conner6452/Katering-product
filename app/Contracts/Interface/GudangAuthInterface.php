<?php

namespace App\Contracts\Interface;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface GudangAuthInterface
{
    public function getAllGudang(array $filters = []): LengthAwarePaginator;
    public function findByIdGudang(mixed $id): ?User;
    public function storeGudang(array $data): User;
    public function updateGudang(mixed $id, array $data): User;
    public function deleteGudang(mixed $id): bool;
}
