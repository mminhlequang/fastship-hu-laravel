<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class SendEmailQueue extends Mailable
{
    use Queueable, SerializesModels;

    protected $content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
        $this->subject($content['title']);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->content['data'];

        return $this->markdown($this->content['markdown']) //markdown la view chua noi dung gui mail
        ->with('data', $data);
    }

}
