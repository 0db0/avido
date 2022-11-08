<?php

namespace App\Email\Password;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

final class SetupPasswordEmail extends TemplatedEmail
{
    public static function build(string $token, string $addressee): self
    {
        return (new self())
            ->to($addressee)
            ->htmlTemplate('emails/setup_password_email.html.twig')
            ->context([
                'token' => $token,
            ]);
    }
}
