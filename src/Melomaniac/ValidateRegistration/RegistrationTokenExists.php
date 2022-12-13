<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac\ValidateRegistration;

use Attribute;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

#[\Attribute]
final class RegistrationTokenExists extends Constraint
{
    public string $message = 'This registration token does not exist.';

    /**
     * @return class-string<ConstraintValidator>
     */
    public function validatedBy(): string
    {
        return RegistrationTokenExistsValidator::class;
    }
}
