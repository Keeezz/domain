<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac\ValidateRegistration;

use Keez\Domain\Melomaniac\Melomaniac;
use Keez\Domain\Melomaniac\MelomaniacGateway;
use Keez\Domain\Shared\Command\CommandHandler;

final class ValidateRegistration implements CommandHandler
{
    public function __construct(private MelomaniacGateway $melomaniacGateway)
    {
    }

    public function __invoke(ValidationOfRegistration $validationOfRegistration): void
    {
        /** @var Melomaniac $melomaniac */
        $melomaniac = $this->melomaniacGateway->getMelomaniacByRegistrationToken($validationOfRegistration->registrationToken);
        $melomaniac->validateRegistration();

        $this->melomaniacGateway->update($melomaniac);
    }
}
