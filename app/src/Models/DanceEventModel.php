<?php

namespace App\Models;

class DanceEventModel
{
    private int $DanceEventID;
    private int $ArtistID;
    private int $DanceVenueID;
    private string $StartDateTime;
    private ?string $EndDateTime = null;
    private float $Price;
    private ?string $DisplayTitle = null;
    private ?string $VenueName = null;
    private ?string $deleted_at = null;
    private ?string $updated_at = null;
    private ?string $created_at = null;

    public function getId(): int
    {
        return $this->DanceEventID;
    }

    public function getArtistId(): int
    {
        return $this->ArtistID;
    }

    public function getDanceVenueId(): int
    {
        return $this->DanceVenueID;
    }

    public function getStartDateTime(): string
    {
        return $this->StartDateTime;
    }

    public function getEndDateTime(): ?string
    {
        return $this->EndDateTime;
    }

    public function getPrice(): float
    {
        return $this->Price;
    }

    public function getDisplayTitle(): ?string
    {
        return $this->DisplayTitle;
    }

    public function getVenueName(): ?string
    {
        return $this->VenueName;
    }

    public function getDeletedAt(): ?string
    {
        return $this->deleted_at;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setArtistId(int $artistId): void
    {
        $this->ArtistID = $artistId;
    }

    public function setDanceVenueId(int $danceVenueId): void
    {
        $this->DanceVenueID = $danceVenueId;
    }

    public function setStartDateTime(string $startDateTime): void
    {
        $this->StartDateTime = $startDateTime;
    }

    public function setEndDateTime(?string $endDateTime): void
    {
        $this->EndDateTime = $endDateTime;
    }

    public function setPrice(float $price): void
    {
        $this->Price = $price;
    }

    public function setVenueName(?string $venueName): void
    {
        $this->VenueName = $venueName;
    }
}