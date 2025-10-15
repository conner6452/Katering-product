<?php

namespace App\Observers;

use App\Models\PreOrder;
use App\Notifications\PreOrderStatusNotification;

class PreOrderObserver
{
    /**
     * Handle the PreOrder "created" event.
     */
    public function created(PreOrder $preOrder): void
    {
        //
    }

    /**
     * Handle the PreOrder "updated" event.
     */
    public function updated(PreOrder $preOrder): void
    {
        if ($preOrder->isDirty('status')) {
            $preOrder->supplier->notify(new PreOrderStatusNotification($preOrder));
        }
    }


}
