<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateUserDto
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $firstName;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $lastName;

    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Assert\NotCompromisedPassword]
    private string $password;

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $password
    ) {
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
        $this->email     = $email;
        $this->password  = $password;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}