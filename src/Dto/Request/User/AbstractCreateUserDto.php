<?php

namespace App\Dto\Request\User;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

abstract class AbstractCreateUserDto
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public readonly string $firstName;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public readonly string $lastName;

    #[Assert\NotBlank]
    #[Assert\Email]
//    #[UniqueEntity('email', entityClass: User::class)]
    public readonly string $email;

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
    ) {
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
        $this->email     = $email;
    }
}
