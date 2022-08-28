<?php
declare(strict_types=1);

namespace App\Security\Voters;

use App\Entity\Advert;
use App\Entity\User;
use App\Security\Permissions\AdvertPermissions;
use App\Security\Policy\AdvertPolicy;
use Exception;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class AdvertVoter extends Voter
{
    public function __construct(private readonly AdvertPolicy $policy)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return match($attribute) {
            AdvertPermissions::CREATE, AdvertPermissions::LIST => is_null($subject),
            AdvertPermissions::SHOW, AdvertPermissions::EDIT   => $subject instanceof Advert,

            default => false,
        };
    }

    /**
     * @throws Exception
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (! $user instanceof User) {
            return false;
        }

        return match ($attribute) {
            AdvertPermissions::CREATE => $this->policy->canCreate($user),
            AdvertPermissions::LIST   => $this->policy->canList($user),
            AdvertPermissions::SHOW   => $this->policy->canShow($user, $subject),
            AdvertPermissions::EDIT   => $this->policy->canEdit($user, $subject),

            default => throw new Exception(),
        };
    }
}
