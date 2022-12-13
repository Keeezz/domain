<?php

declare(strict_types=1);

namespace Keez\Domain\Melomaniac\UpdateProfile;

use Keez\Domain\Melomaniac\Gender;
use Keez\Domain\Melomaniac\Melomaniac;
use Keez\Domain\Shared\Command\Command;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

#[UniqueEmail]
final class Profile implements Command
{
    #[Email]
    #[NotBlank]
    public string $email;

    #[NotBlank]
    public string $nickname;

    public ?Gender $gender = null;

    public ?string $avatar = null;

    public function __construct(public Melomaniac $melomaniac)
    {
        $this->email = $melomaniac->email();
        $this->nickname = $melomaniac->nickname();
        $this->gender = $melomaniac->gender();
        $this->avatar = $melomaniac->avatar();
    }
}
