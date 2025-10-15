<?php

namespace App\Traits;

use Carbon\Carbon;

trait HasFormattedTimestamps
{
    public function getCreatedAtAttribute($value) // ambil kolom CreatedAt
    {
        return Carbon::parse($value)
        ->setTimezone('Asia/Jakarta')
        ->translatedFormat('Y-F-d');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)
        ->setTimezone('Asia/Jakarta')
        ->translatedFormat('Y-F-d');
    }

    public function getDeletedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)
        ->setTimezone('Asia/Jakarta')
        ->translatedFormat('Y-F-d') : null;
    }
}
