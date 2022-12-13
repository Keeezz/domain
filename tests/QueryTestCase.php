<?php

declare(strict_types=1);

namespace Keez\Domain\Tests;

use Keez\Domain\Shared\Query\QueryBus;
use Keez\Domain\Tests\Application\Container\Container;

abstract class QueryTestCase extends ContainerTestCase
{
    protected QueryBus $queryBus;

    protected Container $container;

    public function setUp(): void
    {
        $this->container = self::createContainer();
        $this->queryBus = $this->container->get(QueryBus::class);
    }
}
