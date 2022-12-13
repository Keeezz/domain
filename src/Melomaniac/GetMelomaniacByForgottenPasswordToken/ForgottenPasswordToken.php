<?php

namespace Keez\Domain\Melomaniac\GetMelomaniacByForgottenPasswordToken;

use Keez\Domain\Shared\Query\Query;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

final class ForgottenPasswordToken implements Query
{
    public function __construct(
    #[NotBlank]
    #[Uuid]
    public string $token
  ) {
    }
}
