<?php

declare(strict_types=1);

namespace Keez\Domain\Shared\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @method void dispatch(Event $event)
 */
interface EventDispatcher extends EventDispatcherInterface
{
}
