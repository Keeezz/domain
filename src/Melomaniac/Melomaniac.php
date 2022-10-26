<?php

namespace Keez\Domain\Melomaniac;

use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Uid\Uuid;

final class Melomaniac
{
  private Ulid $id;

  private string $email;

  private string $password;

  private ?Uuid $registrationToken = null;

  private ?Uuid $forgottenPasswordToken = null;

  private ?DateTimeInterface $forgottenPasswordExpiredAt = null;

  private ?DateTimeInterface $registeredAt = null;

  public static function create(
    Ulid $id,
    string $email,
    string $password,
    ?Uuid $registrationToken = null,
    ?DateTimeInterface $registeredAt = null,
    ?Uuid $forgottenPasswordToken = null,
    ?DateTimeInterface $forgottenPasswordExpiredAt = null
  ): self {
    $melomaniac = new self();
    $melomaniac->id = $id;
    $melomaniac->email = $email;
    $melomaniac->password = $password;
    $melomaniac->registrationToken = $registrationToken;
    $melomaniac->registeredAt = $registeredAt;
    $melomaniac->forgottenPasswordToken = $forgottenPasswordToken;
    $melomaniac->forgottenPasswordExpiredAt = $forgottenPasswordExpiredAt;

    return $melomaniac;
  }

  public function id(): Ulid
  {
    return $this->id;
  }

  public function email(): string
  {
    return $this->email;
  }

  public function password(): string
  {
    return $this->password;
  }

  public function registrationToken(): ?Uuid
  {
    return $this->registrationToken;
  }

  public function registeredAt(): ?DateTimeInterface
  {
    return $this->registeredAt;
  }

  public function forgottenPasswordToken(): ?Uuid
  {
    return $this->forgottenPasswordToken;
  }

  public function forgottenPasswordExpiredAt(): ?DateTimeInterface
  {
    return $this->forgottenPasswordExpiredAt;
  }

  public function hasForgottenPasswordTokenExpired(): bool
  {
    return null !== $this->forgottenPasswordExpiredAt && $this->forgottenPasswordExpiredAt < new DateTimeImmutable();
  }

  public function update(string $email): void
  {
    $this->email = $email;
  }

  public function prepareValidationOfRegistration(?Uuid $registrationToken): void
  {
    $this->registrationToken = $registrationToken;
  }

  public function validateRegistration(): void
  {
    $this->registrationToken = null;
    $this->registeredAt = new DateTimeImmutable();
  }

  public function newPassword(string $newPassword): void
  {
    $this->password = $newPassword;
  }

  public function forgotPassword(Uuid $forgottenPasswordToken): void
  {
    $this->forgottenPasswordToken = $forgottenPasswordToken;
    $this->forgottenPasswordExpiredAt = new DateTimeImmutable('24 hours');
  }

  public function hasValidRegistration(): bool
  {
    return null !== $this->registeredAt && null === $this->registrationToken;
  }
}
