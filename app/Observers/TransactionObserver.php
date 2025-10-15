<?php

namespace App\Observers;

use App\Models\Transaction;
use Illuminate\Support\Str;

class TransactionObserver
{
    protected function generateUniqueSlug(Transaction $transaction): string
    {
        $userName = $transaction->user?->name ?? 'unknown';
        $slug = Str::slug($userName);
        $slug .= '-' . date('YmdHis');
        return $slug;
    }

    public function creating(Transaction $transaction)
    {
        // Custom UUID
        if (empty($transaction->custom_id)) {
            $transaction->custom_id = 'TRX-' . now()->format('YmdHis') . '-' . Str::uuid();
        }
        // Slug
        if (empty($transaction->slug)) {
            $transaction->slug = $this->generateUniqueSlug($transaction);
        }
    }

    public function updating(Transaction $transaction)
    {
        if ($transaction->isDirty('name') || empty($transaction->slug)) {
            $transaction->slug = $this->generateUniqueSlug($transaction);
        }
    }
}
