<?php
namespace App\Contracts\Repository;

use App\Contracts\Interface\NotificationInterface;
use Illuminate\Database\Eloquent\Collection;
use Notification;

class NotificationRepository implements NotificationInterface
{
    public function getAll(string $userId): Collection
    {
        return Notification::where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get();
    }
}