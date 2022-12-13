<?php

declare(strict_types=1);

namespace Keez\Domain\Tests\Melomaniac;

use Keez\Domain\Melomaniac\Melomaniac;
use Keez\Domain\Melomaniac\MelomaniacGateway;
use Keez\Domain\Melomaniac\UpdatePassword\NewPassword;
use Keez\Domain\Tests\CommandTestCase;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

final class UpdatePasswordTest extends CommandTestCase
{
    public function testShouldUpdatePasswordOfAMelomaniac(): void
    {
        /** @var MelomaniacGateway $melomaniacGateway */
        $melomaniacGateway = $this->container->get(MelomaniacGateway::class);

        /** @var Melomaniac $melomaniac */
        $melomaniac = $melomaniacGateway->getMelomaniacByEmail('melomaniac+1@email.com');

        $this->commandBus->execute(self::createNewPassword()($melomaniac));

        /** @var Melomaniac $melomaniac */
        $melomaniac = $melomaniacGateway->getMelomaniacByEmail('melomaniac+1@email.com');

        self::assertSame('NewPassword123!', $melomaniac->password());
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
        yield 'blank oldPassword' => [self::createNewPassword(oldPassword: '')];
        yield 'invalid oldPassword' => [self::createNewPassword(plainPassword: 'fail')];
    }

    private static function createNewPassword(
    string $oldPassword = 'hashed_password',
    string $plainPassword = 'NewPassword123!'
  ): \Closure {
        return function (Melomaniac $melomaniac) use ($oldPassword, $plainPassword): NewPassword {
            $newPassword = new NewPassword($melomaniac);
            $newPassword->oldPassword = $oldPassword;
            $newPassword->plainPassword = $plainPassword;

            return $newPassword;
        };
    }
}
