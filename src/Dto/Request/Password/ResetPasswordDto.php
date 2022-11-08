<?php

namespace App\Dto\Request\Password;

use Symfony\Component\Validator\Constraints as Assert;

final class ResetPasswordDto
{
    #[Assert\NotBlank]
    #[Assert\Email]

    public function __construct(
        private string $email,
        private string $token,
        private string $password,
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
