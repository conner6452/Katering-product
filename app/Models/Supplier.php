<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes, HasUuids, HasFactory;

    protected $fillable =[
        'name', 'contact', 'company_detail', 'ingredient_id','slug'
    ];

    public function transaction()
    {
        //return $this->hasMany(Transaction::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

}
