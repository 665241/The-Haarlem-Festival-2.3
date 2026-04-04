<?php

namespace App\Repositories\Interfaces; 

use App\Models\TicketModel; 

interface ITicketRepository {

    public function getAll(): array; 
    public function getAllWithDetails(): array; 
    public function getTicketsByOrderId(string $orderId): array; 
    public function savePaidTicket(TicketModel $ticket, string $stripeId): bool; 
    public function getByToken(string $token): ?array;
    public function markAsScanned(string $token): bool; 
    public function savePendingTicket(TicketModel $ticket, string $tempOrderId): bool;
    public function updateTicketsToPaid(string $orderId, string $actualStripeId): bool; 
    public function markAsExpired(string $orderId): bool;  
    public function getTicketsByUserId(int $userId): array;

}