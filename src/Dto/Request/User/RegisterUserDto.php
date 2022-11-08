<?php

namespace App\Dto\Request\User;

use Symfony\Component\Validator\Constraints as Assert;

final class RegisterUserDto extends AbstractCreateUserDto
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Assert\NotCompromisedPassword]
    public readonly string $password;

    #[Assert\NotBlank]
    #[Assert\EqualTo(propertyPath: 'password')]
    private string $repeatedPassword;

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $password,
        string $repeatedPassword,
    ) {
        $this->password         = $password;
        $this->repeatedPassword = $repeatedPassword;

        parent::__construct($firstName, $lastName, $email);
    }
}
