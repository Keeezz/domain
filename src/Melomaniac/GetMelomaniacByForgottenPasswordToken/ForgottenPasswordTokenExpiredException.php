<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac\GetMelomaniacByForgottenPasswordToken;

use InvalidArgumentException;

final class ForgottenPasswordTokenExpiredException extends InvalidArgumentException
{
}
