<?php

namespace App\Dto\Request\Advert;

use App\Dto\Request\RequestDtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AdvertModerationDeclineDto implements RequestDtoInterface
{
    #[Assert\When()]
    #[Assert\Type('string')]
    public readonly string|null $note;

    public function __construct(?string $note)
    {
        $this->note = $note;
    }
}
