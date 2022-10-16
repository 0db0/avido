<?php

namespace App\Email\Password;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

final class SetupPasswordEmail extends TemplatedEmail
{
    public static function build(string $url, string $addressee): self
    {
        return (new self())
            ->to($addressee)
            ->htmlTemplate('emails.reset_password_email')
            ->context([
                'setupPasswordUrl' => $url,
            ]);
    }
}
