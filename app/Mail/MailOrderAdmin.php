<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailOrderAdmin extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    protected $order, $booking, $settings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($booking,$order,$settings)
    {
        $this->booking = $booking;
        $this->order = $order;
        $this->settings = $settings;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $booking = $this->booking;
        $order = $this->order;
        $settings = $this->settings;
        return $this->markdown('theme::front-end.emails.orders.admin', compact('booking','order','settings'))->subject('Khách hàng ' . optional($booking->customer)->name . ' đã đặt hàng #' . $booking->code);
    }
}
