<?php
namespace App\Observers;

use App\Models\Supplier;
use Illuminate\Support\Str;

class SupplierObserver{
    public function generateUniqueSlug(Supplier $Supplier):string{
        $slug = Str::slug($Supplier->name);
        
        $slug .= '-' . date('YmdHis');
        return $slug;
    }
   public function creating(Supplier $Supplier)
    {
        if (empty($Supplier->slug)) {
            $Supplier->slug = $this->generateUniqueSlug($Supplier);
        }
    }

    public function updating(Supplier $Supplier)
    {
        if ($Supplier->isDirty('name') || empty($Supplier->slug)) {
            $Supplier->slug = $this->generateUniqueSlug($Supplier);
        }
    }
}