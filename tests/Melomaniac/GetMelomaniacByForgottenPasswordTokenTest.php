<?php

declare(strict_types=1);

namespace Keez\Domain\Tests\Melomaniac;

use Keez\Domain\Melomaniac\GetMelomaniacByForgottenPasswordToken\ForgottenPasswordToken;
use Keez\Domain\Melomaniac\GetMelomaniacByForgottenPasswordToken\ForgottenPasswordTokenExpiredException;
use Keez\Domain\Melomaniac\Melomaniac;
use Keez\Domain\Melomaniac\MelomaniacGateway;
use Keez\Domain\Shared\Uid\UuidGeneratorInterface;
use Keez\Domain\Tests\QueryTestCase;

final class GetMelomaniacByForgottenPasswordTokenTest extends QueryTestCase
{
    public function testShouldReturnAMelomaniacByItsForgottenPasswordToken(): void
    {
        /** @var MelomaniacGateway $melomaniacGateway */
        $melomaniacGateway = $this->container->get(MelomaniacGateway::class);

        /** @var UuidGeneratorInterface $uuidGenerator */
        $uuidGenerator = $this->container->get(UuidGeneratorInterface::class);

        /** @var Melomaniac $melomaniac */
        $melomaniac = $melomaniacGateway->getMelomaniacByEmail('melomaniac+1@email.com');

        $melomaniac->forgotPassword($uuidGenerator->generate());
        $melomaniacGateway->update($melomaniac);

        $melomaniac = $this->queryBus->fetch(new ForgottenPasswordToken((string) $melomaniac->forgottenPasswordToken()));

        self::assertInstanceOf(Melomaniac::class, $melomaniac);
    }

    public function testShouldReturnNoMelomaniacDueToNoMatchOfTheGivenForgottenPasswordToken(): void
    {
        /** @var UuidGeneratorInterface $uuidGenerator */
        $uuidGenerator = $this->container->get(UuidGeneratorInterface::class);

        $melomaniac = $this->queryBus->fetch(new ForgottenPasswordToken((string) $uuidGenerator->generate()));

        self::assertNull($melomaniac);
    }

    public function testShouldFailedDueToAExpiredForgottenPasswordRequest(): void
    {
        /** @var MelomaniacGateway $melomaniacGateway */
        $melomaniacGateway = $this->container->get(MelomaniacGateway::class);

        /** @var UuidGeneratorInterface $uuidGenerator */
        $uuidGenerator = $this->container->get(UuidGeneratorInterface::class);

        /** @var Melomaniac $melomaniac */
        $melomaniac = $melomaniacGateway->getMelomaniacByEmail('melomaniac+1@email.com');
        $melomaniac->forgotPassword($uuidGenerator->generate());
        $reflectionProperty = new \ReflectionProperty($melomaniac, 'forgottenPasswordExpiredAt');
        $reflectionProperty->setValue($melomaniac, new \DateTimeImmutable('1 day ago'));
        $melomaniacGateway->update($melomaniac);

        self::expectException(ForgottenPasswordTokenExpiredException::class);

        $this->queryBus->fetch(new ForgottenPasswordToken((string) $melomaniac->forgottenPasswordToken()));
    }
}
