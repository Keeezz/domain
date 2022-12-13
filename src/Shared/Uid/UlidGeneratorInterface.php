<?php

declare(strict_types=1);

namespace Keez\Domain\Shared\Uid;

use Symfony\Component\Uid\Ulid;

interface UlidGeneratorInterface
{
    public function generate(): Ulid;
}
