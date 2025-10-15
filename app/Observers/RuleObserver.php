<?php

namespace App\Observers;

use App\Models\Rule;
use Illuminate\Support\Str;

class RuleObserver
{
    public function generateUniqueSlug(Rule $rule): string
    {
        $slug = Str::slug($rule->name);

        $slug .= '-' . date('YmdHis');
        return $slug;
    }
    public function creating(Rule $rule)
    {
        if (empty($rule->slug)) {
            $rule->slug = $this->generateUniqueSlug($rule);
        }
    }

    public function updating(Rule $rule)
    {
        if ($rule->isDirty('name') || empty($Ingredient->slug)) {
            $rule->slug = $this->generateUniqueSlug($rule);
        }
    }
}
