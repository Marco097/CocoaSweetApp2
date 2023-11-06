<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Mail\ExampleEmail;
use Illuminate\Support\Facades\Mail;

class ExampleTest extends TestCase
{
    public function testExampleEmail()
    {
        Mail::fake();

        $recipient = 'marco@gmail.com';
        $subject = 'Gracias';

        // Supongamos que estás enviando un correo con ExampleEmail
        Mail::to($recipient)->send(new ExampleEmail());

        // Verifica que se haya enviado el correo a la dirección especificada
        Mail::assertSent(ExampleEmail::class, function ($mail) use ($recipient, $subject) {
            return $mail->hasTo($recipient) && $mail->subject($subject);
        });
    }
}
