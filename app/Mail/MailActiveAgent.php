<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailActiveAgent extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    protected $agentRegister;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($agentRegister)
    {
        $this->agentRegister = $agentRegister;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $agentRegister = $this->agentRegister;
        return $this->markdown('emails.active.agent', compact('agentRegister'));
//        return $this->view('emails.active.agent', compact('agentRegister', 'admin'));
    }
}
