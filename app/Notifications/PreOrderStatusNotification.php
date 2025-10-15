<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PreOrderStatusNotification extends Notification
{
    use Queueable;

    protected $po;
    protected $status;
    /**
     * Create a new notification instance.
     */
    public function __construct($preOrder)
    {
        $this->preOrder = $preOrder;
        $this->status = $preOrder->status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        $name = $this->preOrder->supplier->name ?? 'Supplier';
        $title = '';
        $message = '';

        switch($this->status){
            case 'process':
                $title = 'Menunggu Persetujuan';
                $message = "Pre-Order dari {$name} sedang menunggu persetujuan";
                break;

            case 'approved':
                $title = 'Pre Order Disetujui';
                $message = "Pre Order dari {$name} telah disetujui.";
                break;
        }
        return [
            'pre_order_id' => $this->preOrder->id,
            'title' => $title,
            'message' => $message,
            'status' => $this->status,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
