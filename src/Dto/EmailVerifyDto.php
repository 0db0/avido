<?php

namespace App\Dto;

use App\Entity\EmailVerification;
use App\Validator\CodeNotExpired as CustomAssert;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Validator\Constraints as Assert;

final class EmailVerifyDto
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 60)]
    #[CustomAssert\CodeNotExpired]
    private string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}