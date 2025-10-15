<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class PreOrder extends Model
{
    use HasUuids, SoftDeletes, HasFactory, Notifiable;

    protected $table = 'pre_orders';

    protected $fillable = [
        'supplier_id',
        'total_price',
        'status',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function preOrderLists(): HasMany
    {
        return $this->hasMany(PreOrderList::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'related_id');
    }

}
