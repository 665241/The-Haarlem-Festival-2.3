<?php

namespace App\Services\Interfaces;

use App\Models\DanceEventModel;
use App\Models\Enums\EventTypeEnum;

interface IDanceEventService
{
    public function getAllDanceEvents(): array;

    public function getDanceEventById(int $id): ?DanceEventModel;

    public function getEventsForArtist(int $artistId, EventTypeEnum $eventType): array;

    public function getVenueInfoByDanceEventId(int $danceEventId): ?array;

    public function createDanceEvent(DanceEventModel $event): bool;

    public function updateDanceEvent(int $id, DanceEventModel $event): bool;

    public function deleteDanceEvent(int $id): bool;
}
