<?php

declare(strict_types=1);

namespace Keez\Domain\Shared\Query;

interface QueryBus
{
    public function fetch(Query $query): mixed;
}
