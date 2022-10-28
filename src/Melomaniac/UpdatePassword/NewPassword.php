<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac\UpdatePassword;

use Keez\Domain\Melomaniac\Melomaniac;
use Keez\Domain\Shared\Command\Command;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;

#[CurrentPassword]
final class NewPassword implements Command
{
  #[NotBlank]
  public string $oldPassword;

  #[NotBlank]
  #[Regex(pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/')]
  public string $plainPassword;

  public function __construct(public Melomaniac $melomaniac)
  {
  }
}
