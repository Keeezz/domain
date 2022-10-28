<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac\RequestForgottenPassword;

use Keez\Domain\Melomaniac\MelomaniacGateway;
use Keez\Domain\Shared\Command\CommandHandler;
use Keez\Domain\Shared\EventDispatcher\EventDispatcher;
use Keez\Domain\Shared\Uid\UuidGeneratorInterface;

final class RequestForgottenPassword implements CommandHandler
{
  public function __construct(
    private UuidGeneratorInterface $uuidGenerator,
    private MelomaniacGateway $melomaniacGateway,
    private EventDispatcher $eventDispatcher
  ) {
  }

  public function __invoke(ForgottenPasswordRequest $forgottenPasswordRequest): void
  {
    $melomaniac = $this->melomaniacGateway->getMelomaniacByEmail($forgottenPasswordRequest->email);

    if (null === $melomaniac) {
      return;
    }

    $melomaniac->forgotPassword($this->uuidGenerator->generate());
    $this->melomaniacGateway->update($melomaniac);
    $this->eventDispatcher->dispatch(new ForgottenPasswordRequested($melomaniac));
  }
}
