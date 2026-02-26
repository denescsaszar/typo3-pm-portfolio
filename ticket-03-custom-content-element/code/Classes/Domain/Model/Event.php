<?php
declare(strict_types=1);

namespace DRK\DrkEvents\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Model fuer eine DRK-Veranstaltung.
 * Bildet die Datenbank-Tabelle tx_drkevents_domain_model_event ab.
 */
class Event extends AbstractEntity
{
    protected string $title = '';
    protected ?\DateTime $eventDate = null;
    protected string $location = '';
    protected string $address = '';
    protected string $description = '';
    protected string $category = '';
    protected int $maxParticipants = 0;
    protected bool $registrationRequired = false;
    protected string $registrationLink = '';

    // --- Getter ---

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getEventDate(): ?\DateTime
    {
        return $this->eventDate;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getMaxParticipants(): int
    {
        return $this->maxParticipants;
    }

    public function isRegistrationRequired(): bool
    {
        return $this->registrationRequired;
    }

    public function getRegistrationLink(): string
    {
        return $this->registrationLink;
    }

    /**
     * Hilfsmethode: Gibt das Kategorie-Label zurueck.
     */
    public function getCategoryLabel(): string
    {
        return match($this->category) {
            'blutspende' => 'Blutspende',
            'erste-hilfe' => 'Erste-Hilfe-Kurs',
            'ehrenamt' => 'Ehrenamt',
            default => 'Sonstige',
        };
    }

    /**
     * Hilfsmethode: Prueft ob das Event in der Zukunft liegt.
     */
    public function isUpcoming(): bool
    {
        return $this->eventDate !== null && $this->eventDate > new \DateTime();
    }
}
