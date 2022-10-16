<?php

namespace App\Email\Advert;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

final class ResetPasswordEmail extends TemplatedEmail
{
    public static function build(string $token, string $addressee): self
    {
        return (new self())
            ->to($addressee)
            ->htmlTemplate('emails.reset_password_email')
            ->context([
                'token' => $token,
            ]);
    }
}
