<?php

namespace App\Command\Question;

use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

final class NameQuestion extends Question
{
    public function __construct(string $question, float|bool|int|string $default = null)
    {
        parent::__construct($question, $default);
        $this->setUp();
    }

    private function setUp(): void
    {
        $validator = Validation::createCallable(new NotBlank(), new Length(min: 3, max: 255));
        $this->setValidator($validator);

        $this->setMaxAttempts(3);
    }
}
