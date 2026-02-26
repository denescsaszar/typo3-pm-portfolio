<?php
declare(strict_types=1);

namespace DRK\DrkEvents\Controller;

use DRK\DrkEvents\Domain\Repository\EventRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Controller fuer die Event-Verwaltung.
 * Steuert Listen- und Detailansicht der DRK-Veranstaltungen.
 */
class EventController extends ActionController
{
    public function __construct(
        protected EventRepository $eventRepository
    ) {}

    /**
     * Listenansicht: Zeigt kommende Events, optional gefiltert nach Kategorie.
     */
    public function listAction(string $category = ''): ResponseInterface
    {
        if ($category !== '') {
            $events = $this->eventRepository->findByCategory($category);
        } else {
            $events = $this->eventRepository->findUpcoming();
        }

        $this->view->assignMultiple([
            'events' => $events,
            'currentCategory' => $category,
            'categories' => ['blutspende', 'erste-hilfe', 'ehrenamt'],
        ]);

        return $this->htmlResponse();
    }

    /**
     * Detailansicht: Zeigt ein einzelnes Event mit allen Informationen.
     */
    public function showAction(\DRK\DrkEvents\Domain\Model\Event $event): ResponseInterface
    {
        $this->view->assign('event', $event);
        return $this->htmlResponse();
    }
}
