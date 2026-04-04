<?php

namespace App\ViewModels;

class DanceEventViewModel
{
    // ID from the general Event table
    private ?int $EventID = null;

    // ID from the DanceEvent table
    private int $DanceEventID;

    // Event timing
    private string $StartDateTime;
    private ?string $EndDateTime = null;

    // Ticket price
    private float $Price;

    // Venue information from DanceVenue table
    private ?string $VenueName = null;
    private ?string $Location = null;
    private ?int $Capacity = null;
    private ?string $DisplayTitle = null;

    /**
     * Get the general Event table ID
     */
    public function getEventID(): ?int
    {
        return $this->EventID;
    }

    /**
     * Get the DanceEvent table ID
     */
    public function getDanceEventID(): int
    {
        return $this->DanceEventID;
    }

    /**
     * Get start date and time
     */
    public function getStartDateTime(): string
    {
        return $this->StartDateTime;
    }

    /**
     * Get end date and time
     */
    public function getEndDateTime(): ?string
    {
        return $this->EndDateTime;
    }

    /**
     * Get ticket price
     */
    public function getPrice(): float
    {
        return $this->Price;
    }

    /**
     * Get venue name
     */
    public function getVenueName(): ?string
    {
        return $this->VenueName;
    }

    /**
     * Get location/address
     */
    public function getLocation(): ?string
    {
        return $this->Location;
    }

    /**
     * Get venue capacity
     */
    public function getCapacity(): ?int
    {
        return $this->Capacity;
    }
    public function getDisplayTitle(): ?string
{
    return $this->DisplayTitle;
}

}