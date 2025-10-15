<?php

namespace App\Contracts\Interface;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface AuthInterface extends DriverAuthInterface, GudangAuthInterface
{
    public function loginAdmin(array $data);

    public function logout();

    public function forgotPassword(string $email);

    public function updateForgotPassword(array $data);

    public function loginMobile(array $data);
    public function paginate(int $pagination = 10): LengthAwarePaginator;
    public function findById(mixed $id): ?User;
    public function store(array $data): User;
    public function update(mixed $id, array $data): User;
    public function delete(mixed $id): bool;
    public function forceDelete(mixed $id): bool;
    public function restore(mixed $id): bool;
    public function trash(array $filters = []): LengthAwarePaginator;
    public function searchTrashed(string $keyword, int $perPage = 10);
    public function getAll(array $filters = []): LengthAwarePaginator;
}
