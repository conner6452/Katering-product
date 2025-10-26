<?php

namespace App\Notifications;

use App\Services\FirebaseService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PreOrderStatusNotification extends Notification
{
    use Queueable;

    protected $preOrder;
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
        return ['database', 'fcm'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        $name = $this->preOrder->supplier->name ?? 'Supplier';

    $title = 'Status Pre Order';
    $body = match($this->status) {
        'process' => "Pre-Order dari {$name} menunggu persetujuan.",
        'approved' => "Pre-Order dari {$name} disetujui.",
        'rejected' => "Pre-Order dari {$name} ditolak.",
        default => "Pre-Order diperbarui.",
    };

    if (!empty($notifiable->fcm_token)) {
        FirebaseService::sendNotification(
            $notifiable->fcm_token,
            $title,
            $body,
            ['pre_order_id' => $this->preOrder->id, 'status' => $this->status]
        );
    }

    return [
        'pre_order_id' => $this->preOrder->id,
        'title' => $title,
        'message' => $body,
        'status' => $this->status,
    ];
    }
    public function toFcm($notifiable)
    {
        $name = $this->preOrder->supplier->name ?? 'Supplier';
        $message = match($this->status){
            'process' => "Pre-Order dari {$name} menunggu persetujuan.",
            'done' => "Pre-Order dari {$name} disetujui"
        };
        return [
            'title' => 'Status Pre-Order',
            'message' => $message,
            'status' => $this->status,
            'pre_order_id' => $this->preOrder->id,
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
