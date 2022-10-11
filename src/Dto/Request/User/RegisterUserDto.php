<?php

namespace App\Dto\Request\User;

use Symfony\Component\Validator\Constraints as Assert;

final class RegisterUserDto extends AbstractCreateUserDto
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Assert\NotCompromisedPassword]
    public readonly string $password;

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $password
    ) {
        $this->password  = $password;

        parent::__construct($firstName, $lastName, $email);
    }
}
