<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Address;
use App\Models\History;

class EmailTemplate extends Mailable implements ShouldQueue
{
    use Queueable;

    public $message;


    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this
            ->from('demon@gmail.com')
            ->subject($this->history->subject)
            ->view('emails.template', [
                "data" => $this->message,
            ]);
    }
}
