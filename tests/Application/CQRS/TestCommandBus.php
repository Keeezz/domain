<?php

declare(strict_types=1);

namespace Keez\Domain\Tests\Application\CQRS;

use Keez\Domain\Shared\Command\Command;
use Keez\Domain\Shared\Command\CommandBus;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Validator\ContainerConstraintValidatorFactory;
use Symfony\Component\Validator\Validation;

final class TestCommandBus implements CommandBus
{
    public function __construct(private ContainerInterface $container, private array $handlers)
    {
    }

    public function execute(Command $command): void
    {
        $constraintValidatorFactory = new ContainerConstraintValidatorFactory($this->container);

        $validator = Validation::createValidatorBuilder()
          ->setConstraintValidatorFactory($constraintValidatorFactory)
          ->enableAnnotationMapping()
          ->getValidator();

        $violations = $validator->validate($command);

        if (count($violations) > 0) {
            throw new ValidationFailedException($command, $violations);
        }

        $this->container->get($this->handlers[$command::class])->__invoke($command);
    }
}
