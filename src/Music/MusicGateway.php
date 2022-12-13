<?php

declare(strict_types=1);

namespace Keez\Domain\Music;

use Keez\Domain\Shared\Gateway;

interface MusicGateway extends Gateway
{
    public function create(Music $music): void;

    public function getMusicBySlug(string $slug): Music;
}
