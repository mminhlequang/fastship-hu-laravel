<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Booking\Entities\Customer;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailOrderCustomer extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    protected $order, $booking, $settings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($booking, $order, $settings)
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
        return $this->markdown('theme::front-end.emails.orders.order', compact('booking','order','settings'))->subject($settings['company_website']. ': Xác nhận đơn hàng #' . $booking->code);
    }
}
