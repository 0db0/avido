<?php

namespace App\Dto\Request\Password;

use App\Dto\Request\RequestDtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class ForgotPasswordDto implements RequestDtoInterface
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public readonly string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }
}
