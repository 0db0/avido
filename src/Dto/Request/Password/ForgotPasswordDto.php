<?php

namespace App\Dto\Request\Password;

use Symfony\Component\Validator\Constraints as Assert;

final class ForgotPasswordDto
{
    public function __construct(private string $email)
    {
    }

    #[Assert\NotBlank]
    #[Assert\Email]
    public function getEmail(): string
    {
        return $this->email;
    }
}
