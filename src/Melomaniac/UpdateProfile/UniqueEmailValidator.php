<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac\UpdateProfile;

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
            throw new \InvalidArgumentException('The constraint must be an instance of UniqueEmail.'); // @codeCoverageIgnore
        }

        if (!$value instanceof Profile) {
            return; // @codeCoverageIgnore
        }

        if ('' === $value->email) {
            return; // @codeCoverageIgnore
        }

        if ($value->melomaniac->email() === $value->email) {
            return; // @codeCoverageIgnore
        }

        if ($this->melomaniacGateway->hasEmail($value->email, $value->melomaniac)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
