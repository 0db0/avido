<?php

namespace App\Security\Policy;

use App\Entity\Advert;
use App\Entity\User;
use App\Enum\AdvertStatus;
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

        if (
            $this->security->isGranted(UserRole::Admin->value)
            || $this->security->isGranted(UserRole::Moderator->value)
        ) {
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

        return $advert->getAuthor()->getId() === $user->getId();
    }

    public function canEdit(User $user, Advert $advert): bool
    {
         if ($user->getStatus() !== UserStatus::Active) {
             return false;
         }

         if ($advert->getAuthor()->getId() !== $user->getId()) {
             return false;
         }

         return in_array($advert->getStatus(), [AdvertStatus::Draft, AdvertStatus::Rejected], true);
    }

    public function canPushToModeration(User $user, Advert $advert): bool
    {
        if ($user->getStatus() !== UserStatus::Active) {
            return false;
        }

        if ($advert->getAuthor()->getId() !== $user->getId()) {
            return false;
        }

        return in_array($advert->getStatus(), [AdvertStatus::Draft, AdvertStatus::Rejected], true);
    }

    public function canModerate(User $user, Advert $advert): bool
    {
        if ($advert->getStatus() !== AdvertStatus::Moderation) {
            return false;
        }

        return $user->getStatus() === UserStatus::Active
            && $this->security->isGranted([UserRole::Moderator->value, UserRole::Admin->value]);
    }
}
