<?php

declare(strict_types=1);

namespace Keez\Domain\Tests\Application\EventDispatcher;

use Keez\Domain\Shared\EventDispatcher\EventDispatcher;
use Psr\Container\ContainerInterface;

final class TestEventDispatcher implements EventDispatcher
{
  private array $eventsDispatched = [];

  public function __construct(private ContainerInterface $container, private array $eventListeners)
  {
  }

  public function dispatch(object $event): void
  {
    /* @var Event $event */

    $this->eventsDispatched[] = $event::class;

    if (isset($this->eventListeners[$event::class])) {
      $this->container->get($this->eventListeners[$event::class])->__invoke($event);
    }
  }

  public function reset(): void
  {
    $this->eventsDispatched = [];
  }

  public function hasDispatched(string $eventClass): bool
  {
    return in_array($eventClass, $this->eventsDispatched, true);
  }
}
