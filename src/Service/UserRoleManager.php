<?php

namespace App\Service;

use App\Entity\User;
use App\Enum\UserRole;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserRoleManager
{
    public function hasRole(User|UserInterface $user, UserRole $role): bool
    {
        return in_array($role->value, $user->getRoles(), true);
    }
}