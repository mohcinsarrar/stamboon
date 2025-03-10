<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactEmail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $name, $email, $object, $content;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $object, $content)
    {
        $this->name = $name;
        $this->email = $email;
        $this->object = $object;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.contact')->subject('Email du contact');
    }
}