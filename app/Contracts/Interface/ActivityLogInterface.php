<?php

namespace App\Contracts\Interface;

interface ActivityLogInterface
{
    public function log(array $data): void;
}
