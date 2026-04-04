<?php
namespace App\Repositories;

use App\Repositories\Interfaces\ITicketRepository; 
use App\Framework\Repository;
use App\Models\TicketModel;
use App\Models\EventModel;
use PDO;

class TicketRepository extends Repository implements ITicketRepository
{
    public function savePaidTicket(TicketModel $ticket, string $stripeId): bool
    {
        $sql = "INSERT INTO Tickets (
                    user_id, 
                    event_id, 
                    sub_event_id, 
                    number_of_people, 
                    unit_price, 
                    total_price, 
                    [status], 
                    stripe_session_id,
                    unique_ticket_token, 
                    is_scanned, 
                    program_item_id
                ) 
                VALUES (:uid, :eid, :subid, :count, :uprice, :tprice, 'paid', :stripe, :token, 0, :program_id)";

        $stmt = $this->connection->prepare($sql);

        $token = bin2hex(random_bytes(16));

        return $stmt->execute([
            'uid'    => $ticket->getUser() ? $ticket->getUser()->getId() : null,
            'eid'    => $ticket->getEvent()->getId(),
            'subid'  => $ticket->getEvent()->getSubEventId(),
            'count'  => $ticket->getNumberOfPeople(),
            'uprice' => $ticket->getUnitPrice(),
            'tprice' => $ticket->getTotalPrice(),
            'stripe' => $stripeId,
            'token'  => $token, 
            'program_id' => $ticket->getProgramItemId() 
        ]);
    }

    public function getByToken(string $token): ?array //retrieves a ticket by its unique id, used while scanning to validate the tickets.
    {
        try {
            $sql = "SELECT * FROM Tickets WHERE unique_ticket_token = :token";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(['token' => $token]);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row ?: null;
        } catch (\Exception $e) {
            error_log("Error fetching ticket by token: " . $e->getMessage());
            return null;
        }
    }
    
    public function markAsScanned(string $token): bool  //marks tickets as scanned preventing it from being used again
    {
        try {
            $sql = "UPDATE Tickets
                SET is_scanned = 1
                WHERE unique_ticket_token = :token";

            $stmt = $this->connection->prepare($sql);

            return $stmt->execute(['token' => $token]);
        } catch (\Exception $e) {
            error_log("Error marking ticket as scanned: " . $e->getMessage());
            return false;
        }
    }

    public function savePendingTicket(TicketModel $ticket, string $tempOrderId): bool 
    {
        $sql = "INSERT INTO Tickets (
                    user_id, event_id, sub_event_id, number_of_people, 
                    unit_price, total_price, [status], stripe_session_id,
                    unique_ticket_token, is_scanned, created_at,
                    program_item_id
                ) 
                VALUES (:uid, :eid, :subid, :count, :uprice, :tprice, 'pending', :orderId, :token, 0, GETDATE(), :program_id)";

        $stmt = $this->connection->prepare($sql);
        $token = bin2hex(random_bytes(16));

        return $stmt->execute([
            'uid'        => $ticket->getUser() ? $ticket->getUser()->getId() : null,
            'eid'        => $ticket->getEvent()->getId(),
            'subid'      => $ticket->getEvent()->getSubEventId(),
            'count'      => $ticket->getNumberOfPeople(),
            'uprice'     => $ticket->getUnitPrice(),
            'tprice'     => $ticket->getTotalPrice(),
            'orderId'    => $tempOrderId, 
            'token'      => $token,
            'program_id' => $ticket->getProgramItemId() 
        ]);
    }

    public function updateTicketsToPaid(string $orderId, string $actualStripeId): bool 
    {
        $sql = "UPDATE Tickets SET [status] = 'paid'
                WHERE stripe_session_id = :orderId AND [status] = 'pending'";
        
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            'orderId' => $orderId
        ]);
    }

    public function markAsExpired(string $orderId): bool 
    {
        $sql = "UPDATE Tickets 
                SET [status] = 'expired' 
                WHERE stripe_session_id = :orderId 
                AND [status] = 'pending'";
                
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute(['orderId' => $orderId]);
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM Tickets ORDER BY id DESC";
        $stmt = $this->connection->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllWithDetails(): array
    {
        $sql = "
            SELECT 
                t.id,
                t.user_id,
                u.Email,
                u.FullName,
                t.event_id,
                e.eventType,
                t.sub_event_id,
                t.number_of_people,
                t.unit_price,
                t.total_price,
                t.status,
                t.is_scanned, 
                t.created_at
            FROM Tickets t
            LEFT JOIN Users u ON u.Id = t.user_id
            LEFT JOIN Event e ON e.id = t.event_id
            ORDER BY t.id DESC
        ";

        $stmt = $this->connection->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTicketsByOrderId(string $orderId): array 
    {
        $sql = "SELECT * FROM Tickets 
                WHERE stripe_session_id = :orderId 
                AND [status] = 'pending'";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['orderId' => $orderId]); 
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // In App/Repositories/TicketRepository.php

    public function getTicketsByUserId(int $userId): array
    {
        $sql = "SELECT t.*, e.eventType, e.subEventId 
            FROM tickets t
            JOIN [event] e ON t.event_id = e.id
            WHERE t.user_id = :userId";
                
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $tickets = [];
        foreach ($rows as $row) {
            // if the DB has 'jazz', this returns EventTypeEnum::JazzEvent
            $enumType = \App\Models\Enums\EventTypeEnum::tryFrom($row['eventType']);

            //passing null for details because TicketService->hydrateTickets() will fill them
            $event = new \App\Models\EventModel(
                (int)$row['event_id'],
                $enumType,
                (int)$row['sub_event_id']
            );

            $ticket = new \App\Models\TicketModel(
                (int)$row['id'],
                $event,
                null, 
                (int)$row['number_of_people'],
                $row['unique_ticket_token'] ?? null
            );

            $tickets[] = $ticket;
        }

        return $tickets;
    }

}