<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac\ValidateRegistration;

use Keez\Domain\Melomaniac\MelomaniacGateway;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class RegistrationTokenExistsValidator extends ConstraintValidator
{
    public function __construct(private MelomaniacGateway $melomaniacGateway)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof RegistrationTokenExists) {
            throw new \InvalidArgumentException('Constraint must be RegistrationTokenExists.'); // @codeCoverageIgnore
        }

        if (!is_string($value) || '' === $value) {
            return; // @codeCoverageIgnore
        }

        if (!$this->melomaniacGateway->hasRegistrationToken($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
