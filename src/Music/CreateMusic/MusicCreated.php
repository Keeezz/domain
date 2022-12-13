<?php

declare(strict_types=1);

namespace Keez\Domain\Music\CreateMusic;

use Keez\Domain\Music\Music;
use Keez\Domain\Shared\EventDispatcher\Event;

final class MusicCreated implements Event
{
    public function __construct(public Music $music)
    {
    }
}
