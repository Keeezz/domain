<?php

declare(strict_types=1);

namespace Keez\Domain\Tests\Music;

use Keez\Domain\Music\Music;
use Keez\Domain\Music\MusicGateway;
use Keez\Domain\Tests\CommandTestCase;
use Keez\Domain\Music\CreateMusic\MusicCreated;
use Keez\Domain\Music\CreateMusic\MusicCreation;

final class CreateMusicTest extends CommandTestCase
{
  public function testShouldCreateAMusic(): void
  {
    $musicCreation = new MusicCreation(title: 'Music 1', duration: '4:32', slug: 'music-1');

    $this->commandBus->execute($musicCreation);

    $musicGateway = $this->container->get(MusicGateway::class);
    $music = $musicGateway->getMusicBySlug('music-1');

    self::assertInstanceOf(Music::class, $music);
    self::assertSame('music-1', $music->slug());
    self::assertTrue($this->eventDispatcher->hasDispatched(MusicCreated::class));
  }
}
