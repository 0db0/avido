<?php

namespace App\Dto;

use App\Entity\User;

class ResetPasswordTokenDto
{
    public function __construct(private User $user, private string $token)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}