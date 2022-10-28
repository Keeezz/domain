<?php

declare(strict_types=1);

namespace Keez\Domain\Tests\Melomaniac;

use DateTimeImmutable;
use Generator;
use Keez\Domain\Melomaniac\Melomaniac;
use Keez\Domain\Tests\CommandTestCase;
use Keez\Domain\Melomaniac\MelomaniacGateway;
use Keez\Domain\Melomaniac\RequestForgottenPassword\ForgottenPasswordRequest;
use Keez\Domain\Melomaniac\RequestForgottenPassword\ForgottenPasswordRequested;
use Keez\Domain\Shared\Uid\UuidGeneratorInterface;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

final class RequestForgottenPasswordTest extends CommandTestCase
{
  public function testShouldCreateForgottenPasswordRequest(): void
  {
    /** @var MelomaniacGateway $melomaniacGateway */
    $melomaniacGateway = $this->container->get(MelomaniacGateway::class);

    /** @var UuidGeneratorInterface $uuidGenerator */
    $uuidGenerator = $this->container->get(UuidGeneratorInterface::class);

    /** @var Melomaniac $melomaniac */
    $melomaniac = $melomaniacGateway->getMelomaniacByEmail('melomaniac+1@email.com');
    $melomaniac->forgotPassword($uuidGenerator->generate());
    $melomaniacGateway->update($melomaniac);

    $this->commandBus->execute(self::createForgottenPasswordRequest());

    /** @var Melomaniac $melomaniac */
    $melomaniac = $melomaniacGateway->getMelomaniacByEmail('melomaniac+1@email.com');

    self::assertNotNull($melomaniac->forgottenPasswordExpiredAt());
    self::assertNotNull($melomaniac->forgottenPasswordToken());
    self::assertGreaterThan(new DateTimeImmutable(), $melomaniac->forgottenPasswordExpiredAt());
    self::assertTrue($this->eventDispatcher->hasDispatched(ForgottenPasswordRequested::class));
  }

  public function testShouldNotCreateForgottenPasswordRequest(): void
  {
    $this->commandBus->execute(self::createForgottenPasswordRequest('fail@email.com'));
    self::assertFalse($this->eventDispatcher->hasDispatched(ForgottenPasswordRequested::class));
  }

  /**
   * @dataProvider provideForgottenPasswordRequests
   */
  public function testShouldFailedDueInvalidForgottenPasswordRequest(ForgottenPasswordRequest $forgottenPasswordRequest): void
  {
    self::expectException(ValidationFailedException::class);
    $this->commandBus->execute($forgottenPasswordRequest);
  }

  public function provideForgottenPasswordRequests(): Generator
  {
    yield 'blank email' => [self::createForgottenPasswordRequest('')];
    yield 'invalid email' => [self::createForgottenPasswordRequest('fail')];
  }

  private static function createForgottenPasswordRequest(string $email = 'melomaniac+1@email.com'): ForgottenPasswordRequest
  {
    $forgottenPasswordRequest = new ForgottenPasswordRequest();
    $forgottenPasswordRequest->email = $email;

    return $forgottenPasswordRequest;
  }
}
