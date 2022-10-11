<?php

namespace App\Dto\Request\User;

use App\Enum\UserRole;

final class CreateUserDto extends AbstractCreateUserDto
{
    private string $role;

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $role
    ) {
        $this->role = $role;

        parent::__construct($firstName, $lastName, $email);
    }


    public function getRole(): ?UserRole
    {
        return UserRole::tryFrom($this->role);
    }
}
