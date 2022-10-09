<?php

namespace App\Command\Question;

use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Validation;

final class PasswordQuestion extends Question
{
    public function __construct(string $question, float|bool|int|string $default = null)
    {
        parent::__construct($question, $default);
        $this->setUp();
    }

    private function setUp(): void
    {
        $validator = Validation::createCallable(new NotBlank(), new NotCompromisedPassword());
        $this->setValidator($validator);

        $this->setHidden(true);
        $this->setMaxAttempts(3);
    }
}
