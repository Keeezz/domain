<?php

declare(strict_types=1);

namespace Keez\Domain\Tests\Melomaniac;

use Keez\Domain\Melomaniac\Melomaniac;
use Keez\Domain\Tests\CommandTestCase;
use Keez\Domain\Melomaniac\MelomaniacGateway;
use Keez\Domain\Shared\Uid\UuidGeneratorInterface;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Keez\Domain\Melomaniac\ValidateRegistration\ValidationOfRegistration;

final class ValidateRegistrationTest extends CommandTestCase
{
  public function testShouldValidateRegistrationOfAMelomaniac(): void
  {
    /** @var MelomaniacGateway $melomaniacGateway */
    $melomaniacGateway = $this->container->get(MelomaniacGateway::class);

    /** @var UuidGeneratorInterface $uuidGenerator */
    $uuidGenerator = $this->container->get(UuidGeneratorInterface::class);

    /** @var Melomaniac $melomaniac */
    $melomaniac = $melomaniacGateway->getMelomaniacByEmail('melomaniac+1@email.com');
    $melomaniac->prepareValidationOfRegistration($uuidGenerator->generate());
    $melomaniacGateway->update($melomaniac);

    $this->commandBus->execute(new ValidationOfRegistration((string) $melomaniac->registrationToken()));

    self::assertNull($melomaniac->registrationToken());
    self::assertNotNull($melomaniac->registeredAt());
    self::assertTrue($melomaniac->hasValidRegistration());
  }

  public function testShouldFailedDueToUnexistingRegistrationToken(): void
  {
    /** @var UuidGeneratorInterface $uuidGenerator */
    $uuidGenerator = $this->container->get(UuidGeneratorInterface::class);

    self::expectException(ValidationFailedException::class);

    $this->commandBus->execute(new ValidationOfRegistration((string) $uuidGenerator->generate()));
  }
}
