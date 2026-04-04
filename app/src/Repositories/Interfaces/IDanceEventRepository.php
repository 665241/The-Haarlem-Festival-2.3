<?php

namespace App\Repositories\Interfaces;

use App\Models\DanceEventModel;
use App\Models\Enums\EventTypeEnum;

interface IDanceEventRepository
{
    /**
     * Get all active dance events
     */
    public function getAllActive(): array;

    /**
     * Get one dance event by its id
     */
    public function getById(int $id): ?DanceEventModel;

    /**
     * Get all dance events for one artist/DJ
     */
    public function getEventsForArtist(int $artistId, EventTypeEnum $eventType): array;

    /**
     * Get venue information for a specific dance event
     */
    public function getVenueInfoByDanceEventId(int $danceEventId): ?array;

    /**
     * Create a new dance event
     */
    public function create(DanceEventModel $event): bool;

    /**
     * Update an existing dance event
     */
    public function update(int $id, DanceEventModel $event): bool;

    /**
     * Delete a dance event
     */
    public function delete(int $id): bool;
}