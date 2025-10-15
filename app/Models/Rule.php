<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rule extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'rules';

    protected $fillable = [
        'name',
        'capacity',
        'slug',
        'budget',
        'description',
    ];
}
