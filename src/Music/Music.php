<?php

declare(strict_types=1);

namespace Keez\Domain\Music;

use Symfony\Component\Uid\Ulid;

final class Music
{
    private Ulid $id;

    private string $title;

    private string $duration;

    private string $slug;

    public static function create(
    Ulid $id,
    string $title,
    string $duration,
    string $slug
  ): self {
        $music = new self();
        $music->id = $id;
        $music->duration = $duration;
        $music->title = $title;
        $music->slug = $slug;

        return $music;
    }

    public function id(): Ulid
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function duration(): string
    {
        return $this->duration;
    }

    public function slug(): string
    {
        return $this->slug;
    }
}
