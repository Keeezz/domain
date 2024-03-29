<?php

declare(strict_types=1);

namespace Keez\Domain\Tests\Application\Uid;

use Keez\Domain\Shared\Uid\UuidGeneratorInterface;
use Symfony\Component\Uid\Uuid;

final class UuidGenerator implements UuidGeneratorInterface
{
    public function generate(): Uuid
    {
        return Uuid::v4();
    }
}
