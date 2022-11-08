<?php

namespace App\Dto\Request\Password;

use Symfony\Component\Validator\Constraints as Assert;

final class UpdatePasswordDto
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $oldPassword;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Assert\NotCompromisedPassword]
    private string $password;

    public function __construct(string $oldPassword, string $password)
    {
        $this->oldPassword = $oldPassword;
        $this->password = $password;
    }

    public function getOldPassword(): string
    {
        return $this->oldPassword;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
