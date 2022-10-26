<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac\Register;

use Keez\Domain\Melomaniac\Melomaniac;
use Keez\Domain\Shared\EventDispatcher\Event;

final class NewRegistration implements Event
{
  public function __construct(public Melomaniac $melomaniac)
  {
  }
}
