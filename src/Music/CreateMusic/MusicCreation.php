<?php

declare(strict_types=1);

namespace Keez\Domain\Music\CreateMusic;

use Keez\Domain\Shared\Command\Command;
use Symfony\Component\Validator\Constraints\NotBlank;

final class MusicCreation implements Command
{
  public function __construct(
    #[NotBlank]
    public string $title,
    #[NotBlank]
    public string $duration,
    #[NotBlank]
    public string $slug
  ) {
  }
}
