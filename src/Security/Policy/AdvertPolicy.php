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
        if ($user->getStatus() !== UserStatus::Active) {
            return false;
        }

        return $this->security->isGranted(UserRole::User->value);
    }

    public function canList(User $user): bool
    {
        return $user->getStatus() === UserStatus::Active;
    }

    public function canShow(User $user, Advert $advert): bool
    {
        if ($user->getStatus() !== UserStatus::Active) {
            return false;
        }

        if ($this->security->isGranted([UserRole::Admin->value, UserRole::Moderator->value])) {
            return true;
        }

        return $advert->getSeller()->getId() === $user->getId();
    }

    public function canEdit(User $user, Advert $advert): bool
    {
         if ($user->getStatus() !== UserStatus::Active) {
             return false;
         }

         return $advert->getSeller()->getId() === $user->getId();
    }
}
