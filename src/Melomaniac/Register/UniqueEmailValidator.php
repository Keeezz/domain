<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac\Register;

use InvalidArgumentException;
use Keez\Domain\Melomaniac\MelomaniacGateway;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class UniqueEmailValidator extends ConstraintValidator
{
  public function __construct(private MelomaniacGateway $melomaniacGateway)
  {
  }

  public function validate(mixed $value, Constraint $constraint): void
  {
    if (!$constraint instanceof UniqueEmail) {
      throw new InvalidArgumentException('Constraint must be UniqueEmail.');
    }

    if (!is_string($value) || '' === $value) {
      return;
    }

    if ($this->melomaniacGateway->hasEmail($value)) {
      $this->context->buildViolation($constraint->message)->addViolation();
    }
  }
}
