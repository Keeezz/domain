<?php

declare(strict_types=1);

use Keez\Domain\Shared\Query\QueryBus;
use Keez\Domain\Shared\Command\CommandBus;
use Keez\Domain\Melomaniac\MelomaniacGateway;
use Keez\Domain\Melomaniac\Register\Register;
use Keez\Domain\Melomaniac\Register\Registration;
use Keez\Domain\Shared\Uid\UlidGeneratorInterface;
use Keez\Domain\Shared\Uid\UuidGeneratorInterface;
use Keez\Domain\Tests\Application\CQRS\TestQueryBus;
use Keez\Domain\Tests\Application\Uid\UlidGenerator;
use Keez\Domain\Tests\Application\Uid\UuidGenerator;
use Keez\Domain\Tests\Application\Container\Container;
use Keez\Domain\Tests\Application\CQRS\TestCommandBus;
use Keez\Domain\Shared\EventDispatcher\EventDispatcher;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Keez\Domain\Tests\Application\EventDispatcher\TestEventDispatcher;
use Keez\Domain\Tests\Application\Repository\InMemoryMelomaniacRepository;
use Keez\Domain\Melomaniac\GetMelomaniacByForgottenPasswordToken\ForgottenPasswordToken;
use Keez\Domain\Melomaniac\Register\UniqueEmailValidator;
use Keez\Domain\Melomaniac\GetMelomaniacByForgottenPasswordToken\GetMelomaniacByForgottenPasswordToken;
use Keez\Domain\Melomaniac\RequestForgottenPassword\ForgottenPasswordRequest;
use Keez\Domain\Melomaniac\RequestForgottenPassword\RequestForgottenPassword;

return function (Container $container): void {
  $container
    ->set(
      GetMelomaniacByForgottenPasswordToken::class,
      static fn (Container $container): GetMelomaniacByForgottenPasswordToken => new GetMelomaniacByForgottenPasswordToken(
        $container->get(MelomaniacGateway::class)
      )
    )
    ->set(
      Register::class,
      static fn (Container $container): Register => new Register(
        $container->get(PasswordHasherInterface::class),
        $container->get(UlidGeneratorInterface::class),
        $container->get(UuidGeneratorInterface::class),
        $container->get(MelomaniacGateway::class),
        $container->get(EventDispatcher::class)
      )
    )
    ->set(
      RequestForgottenPassword::class,
      static fn (Container $container): RequestForgottenPassword => new RequestForgottenPassword(
        $container->get(UuidGeneratorInterface::class),
        $container->get(MelomaniacGateway::class),
        $container->get(EventDispatcher::class)
      )
    )
    ->set(
      MelomaniacGateway::class,
      static fn (Container $container): MelomaniacGateway => new InMemoryMelomaniacRepository()
    )
    ->set(
      UuidGeneratorInterface::class,
      static fn (Container $container): UuidGeneratorInterface => new UuidGenerator()
    )
    ->set(
      UlidGeneratorInterface::class,
      static fn (Container $container): UlidGeneratorInterface => new UlidGenerator()
    )
    ->set(
      UniqueEmailValidator::class,
      static fn (Container $container): UniqueEmailValidator => new UniqueEmailValidator(
        $container->get(MelomaniacGateway::class)
      )
    )
    ->set(
      PasswordHasherInterface::class,
      static fn (Container $container): PasswordHasherInterface => (new PasswordHasherFactory(['common' => ['algorithm' => 'plaintext']]))
        ->getPasswordHasher('common')
    )
    ->set(
      EventDispatcher::class,
      static fn (Container $container): EventDispatcher => new TestEventDispatcher($container, [])
    )
    ->set(
      QueryBus::class,
      static fn (Container $container): QueryBus => new TestQueryBus($container, [
        ForgottenPasswordToken::class => GetMelomaniacByForgottenPasswordToken::class,
      ])
    )
    ->set(
      CommandBus::class,
      static fn (Container $container): CommandBus => new TestCommandBus($container, [
        Registration::class => Register::class,
        ForgottenPasswordRequest::class => RequestForgottenPassword::class,
      ])
    );
};
