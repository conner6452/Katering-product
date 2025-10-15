<?php

namespace App\Observers;

use App\Models\Ingredient;
use Illuminate\Support\Str;

class IngredientObserver
{
    public function generateUniqueSlug(Ingredient $Ingredient): string
    {
        $slug = Str::slug($Ingredient->name);

        $slug .= '-' . date('YmdHis');
        return $slug;
    }
    public function creating(Ingredient $Ingredient)
    {
        if (empty($Ingredient->slug)) {
            $Ingredient->slug = $this->generateUniqueSlug($Ingredient);
        }
    }

    public function updating(Ingredient $Ingredient)
    {
        if ($Ingredient->isDirty('name') || empty($Ingredient->slug)) {
            $Ingredient->slug = $this->generateUniqueSlug($Ingredient);
        }
    }
}
