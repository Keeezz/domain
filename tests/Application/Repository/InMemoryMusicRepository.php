<?php

declare(strict_types=1);

namespace Keez\Domain\Tests\Application\Repository;

use Keez\Domain\Music\Music;
use Keez\Domain\Music\MusicGateway;
use Symfony\Component\Uid\Ulid;

final class InMemoryMusicRepository implements MusicGateway
{
    /**
     * @var array<string, Music>
     */
    public array $musics = [];

    public function __construct()
    {
        $this->init();
    }

    public static function CreateMusic(int $index, string $ulid): Music
    {
        return Music::create(
            id: Ulid::fromString($ulid),
            title: sprintf('Music %d', $index),
            slug: sprintf('music-%d', $index),
            duration: sprintf('4:3%d', $index)
        );
    }

    public function init(): void
    {
        $this->musics = [
          '01GBJK7XV3YXQ51EHN9G5DAMYN' => self::CreateMusic(1, '01GBJK7XV3YXQ51EHN9G5DAMYN'),
          '01GBFF6QBSBH7RRTK6N0770BSY' => self::CreateMusic(2, '01GBFF6QBSBH7RRTK6N0770BSY'),
        ];
    }

    public function create(Music $music): void
    {
        $this->musics[(string) $music->id()] = $music;
    }

    public function getMusicBySlug(string $slug): Music
    {
        foreach ($this->musics as $music) {
            if ($music->slug() === $slug) {
                return $music;
            }
        }

        return null;
    }
}
