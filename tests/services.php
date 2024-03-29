<?php

declare(strict_types=1);

use Keez\Domain\Melomaniac\GetMelomaniacByForgottenPasswordToken\ForgottenPasswordToken;
use Keez\Domain\Melomaniac\GetMelomaniacByForgottenPasswordToken\GetMelomaniacByForgottenPasswordToken;
use Keez\Domain\Melomaniac\MelomaniacGateway;
use Keez\Domain\Melomaniac\Register\Register;
use Keez\Domain\Melomaniac\Register\Registration;
use Keez\Domain\Melomaniac\Register\UniqueEmailValidator as RegisterUniqueEmailValidator;
use Keez\Domain\Melomaniac\RequestForgottenPassword\ForgottenPasswordRequest;
use Keez\Domain\Melomaniac\RequestForgottenPassword\RequestForgottenPassword;
use Keez\Domain\Melomaniac\ResetPassword\NewPassword as ResetPasswordNewPassword;
use Keez\Domain\Melomaniac\ResetPassword\ResetPassword;
use Keez\Domain\Melomaniac\UpdatePassword\CurrentPasswordValidator;
use Keez\Domain\Melomaniac\UpdatePassword\NewPassword as UpdatePasswordNewPassword;
use Keez\Domain\Melomaniac\UpdatePassword\UpdatePassword;
use Keez\Domain\Melomaniac\UpdateProfile\Profile;
use Keez\Domain\Melomaniac\UpdateProfile\UniqueEmailValidator as UpdateProfileUniqueEmailValidator;
use Keez\Domain\Melomaniac\UpdateProfile\UpdateProfile;
use Keez\Domain\Melomaniac\ValidateRegistration\RegistrationTokenExistsValidator;
use Keez\Domain\Melomaniac\ValidateRegistration\ValidateRegistration;
use Keez\Domain\Melomaniac\ValidateRegistration\ValidationOfRegistration;
use Keez\Domain\Music\CreateMusic\CreateMusic;
use Keez\Domain\Music\CreateMusic\MusicCreation;
use Keez\Domain\Music\MusicGateway;
use Keez\Domain\Shared\Command\CommandBus;
use Keez\Domain\Shared\EventDispatcher\EventDispatcher;
use Keez\Domain\Shared\Query\QueryBus;
use Keez\Domain\Shared\Uid\UlidGeneratorInterface;
use Keez\Domain\Shared\Uid\UuidGeneratorInterface;
use Keez\Domain\Tests\Application\Container\Container;
use Keez\Domain\Tests\Application\CQRS\TestCommandBus;
use Keez\Domain\Tests\Application\CQRS\TestQueryBus;
use Keez\Domain\Tests\Application\EventDispatcher\TestEventDispatcher;
use Keez\Domain\Tests\Application\Repository\InMemoryMelomaniacRepository;
use Keez\Domain\Tests\Application\Repository\InMemoryMusicRepository;
use Keez\Domain\Tests\Application\Uid\UlidGenerator;
use Keez\Domain\Tests\Application\Uid\UuidGenerator;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

return function (Container $container): void {
    $container
      ->set(
          CreateMusic::class,
          static fn (Container $container): CreateMusic => new CreateMusic(
              $container->get(UlidGeneratorInterface::class),
              $container->get(MusicGateway::class),
              $container->get(EventDispatcher::class)
          )
      )
      ->set(
          MusicGateway::class,
          static fn (Container $container): MusicGateway => new InMemoryMusicRepository()
      )
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
          ResetPassword::class,
          static fn (Container $container): ResetPassword => new ResetPassword(
              $container->get(PasswordHasherInterface::class),
              $container->get(MelomaniacGateway::class)
          )
      )
      ->set(
          UpdatePassword::class,
          static fn (Container $container): UpdatePassword => new UpdatePassword(
              $container->get(PasswordHasherInterface::class),
              $container->get(MelomaniacGateway::class)
          )
      )
      ->set(
          UpdateProfile::class,
          static fn (Container $container): UpdateProfile => new UpdateProfile(
              $container->get(MelomaniacGateway::class)
          )
      )
      ->set(
          ValidateRegistration::class,
          static fn (Container $container): ValidateRegistration => new ValidateRegistration(
              $container->get(MelomaniacGateway::class)
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
          UpdateProfileUniqueEmailValidator::class,
          static fn (Container $container): UpdateProfileUniqueEmailValidator => new UpdateProfileUniqueEmailValidator(
              $container->get(MelomaniacGateway::class)
          )
      )
      ->set(
          RegisterUniqueEmailValidator::class,
          static fn (Container $container): RegisterUniqueEmailValidator => new RegisterUniqueEmailValidator(
              $container->get(MelomaniacGateway::class)
          )
      )
      ->set(
          CurrentPasswordValidator::class,
          static fn (Container $container): CurrentPasswordValidator => new CurrentPasswordValidator(
              $container->get(PasswordHasherInterface::class)
          )
      )
      ->set(
          RegistrationTokenExistsValidator::class,
          static fn (Container $container): RegistrationTokenExistsValidator => new RegistrationTokenExistsValidator(
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
            MusicCreation::class => CreateMusic::class,
            ForgottenPasswordRequest::class => RequestForgottenPassword::class,
            ResetPasswordNewPassword::class => ResetPassword::class,
            UpdatePasswordNewPassword::class => UpdatePassword::class,
            ValidationOfRegistration::class => ValidateRegistration::class,
            Profile::class => UpdateProfile::class,
          ])
      );
};
