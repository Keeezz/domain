<?php

declare(strict_types=1);

namespace Keez\Domain\Tests\Melomaniac;

use Closure;
use Generator;
use Keez\Domain\Melomaniac\Gender;
use Keez\Domain\Melomaniac\Melomaniac;
use Keez\Domain\Tests\CommandTestCase;
use Keez\Domain\Melomaniac\MelomaniacGateway;
use Keez\Domain\Melomaniac\UpdateProfile\Profile;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

final class UpdateProfileTest extends CommandTestCase
{
  public function testShouldUpdateProfileOfAMelomaniac(): void
  {
    /** @var MelomaniacGateway $melomaniacGateway */
    $melomaniacGateway = $this->container->get(MelomaniacGateway::class);

    /** @var Melomaniac $melomaniac */
    $melomaniac = $melomaniacGateway->getMelomaniacByEmail('melomaniac+1@email.com');

    $this->commandBus->execute(self::createProfile()($melomaniac));

    /** @var ?Melomaniac $melomaniac */
    $melomaniac = $melomaniacGateway->getMelomaniacByEmail('melomaniac@email.com');

    self::assertInstanceOf(Melomaniac::class, $melomaniac);
    self::assertSame('melomaniac@email.com', $melomaniac->email());
    self::assertSame('melomaniac', $melomaniac->nickname());
    self::assertSame('avatar.png', $melomaniac->avatar());
  }

  /**
   * @dataProvider provideInvalidNewProfiles
   */
  public function testShouldFailedDueToInvalidProfileData(Closure $newProfile): void
  {
    /** @var MelomaniacGateway $melomaniacGateway */
    $melomaniacGateway = $this->container->get(MelomaniacGateway::class);

    /** @var Melomaniac $melomaniac */
    $melomaniac = $melomaniacGateway->getMelomaniacByEmail('melomaniac+1@email.com');

    self::expectException(ValidationFailedException::class);
    $this->commandBus->execute($newProfile($melomaniac));
  }

  /**
   * @return Generator<string, array<array-key, Closure>>
   */
  public function provideInvalidNewProfiles(): Generator
  {
    yield 'blank email' => [self::createProfile(email: '')];
    yield 'invalid email' => [self::createProfile(email: 'fail')];
    yield 'used email' => [self::createProfile(email: 'melomaniac+2@email.com')];
    yield 'blank nickname' => [self::createProfile(nickname: '')];
  }

  private static function createProfile(
    Gender $gender = Gender::Female,
    string $email = 'melomaniac@email.com',
    string $nickname = 'melomaniac',
    string $avatar = 'avatar.png'
  ): Closure {
    return function (Melomaniac $melomaniac) use ($gender, $email, $nickname, $avatar): Profile {
      $profile = new Profile($melomaniac);
      $profile->email = $email;
      $profile->gender = $gender;
      $profile->nickname = $nickname;
      $profile->avatar = $avatar;

      return $profile;
    };
  }
}
