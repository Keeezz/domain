<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac\ResetPassword;

use Keez\Domain\Melomaniac\Melomaniac;
use Keez\Domain\Shared\Command\Command;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

final class NewPassword implements Command
{
    #[NotBlank]
    #[Regex(pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/')]
    public string $plainPassword;

    public function __construct(public Melomaniac $melomaniac)
    {
    }
}
