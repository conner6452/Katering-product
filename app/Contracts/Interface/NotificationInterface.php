<?php
namespace App\Contracts\Interface;

use Illuminate\Database\Eloquent\Collection;
interface NotificationInterface
{
    public function getAll(string $userId): Collection;
}