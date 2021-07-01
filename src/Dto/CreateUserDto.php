<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUserDto
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
    #[Assert\Length(max: 13)]
    private string $phoneNumber;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $whenConvenientReceiveCalls;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $password;

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $phoneNumber,
        string $whenConvenientReceiveCalls,
        string $password
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->whenConvenientReceiveCalls = $whenConvenientReceiveCalls;
        $this->password = $password;

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

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function getWhenConvenientReceiveCalls(): string
    {
        return $this->whenConvenientReceiveCalls;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}