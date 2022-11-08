<?php

namespace App\Email\Verification;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

final class VerificationEmail extends TemplatedEmail
{
    public static function build(string $code, string $addressee): self
    {
        return (new self())
            ->to($addressee)
            ->htmlTemplate('emails/confirm_email.html.twig')
            ->context([
                'code' => $code,
            ]);
    }
}
