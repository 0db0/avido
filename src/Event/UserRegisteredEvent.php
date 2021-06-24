<?php

namespace App\Event;

use App\Entity\EmailVerification;
use App\Entity\User;

class UserRegisteredEvent
{
    public const NAME = 'user.registered';

    private User $user;
    private EmailVerification $verification;

    public function __construct(User $user, EmailVerification $verification)
    {
        $this->user = $user;
        $this->verification = $verification;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getVerification(): EmailVerification
    {
        return $this->verification;
    }
}