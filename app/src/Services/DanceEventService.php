<?php

namespace App\Services;

use App\Models\DanceEventModel;
use App\Repositories\Interfaces\IDanceEventRepository;
use App\Services\Interfaces\IDanceEventService;
use App\Models\Enums\EventTypeEnum;

class DanceEventService implements IDanceEventService
{
    // Repository that talks to the database
    private IDanceEventRepository $repository;

    /**
     * Constructor
     * Inject the dance event repository into the service
     */
    public function __construct(IDanceEventRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all active dance events
     */
    public function getAllDanceEvents(): array
    {
        return $this->repository->getAllActive();
    }

    /**
     * Get one dance event by its ID
     */
    public function getDanceEventById(int $id): ?DanceEventModel
    {
        return $this->repository->getById($id);
    }

    /**
     * Get all dance events for one artist/DJ
     */
    public function getEventsForArtist(int $artistId, EventTypeEnum $eventType): array
    {
        return $this->repository->getEventsForArtist($artistId, $eventType);
    }

    /**
     * Get venue information for one dance event
     */
    public function getVenueInfoByDanceEventId(int $danceEventId): ?array
    {
        return $this->repository->getVenueInfoByDanceEventId($danceEventId);
    }

    /**
     * Create a new dance event
     */
    public function createDanceEvent(DanceEventModel $event): bool
    {
        return $this->repository->create($event);
    }

    /**
     * Update an existing dance event
     */
    public function updateDanceEvent(int $id, DanceEventModel $event): bool
    {
        return $this->repository->update($id, $event);
    }

    /**
     * Delete a dance event
     */
    public function deleteDanceEvent(int $id): bool
    {
        return $this->repository->delete($id);
    }
}