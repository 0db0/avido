<?php

namespace App\Dto\Request\Password;

use App\Dto\Request\RequestDtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class ResetPasswordDto implements RequestDtoInterface
{

    #[Assert\NotBlank]
    #[Assert\Email]
    public readonly string $email;

    public readonly string $token;

    public readonly string $password;


    public function __construct(
        string $email,
        string $token,
        string $password,
    ) {
        $this->password = $password;
        $this->token    = $token;
        $this->email    = $email;
    }
}
