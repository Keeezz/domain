<?php

declare(strict_types=1);

namespace Keez\Domain\Music\CreateMusic;

use Keez\Domain\Music\Music;
use Keez\Domain\Music\MusicGateway;
use Keez\Domain\Shared\Command\CommandHandler;
use Keez\Domain\Shared\EventDispatcher\EventDispatcher;
use Keez\Domain\Shared\Uid\UlidGeneratorInterface;

final class CreateMusic implements CommandHandler
{
    public function __construct(
    private UlidGeneratorInterface $ulidGenerator,
    private MusicGateway $musicGateway,
    private EventDispatcher $eventDispatcher
  ) {
    }

    public function __invoke(MusicCreation $musicCreation): void
    {
        $music = Music::create(
            id: $this->ulidGenerator->generate(),
            title: $musicCreation->title,
            duration: $musicCreation->duration,
            slug: $musicCreation->slug
        );

        $this->musicGateway->create($music);

        $this->eventDispatcher->dispatch(new MusicCreated($music));
    }
}
