<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    protected $fillable = [
        'date', 'ingredient_id'
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
