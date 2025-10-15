<?php

namespace App\Contracts\Repository;

use App\Contracts\Interface\ActivityLogInterface;
use App\Models\ActivityLog;

class ActivityLogRepository implements ActivityLogInterface
{
    public function log(array $data): void
    {
        ActivityLog::create($data);
    }
}
