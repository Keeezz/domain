<?php

declare(strict_types=1);

namespace Keez\Domain\Tests\Application\Uid;

use Keez\Domain\Shared\Uid\UlidGeneratorInterface;
use Symfony\Component\Uid\Ulid;

final class UlidGenerator implements UlidGeneratorInterface
{
    public function generate(): Ulid
    {
        return new Ulid();
    }
}
