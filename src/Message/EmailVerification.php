<?php

namespace App\Message;

class EmailVerification
{
    public function __construct(private readonly int $userId)
    {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
