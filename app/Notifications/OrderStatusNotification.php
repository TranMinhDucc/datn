<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\PrivateChannel;


class OrderStatusNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $orderId;
    public $status;
    protected $order; // Thêm thuộc tính để lưu trữ order
    protected $notifiable; // Thêm dòng này
    public $cancelReason; // Thêm thuộc tính để lưu trữ lý do hủy
    public $image; // Thêm thuộc tính để lưu trữ hình ảnh

    public function __construct($orderId, $status, $order, $cancelReason = null, $image = null)
    {
        $this->orderId = $orderId;
        $this->status = $status;
        $this->order = $order; // Lưu trữ order để sử dụng trong
        $this->cancelReason = $cancelReason;
        $this->image = $image;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        // Lấy product từ order items
        $product = $this->order->items->first()->productVariant->product ?? null;
        return [
            'order_code' => $this->order->order_code,
            'order_id' => $this->orderId,
            'status' => $this->status,
            'cancel_reason_by_admin' => $this->order->cancel_reason_by_admin, // ✅ phải có dòng này
            'image' => 'storage/' . $product?->image,
            'url' => '/account/dashboard',
        ];
    }

    public function toBroadcast($notifiable)
    {
        // Gán notifiable vào thuộc tính để dùng sau
        $this->notifiable = $notifiable;

        return new BroadcastMessage($this->toArray($notifiable));
    }

    public function broadcastOn()
    {
        \Log::info('Broadcasting to user: ' . $this->notifiable->id);
        return new PrivateChannel('App.Models.User.' . $this->notifiable->id);
    }
}
