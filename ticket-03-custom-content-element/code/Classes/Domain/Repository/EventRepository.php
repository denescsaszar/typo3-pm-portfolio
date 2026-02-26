<?php
declare(strict_types=1);

namespace DRK\DrkEvents\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository fuer Event-Datenbankabfragen.
 * Stellt spezialisierte Finder-Methoden bereit.
 */
class EventRepository extends Repository
{
    /**
     * Standard-Sortierung: nach Datum aufsteigend (naechstes Event zuerst).
     */
    protected $defaultOrderings = [
        'eventDate' => QueryInterface::ORDER_ASCENDING,
    ];

    /**
     * Findet alle kommenden Events (Datum >= heute).
     */
    public function findUpcoming(): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->greaterThanOrEqual('eventDate', new \DateTime('today'))
        );
        return $query->execute();
    }

    /**
     * Findet kommende Events einer bestimmten Kategorie.
     */
    public function findByCategory(string $category): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->greaterThanOrEqual('eventDate', new \DateTime('today')),
                $query->equals('category', $category)
            )
        );
        return $query->execute();
    }

    /**
     * Findet die naechsten X Events (fuer Teaser/Widgets).
     */
    public function findNext(int $limit = 3): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->greaterThanOrEqual('eventDate', new \DateTime('today'))
        );
        $query->setLimit($limit);
        return $query->execute();
    }
}
