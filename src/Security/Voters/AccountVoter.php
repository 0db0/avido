<?php
declare(strict_types=1);

namespace App\Security\Voters;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class AccountVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {

    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {

    }
}