<?php

declare(strict_types=1);

namespace Keez\Domain\Tests\Melomaniac;

use Generator;
use Keez\Domain\Melomaniac\Melomaniac;
use Keez\Domain\Tests\CommandTestCase;
use Keez\Domain\Melomaniac\MelomaniacGateway;
use Keez\Domain\Melomaniac\Register\Registration;
use Keez\Domain\Melomaniac\Register\NewRegistration;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

final class RegisterTest extends CommandTestCase
{
  public function testShouldRegisterAMelomaniac(): void
  {
    $this->commandBus->execute(self::createRegistration());

    /** @var MelomaniacGateway $melomaniacGateway */
    $melomaniacGateway = $this->container->get(MelomaniacGateway::class);
    $melomaniac = $melomaniacGateway->getMelomaniacByEmail('melomaniac@email.com');

    self::assertInstanceOf(Melomaniac::class, $melomaniac);
    self::assertSame('melomaniac@email.com', $melomaniac->email());
    self::assertSame('Password123!', $melomaniac->password());
    self::assertNotNull($melomaniac->registrationToken());
    self::assertFalse($melomaniac->hasValidRegistration());
    self::assertTrue($this->eventDispatcher->hasDispatched(NewRegistration::class));
  }

  /**
   * @dataProvider provideInvalidRegistrations
   */
  public function testShouldFailedDueToInvalidRegistrationData(Registration $registration): void
  {
    self::expectException(ValidationFailedException::class);
    $this->commandBus->execute($registration);
  }

  public function provideInvalidRegistrations(): Generator
  {
    yield 'blank email' => [self::createRegistration(email: '')];
    yield 'invalid email' => [self::createRegistration(email: 'fail')];
    yield 'used email' => [self::createRegistration(email: 'melomaniac+1@email.com')];
    yield 'blank plainPassword' => [self::createRegistration(plainPassword: '')];
    yield 'invalid plainPassword' => [self::createRegistration(plainPassword: 'fail')];
  }

  private static function createRegistration(string $email = "melomaniac@email.com", string $plainPassword = "Password123!"): Registration
  {
    $registration = new Registration();

    $registration->email = $email;
    $registration->plainPassword = $plainPassword;

    return $registration;
  }
}
