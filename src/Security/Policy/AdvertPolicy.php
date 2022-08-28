<?php

namespace App\Security\Policy;

use App\Entity\Advert;
use App\Entity\User;
use App\Enum\UserRole;
use App\Enum\UserStatus;
use Symfony\Component\Security\Core\Security;

final class AdvertPolicy
{
    public function __construct(
        private readonly Security $security
    ) {
    }

    public function canCreate(User $user): bool
    {
        if (! $this->security->isGranted(UserRole::User->value)) {
            return false;
        }

        return $user->getStatus() === UserStatus::Active;
    }

    public function canList(User $user): bool
    {
        return $user->getStatus() === UserStatus::Active;
    }

    public function canShow(User $user, Advert $advert): bool
    {
        if ($this->security->isGranted([UserRole::Admin->value, UserRole::Moderator->value])) {
            return true;
        }

        return $user->getStatus() === UserStatus::Active
            && $advert->getSeller()->getId() === $user->getId();
    }

    public function canEdit(User $user, Advert $advert): bool
    {
        return $user->getStatus() === UserStatus::Active
            && $advert->getSeller()->getId() === $user->getId();
    }
}
