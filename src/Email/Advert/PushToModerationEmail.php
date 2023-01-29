<?php

namespace App\Email\Advert;

use App\Entity\Advert;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

final class PushToModerationEmail extends TemplatedEmail
{
    public static function build(Advert $advert, string $addressee): self
    {
        return (new self())
            ->to($addressee)
            ->htmlTemplate('emails.advert.push_to_moderation.html.twig')
            ->context([
                'author' => $advert->getAuthor(),
                'advert' => $advert,
            ]);
    }
}
