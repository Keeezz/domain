<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac\RequestForgottenPassword;

use Keez\Domain\Melomaniac\Melomaniac;
use Keez\Domain\Shared\EventDispatcher\Event;

final class ForgottenPasswordRequested implements Event
{
  public function __construct(public Melomaniac $melomaniac)
  {
  }
}
