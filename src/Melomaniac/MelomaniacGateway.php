<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac;

use Keez\Domain\Shared\Gateway;

interface MelomaniacGateway extends Gateway
{
    public function register(Melomaniac $melomaniac): void;

    public function hasEmail(string $email, ?Melomaniac $melomaniac = null): bool;

    public function hasRegistrationToken(string $registrationToken): bool;

    public function getMelomaniacByEmail(string $email): ?Melomaniac;

    public function getMelomaniacByRegistrationToken(string $registrationToken): ?Melomaniac;

    public function getMelomaniacByForgottenPasswordToken(string $forgottenPasswordToken): ?Melomaniac;

    public function update(Melomaniac $melomaniac): void;
}
