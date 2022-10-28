<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac\RequestForgottenPassword;

use Keez\Domain\Shared\Command\Command;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ForgottenPasswordRequest implements Command
{
  #[Email]
  #[NotBlank]
  public string $email;
}
