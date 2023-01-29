<?php

namespace App\Dto\Request\Password;

use App\Dto\Request\RequestDtoInterface;
use App\Validator\TokenExists as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class SetupPasswordDto implements RequestDtoInterface
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Assert\NotCompromisedPassword]
    public readonly string $password;

    #[Assert\NotBlank]
    #[Assert\EqualTo(propertyPath: 'password')]
    private readonly string $repeatedPassword;

    #[Assert\NotBlank]
    #[CustomAssert\TokenExists]
    public readonly string $token;

    public function __construct(string $password, string $repeatedPassword, string $token)
    {
        $this->password         = $password;
        $this->repeatedPassword = $repeatedPassword;
        $this->token            = $token;
    }
}
