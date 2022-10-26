<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac\Register;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class UniqueEmail extends Constraint
{
  public string $message = 'This email is already used.';

  public function validatedBy(): string
  {
    return UniqueEmailValidator::class;
  }
}
