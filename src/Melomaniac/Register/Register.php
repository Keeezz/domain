<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac\Register;

use Keez\Domain\Melomaniac\Melomaniac;
use Keez\Domain\Melomaniac\MelomaniacGateway;
use Keez\Domain\Shared\Command\CommandHandler;
use Keez\Domain\Shared\EventDispatcher\EventDispatcher;
use Keez\Domain\Shared\Uid\UlidGeneratorInterface;
use Keez\Domain\Shared\Uid\UuidGeneratorInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final class Register implements CommandHandler
{
  public function __construct(
    private PasswordHasherInterface $passwordHasher,
    private UlidGeneratorInterface $ulidGenerator,
    private UuidGeneratorInterface $uuidGenerator,
    private MelomaniacGateway $melomaniacGateway,
    private EventDispatcher $eventDispatcher
  ) {
  }

  public function __invoke(Registration $registration): void
  {
    $melomaniac = Melomaniac::create(
      id: $this->ulidGenerator->generate(),
      email: $registration->email,
      password: $this->passwordHasher->hash($registration->plainPassword)
    );

    $melomaniac->prepareValidationOfRegistration($this->uuidGenerator->generate());

    $this->melomaniacGateway->register($melomaniac);

    $this->eventDispatcher->dispatch(new NewRegistration($melomaniac));
  }
}
