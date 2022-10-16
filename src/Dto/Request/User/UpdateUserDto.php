<?php

namespace App\Dto\Request\User;

use App\Enum\UserRole;

final class UpdateUserDto extends AbstractCreateUserDto
{
    public readonly string $patronymic;
    public readonly string $whenConvenientReceiveCalls;
    private string $role;

    public function __construct(
        string $firstName,
        string $lastName,
        string $patronymic,
        string $email,
        string $whenConvenientReceiveCalls,
        string $role
    ) {
        $this->role = $role;

        parent::__construct($firstName, $lastName, $email);
        $this->patronymic = $patronymic;
        $this->whenConvenientReceiveCalls = $whenConvenientReceiveCalls;
    }


    public function getRole(): ?UserRole
    {
        return UserRole::tryFrom($this->role);
    }
}
