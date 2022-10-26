<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac\GetMelomaniacByForgottenPasswordToken;

use Keez\Domain\Melomaniac\Melomaniac;
use Keez\Domain\Melomaniac\MelomaniacGateway;
use Keez\Domain\Shared\Query\QueryHandler;

final class GetMelomaniacByForgottenPasswordToken implements QueryHandler
{
  public function __construct(
    private MelomaniacGateway $melomaniacGateway
  ) {
  }

  public function __invoke(ForgottenPasswordToken $query): ?Melomaniac
  {
    $melomaniac = $this->melomaniacGateway->getMelomaniacByForgottenPasswordToken($query->token);

    if (null === $melomaniac) {
      return null;
    }

    if ($melomaniac->hasForgottenPasswordTokenExpired()) {
      throw new ForgottenPasswordTokenExpiredException();
    }

    return $melomaniac;
  }
}
