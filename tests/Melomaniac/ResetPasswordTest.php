<?php

declare(strict_types=1);

namespace Keez\Domain\Tests\Melomaniac;

use Keez\Domain\Melomaniac\Melomaniac;
use Keez\Domain\Melomaniac\MelomaniacGateway;
use Keez\Domain\Melomaniac\ResetPassword\NewPassword;
use Keez\Domain\Tests\CommandTestCase;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

final class ResetPasswordTest extends CommandTestCase
{
    public function testShouldResetPasswordOfAMelomaniac(): void
    {
        /** @var MelomaniacGateway $melomaniacGateway */
        $melomaniacGateway = $this->container->get(MelomaniacGateway::class);

        /** @var Melomaniac $melomaniac */
        $melomaniac = $melomaniacGateway->getMelomaniacByEmail('melomaniac+1@email.com');

        $this->commandBus->execute(self::createNewPassword()($melomaniac));

        /** @var Melomaniac $melomaniac */
        $melomaniac = $melomaniacGateway->getMelomaniacByEmail('melomaniac+1@email.com');

        self::assertSame('NewPassword123!', $melomaniac->password());
        self::assertNull($melomaniac->forgottenPasswordToken());
        self::assertNull($melomaniac->forgottenPasswordExpiredAt());
    }

    /**
     * @dataProvider provideInvalidNewPasswords
     */
    public function testShouldFailedDueToInvalidNewPasswordData(\Closure $newPassword): void
    {
        /** @var MelomaniacGateway $melomaniacGateway */
        $melomaniacGateway = $this->container->get(MelomaniacGateway::class);

        /** @var Melomaniac $melomaniac */
        $melomaniac = $melomaniacGateway->getMelomaniacByEmail('melomaniac+1@email.com');

        self::expectException(ValidationFailedException::class);
        $this->commandBus->execute($newPassword($melomaniac));
    }

    public function provideInvalidNewPasswords(): \Generator
    {
        yield 'blank plainPassword' => [self::createNewPassword(plainPassword: '')];
        yield 'invalid plainPassword' => [self::createNewPassword(plainPassword: 'fail')];
    }

    private static function createNewPassword(string $plainPassword = 'NewPassword123!'): \Closure
    {
        return function (Melomaniac $melomaniac) use ($plainPassword): NewPassword {
            $newPassword = new NewPassword($melomaniac);
            $newPassword->plainPassword = $plainPassword;

            return $newPassword;
        };
    }
}
