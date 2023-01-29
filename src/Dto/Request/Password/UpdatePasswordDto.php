<?php

namespace App\Dto\Request\Password;

use App\Dto\Request\RequestDtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdatePasswordDto implements RequestDtoInterface
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public readonly string $oldPassword;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Assert\NotCompromisedPassword]
    public readonly string $password;

    public function __construct(string $oldPassword, string $password)
    {
        $this->oldPassword = $oldPassword;
        $this->password    = $password;
    }
}
