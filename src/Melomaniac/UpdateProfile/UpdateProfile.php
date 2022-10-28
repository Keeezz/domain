<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac\UpdateProfile;

use Keez\Domain\Melomaniac\MelomaniacGateway;
use Keez\Domain\Shared\Command\CommandHandler;

final class UpdateProfile implements CommandHandler
{
  public function __construct(private MelomaniacGateway $melomaniacGateway)
  {
  }

  public function __invoke(Profile $profile): void
  {
    $melomaniac = $profile->melomaniac;
    $melomaniac->update(
      email: $profile->email,
      gender: $profile->gender,
      nickname: $profile->nickname,
      avatar: $profile->avatar
    );
    $this->melomaniacGateway->update($melomaniac);
  }
}
