<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Setting;
class SendMail extends Mailable implements ShouldQueue
{
    use Queueable;
    protected $settings;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $locale = app()->getLocale();
        $settings = $this->settings;
        // dd($settings);
        // $img = $this->data['image'];
        $title = $this->data['title_'.$locale];
        $des = $this->data['description_'.$locale];
        return $this->subject(__('theme::mail.lastest_news'))
        ->markdown('theme::front-end.emails.news.testmail', compact('des','title'));
        
        
    }
}
