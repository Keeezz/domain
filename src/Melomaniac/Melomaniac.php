<?php

namespace Keez\Domain\Melomaniac;

use Symfony\Component\Uid\Ulid;
use Symfony\Component\Uid\Uuid;

final class Melomaniac
{
    private Ulid $id;

    private string $email;

    private string $nickname;

    private string $password;

    private ?Gender $gender = null;

    private ?string $avatar = null;

    private ?Uuid $registrationToken = null;

    private ?Uuid $forgottenPasswordToken = null;

    private ?\DateTimeInterface $forgottenPasswordExpiredAt = null;

    private ?\DateTimeInterface $registeredAt = null;

    public static function create(
    Ulid $id,
    string $email,
    string $nickname,
    string $password,
    ?Gender $gender = null,
    ?string $avatar = null,
    ?Uuid $registrationToken = null,
    ?\DateTimeInterface $registeredAt = null,
    ?Uuid $forgottenPasswordToken = null,
    ?\DateTimeInterface $forgottenPasswordExpiredAt = null
  ): self {
        $melomaniac = new self();
        $melomaniac->id = $id;
        $melomaniac->gender = $gender;
        $melomaniac->email = $email;
        $melomaniac->nickname = $nickname;
        $melomaniac->password = $password;
        $melomaniac->avatar = $avatar;
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

    public function nickname(): string
    {
        return $this->nickname;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function gender(): ?Gender
    {
        return $this->gender;
    }

    public function avatar(): ?string
    {
        return $this->avatar;
    }

    public function registrationToken(): ?Uuid
    {
        return $this->registrationToken;
    }

    public function registeredAt(): ?\DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function forgottenPasswordToken(): ?Uuid
    {
        return $this->forgottenPasswordToken;
    }

    public function forgottenPasswordExpiredAt(): ?\DateTimeInterface
    {
        return $this->forgottenPasswordExpiredAt;
    }

    public function hasForgottenPasswordTokenExpired(): bool
    {
        return null !== $this->forgottenPasswordExpiredAt && $this->forgottenPasswordExpiredAt < new \DateTimeImmutable();
    }

    public function update(string $email, ?Gender $gender, string $nickname, ?string $avatar = null): void
    {
        $this->email = $email;
        $this->nickname = $nickname;
        $this->avatar = $avatar;
        $this->gender = $gender;
    }

    public function prepareValidationOfRegistration(?Uuid $registrationToken): void
    {
        $this->registrationToken = $registrationToken;
    }

    public function validateRegistration(): void
    {
        $this->registrationToken = null;
        $this->registeredAt = new \DateTimeImmutable();
    }

    public function newPassword(string $newPassword): void
    {
        $this->password = $newPassword;
    }

    public function forgotPassword(Uuid $forgottenPasswordToken): void
    {
        $this->forgottenPasswordToken = $forgottenPasswordToken;
        $this->forgottenPasswordExpiredAt = new \DateTimeImmutable('24 hours');
    }

    public function hasValidRegistration(): bool
    {
        return null !== $this->registeredAt && null === $this->registrationToken;
    }
}
