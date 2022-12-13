<?php

declare(strict_types=1);

namespace Keez\Domain\Shared\Command;

interface CommandBus
{
    public function execute(Command $command): void;
}
