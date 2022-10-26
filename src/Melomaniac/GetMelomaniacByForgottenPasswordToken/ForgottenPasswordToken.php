<?php

namespace Keez\Domain\Melomaniac\GetMelomaniacByForgottenPasswordToken;

use Keez\Domain\Shared\Query\Query;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ForgottenPasswordToken implements Query
{
  public function __construct(
    #[NotBlank]
    #[Uuid]
    public string $token
  ) {
  }
}
