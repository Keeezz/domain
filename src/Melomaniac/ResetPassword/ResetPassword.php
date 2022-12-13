<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac\ResetPassword;

use Keez\Domain\Melomaniac\MelomaniacGateway;
use Keez\Domain\Shared\Command\CommandHandler;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final class ResetPassword implements CommandHandler
{
    public function __construct(
    private PasswordHasherInterface $passwordHasher,
    private MelomaniacGateway $melomaniacGateway
  ) {
    }

    public function __invoke(NewPassword $newPassword): void
    {
        $melomaniac = $newPassword->melomaniac;
        $melomaniac->newPassword($this->passwordHasher->hash($newPassword->plainPassword));
        $this->melomaniacGateway->update($melomaniac);
    }
}
