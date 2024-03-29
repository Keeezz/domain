<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac\ValidateRegistration;

use Keez\Domain\Shared\Command\Command;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

final class ValidationOfRegistration implements Command
{
    public function __construct(
    #[NotBlank]
    #[Uuid]
    #[RegistrationTokenExists]
    public string $registrationToken
  ) {
    }
}
