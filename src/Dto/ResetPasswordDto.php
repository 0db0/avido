<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class ResetPasswordDto
{
    #[Assert\NotBlank]
    #[Assert\Email]

    public function __construct(private string $email)
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}