<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class OrderSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Tạo instance mới cho mail.
     */
    public function __construct(Order $order)
    {
        $this->order = $order->load(['orderItems', 'orderItems.product', 'address', 'user', 'paymentMethod']);
    }

    /**
     * Build nội dung mail.
     */
    public function build()
    {
        // ✅ Tạo PDF từ Blade
        $pdf = PDF::loadView('invoices.pdf', ['order' => $this->order])
            ->setPaper('a4'); // khổ giấy A4

        return $this->subject('Xác nhận đơn hàng #' . $this->order->order_code . ' - Katie Shop')
            ->markdown('emails.orders.success')
            ->with([
                'order' => $this->order
            ])
            ->attachData(
                $pdf->output(),
                'hoa-don-' . $this->order->order_code . '.pdf',
                ['mime' => 'application/pdf']
            );
    }
}
