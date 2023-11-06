<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExampleEmail extends Mailable
{
    use SerializesModels;

    public function build()
    {
        return $this->view('emails.example')
                   ->with([
                       'message' => 'Este es un ejemplo de correo electrónico en Laravel.',
                   ]);
    }
}
