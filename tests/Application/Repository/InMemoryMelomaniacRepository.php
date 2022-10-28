<?php

declare(strict_types=1);

namespace Keez\Domain\Tests\Application\Repository;

use Symfony\Component\Uid\Ulid;
use Keez\Domain\Melomaniac\Melomaniac;
use Keez\Domain\Melomaniac\MelomaniacGateway;

final class InMemoryMelomaniacRepository implements MelomaniacGateway
{
  public array $melomaniacs = [];

  public function __construct()
  {
    $this->init();
  }

  public static function createMelomaniac(int $index, string $ulid): Melomaniac
  {
    return Melomaniac::create(
      id: Ulid::fromString($ulid),
      email: sprintf('melomaniac+%d@email.com', $index),
      nickname: sprintf('melomaniac+%d', $index),
      password: 'hashed_password'
    );
  }

  public function init(): void
  {
    $this->melomaniacs = [
      '01GBJK7XV3YXQ51EHN9G5DAMYN' => self::createMelomaniac(1, '01GBJK7XV3YXQ51EHN9G5DAMYN'),
      '01GBFF6QBSBH7RRTK6N0770BSY' => self::createMelomaniac(2, '01GBFF6QBSBH7RRTK6N0770BSY'),
    ];
  }

  public function register(Melomaniac $melomaniac): void
  {
    $this->melomaniacs[(string) $melomaniac->id()] = $melomaniac;
  }

  public function hasEmail(string $email, ?Melomaniac $melomaniac = null): bool
  {
    return count(
      array_filter(
        $this->melomaniacs,
        static fn (Melomaniac $p) => $p->email() === $email && (null === $melomaniac || $p->id() !== $melomaniac->id()),
      )
    ) > 0;
  }

  public function update(Melomaniac $melomaniac): void
  {
    $this->melomaniacs[(string) $melomaniac->id()] = $melomaniac;
  }

  public function hasRegistrationToken(string $registrationToken): bool
  {
    return null !== $this->getMelomaniacByRegistrationToken($registrationToken);
  }

  public function getMelomaniacByRegistrationToken(string $registrationToken): ?Melomaniac
  {
    foreach ($this->melomaniacs as $melomaniac) {
      if ((string) $melomaniac->registrationToken() === $registrationToken) {
        return $melomaniac;
      }
    }

    return null;
  }

  public function getMelomaniacByForgottenPasswordToken(string $forgottenPasswordToken): ?Melomaniac
  {
    foreach ($this->melomaniacs as $melomaniac) {
      if ((string) $melomaniac->forgottenPasswordToken() === $forgottenPasswordToken) {
        return $melomaniac;
      }
    }

    return null;
  }

  public function getMelomaniacByEmail(string $email): ?Melomaniac
  {
    foreach ($this->melomaniacs as $melomaniac) {
      if ($melomaniac->email() === $email) {
        return $melomaniac;
      }
    }

    return null;
  }
}
