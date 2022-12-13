<?php

declare(strict_types=1);

namespace Keez\Domain\Tests;

use Keez\Domain\Shared\Command\CommandBus;
use Keez\Domain\Shared\EventDispatcher\EventDispatcher;
use Keez\Domain\Tests\Application\Container\Container;
use Keez\Domain\Tests\Application\EventDispatcher\TestEventDispatcher;

abstract class CommandTestCase extends ContainerTestCase
{
    protected CommandBus $commandBus;

    protected TestEventDispatcher $eventDispatcher;

    protected Container $container;

    public function setUp(): void
    {
        $this->container = self::createContainer();
        $this->commandBus = $this->container->get(CommandBus::class);
        $this->eventDispatcher = $this->container->get(EventDispatcher::class);
    }
}
