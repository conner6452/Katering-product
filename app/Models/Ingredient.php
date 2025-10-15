<?php

namespace App\Models;

use App\Traits\HasFormattedTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{

    use SoftDeletes, HasUuids, HasFactory;

    protected $fillable = [
        'name',
        'stock',
        'detail',
        'slug',
        'harga',
        'image',
        'price',
    ];

    protected $casts = [
        'stock' => 'integer',
    ];

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
    public function graphic()
    {
        return $this->hasMany(Gudang::class);
    }
}
