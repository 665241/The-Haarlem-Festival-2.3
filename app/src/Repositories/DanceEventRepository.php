<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Models\DanceEventModel;
use App\ViewModels\DanceEventViewModel;
use App\Models\Enums\EventTypeEnum;
use App\Repositories\Interfaces\IDanceEventRepository;
use PDO;
use Throwable;

class DanceEventRepository extends Repository implements IDanceEventRepository
{
    /**
     * Get all active dance events (not soft deleted),
     * ordered by start date and time.
     */
    public function getAllActive(): array
    {
        $stmt = $this->connection->prepare("
            SELECT *
            FROM DanceEvent
            WHERE deleted_at IS NULL
            ORDER BY StartDateTime ASC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, DanceEventModel::class);
    }

    /**
     * Get one dance event by its ID.
     */
    public function getById(int $id): ?DanceEventModel
    {
        $stmt = $this->connection->prepare("
            SELECT *
            FROM DanceEvent
            WHERE DanceEventID = :DanceEventID
              AND deleted_at IS NULL
        ");

        $stmt->execute([
            'DanceEventID' => $id
        ]);

        $event = $stmt->fetchObject(DanceEventModel::class);

        return $event ?: null;
    }

    /**
     * Get all dance events for one artist/DJ.
     * We also join the general Event table and the DanceVenue table.
     */
    public function getEventsForArtist(int $artistId, EventTypeEnum $eventType): array
    {
       $sql = "
    SELECT
        e.id AS EventID,
        de.DanceEventID,
        de.DisplayTitle,
        de.StartDateTime,
        de.EndDateTime,
        de.Price,
        v.VenueName,
        v.Location,
        v.Capacity
    FROM Event e
    JOIN DanceEvent de ON de.DanceEventID = e.subEventId
    JOIN DanceVenue v ON v.DanceVenueID = de.DanceVenueID
    WHERE e.eventType = :eventType
      AND de.ArtistID = :artistId
      AND de.deleted_at IS NULL
    ORDER BY de.StartDateTime ASC
";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'eventType' => $eventType->value,
            'artistId' => $artistId
        ]);

        return $stmt->fetchAll(PDO::FETCH_CLASS, DanceEventViewModel::class);
    }

    /**
     * Get venue information for one dance event.
     * Useful when showing location details.
     */
    public function getVenueInfoByDanceEventId(int $danceEventId): ?array
    {
        $sql = "
            SELECT
                v.VenueName,
                v.Location,
                v.Capacity
            FROM DanceEvent de
            JOIN DanceVenue v ON v.DanceVenueID = de.DanceVenueID
            WHERE de.DanceEventID = :danceEventId
              AND de.deleted_at IS NULL
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'danceEventId' => $danceEventId
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /**
     * Create a new dance event and also insert the matching row
     * into the general Event table.
     */
    public function create(DanceEventModel $event): bool
    {
        $this->connection->beginTransaction();

        try {
            $stmt = $this->connection->prepare("
                INSERT INTO DanceEvent (ArtistID, DanceVenueID, StartDateTime, EndDateTime, Price)
                VALUES (:ArtistID, :DanceVenueID, :StartDateTime, :EndDateTime, :Price)
            ");

            $success = $stmt->execute([
                'ArtistID' => $event->getArtistId(),
                'DanceVenueID' => $event->getDanceVenueId(),
                'StartDateTime' => $event->getStartDateTime(),
                'EndDateTime' => $event->getEndDateTime(),
                'Price' => $event->getPrice()
            ]);

            if (!$success) {
                $this->connection->rollBack();
                return false;
            }

            // Get the new DanceEvent ID
            $danceEventId = (int)$this->connection->lastInsertId();

            // Create wrapper row in the generic Event table
            $wrapperSuccess = $this->createEventWrapper($danceEventId);

            if (!$wrapperSuccess) {
                $this->connection->rollBack();
                return false;
            }

            $this->connection->commit();
            return true;

        } catch (Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    /**
     * Update an existing dance event.
     */
    public function update(int $id, DanceEventModel $event): bool
    {
        $stmt = $this->connection->prepare("
            UPDATE DanceEvent
            SET ArtistID = :ArtistID,
                DanceVenueID = :DanceVenueID,
                StartDateTime = :StartDateTime,
                EndDateTime = :EndDateTime,
                Price = :Price,
                updated_at = GETDATE()
            WHERE DanceEventID = :DanceEventID
              AND deleted_at IS NULL
        ");

        return $stmt->execute([
            'DanceEventID' => $id,
            'ArtistID' => $event->getArtistId(),
            'DanceVenueID' => $event->getDanceVenueId(),
            'StartDateTime' => $event->getStartDateTime(),
            'EndDateTime' => $event->getEndDateTime(),
            'Price' => $event->getPrice()
        ]);
    }

    /**
     * Soft delete the dance event and remove the wrapper row
     * from the general Event table.
     */
    public function delete(int $id): bool
    {
        $this->connection->beginTransaction();

        try {
            $stmt = $this->connection->prepare("
                UPDATE DanceEvent
                SET deleted_at = GETDATE()
                WHERE DanceEventID = :DanceEventID
                  AND deleted_at IS NULL
            ");

            $success = $stmt->execute([
                'DanceEventID' => $id
            ]);

            if (!$success) {
                $this->connection->rollBack();
                return false;
            }

            $wrapperSuccess = $this->deleteEventWrapper($id);

            if (!$wrapperSuccess) {
                $this->connection->rollBack();
                return false;
            }

            $this->connection->commit();
            return true;

        } catch (Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    /**
     * Helper method:
     * Add a matching generic row into the Event table.
     */
    private function createEventWrapper(int $subEventId): bool
    {
        $stmt = $this->connection->prepare("
            INSERT INTO Event (eventType, subEventId)
            VALUES (:eventType, :subEventId)
        ");

        return $stmt->execute([
            'eventType' => EventTypeEnum::DanceEvent->value,
            'subEventId' => $subEventId
        ]);
    }

    /**
     * Helper method:
     * Delete the matching generic row from the Event table.
     */
    private function deleteEventWrapper(int $subEventId): bool
    {
        $stmt = $this->connection->prepare("
            DELETE FROM Event
            WHERE eventType = :eventType
              AND subEventId = :subEventId
        ");

        return $stmt->execute([
            'eventType' => EventTypeEnum::DanceEvent->value,
            'subEventId' => $subEventId
        ]);
    }
}