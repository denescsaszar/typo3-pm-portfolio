# PM Response — Ticket 03: Custom Content Element — Event-Kalender

## Zusammenfassung fuer den Kunden

Liebe Frau Hartmann,

der Event-Kalender fuer die DRK-Website ist fertig entwickelt. Hier eine Uebersicht:

**Was Ihre Redakteure jetzt koennen:**
- Events im TYPO3-Backend anlegen mit Titel, Datum, Ort, Beschreibung und Kategorie
- Drei Kategorien stehen zur Verfuegung: Blutspende, Erste-Hilfe-Kurse, Ehrenamt
- Optional: Maximale Teilnehmerzahl und Anmelde-Link angeben

**Was die Besucher sehen:**
- Eine Listenansicht mit allen kommenden Veranstaltungen, nach Datum sortiert
- Filter-Buttons zum Filtern nach Kategorie
- Detailseite mit allen Informationen und Anmelde-Button
- Vergangene Events werden automatisch ausgeblendet

**Barrierefreiheit:**
- Screenreader-optimiert (ARIA-Labels, semantisches HTML)
- Strukturierte Daten (Schema.org) fuer bessere Google-Darstellung
- Tastaturnavigation funktioniert durchgaengig

Wir wuerden die Funktion gerne am Freitag auf dem Staging-System zeigen. Passt 11 Uhr?

Beste Gruesse

---

## Technische Rueckmeldung ans Entwicklerteam

### Architektur-Review der Extension

**Status: Freigabe mit Anmerkungen**

Saubere MVC-Architektur. Hier mein Review:

### Was passt
- **Controller:** Schlank, nur 2 Actions (list, show). Filterlogik korrekt im Controller, nicht im Template
- **Model:** Alle Felder abgedeckt, `getCategoryLabel()` und `isUpcoming()` als Hilfsmethoden — gut
- **Repository:** `findUpcoming()` filtert vergangene Events korrekt raus. `findByCategory()` kombiniert Datum + Kategorie
- **TCA:** Backend-Formular ist uebersichtlich in Tabs aufgeteilt (Allgemein, Ort, Details, Zugriff). Kategorie als Select-Dropdown
- **Templates:** List mit Filter + Fallback fuer leere Liste. Detail mit Schema.org Markup
- **Partial:** Event-Card ist wiederverwendbar (kann auch auf der Startseite als Teaser genutzt werden)

### Offene Punkte

1. **Pagination:** Bei vielen Events brauchen wir Seitenblaetterung. TYPO3 v12 hat den `PaginateViewHelper` entfernt — bitte `GeorgRinger/NumberedPagination` oder eigene Loesung:
   ```php
   use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
   ```

2. **ICS-Export:** Kunde hat im letzten Meeting erwaehnt, dass Besucher Events in ihren Kalender exportieren wollen. Ist das im Scope? Aufwand schaetze ich auf 4-6h.

3. **Caching:** Repository-Queries werden bei jedem Seitenaufruf ausgefuehrt. Bei der erwarteten Traffic-Last (DRK-Website) sollten wir Caching einbauen:
   ```typoscript
   plugin.tx_drkevents._LOCAL_LANG.default.cache.lifetime = 900
   ```

4. **Suche:** Aktuell nur Kategorie-Filter. Sollen wir eine PLZ-basierte Umkreissuche ergaenzen? Das waere ein eigenes Ticket (geschaetzt 16-24h).

5. **Backend-Vorschau:** Redakteure sehen im Backend nur den Plugin-Platzhalter. Eine Backend-Preview waere hilfreich:
   ```php
   // CType Preview fuer das Backend
   $GLOBALS['TCA']['tt_content']['types']['drkevents_list']['previewRenderer']
       = \DRK\DrkEvents\Preview\EventListPreviewRenderer::class;
   ```

### Vorgeschlagene Timeline
- Mi: Pagination + Caching einbauen (4h)
- Do: Internes QA + Barrierefreiheitstest
- Fr 11:00: Staging-Demo fuer DRK
- Mo: Feedback einarbeiten
- Di: Redaktionsschulung vorbereiten
- Mi: Go-Live

---

## Erkenntnisse als PM

### MVC-Pattern in TYPO3 (Extbase):
- **Model (Event.php):** Definiert die Datenstruktur — welche Felder hat ein Event?
- **Controller (EventController.php):** Verarbeitet Anfragen — welche Events werden geladen und wie?
- **View (Fluid Templates):** Stellt die Daten dar — wie sieht die Liste/Detailseite aus?
- **Repository (EventRepository.php):** Datenbankschicht — spezialisierte Abfragen (findUpcoming, findByCategory)

### TCA verstehen:
- TCA = Table Configuration Array
- Definiert wie das Backend-Formular aussieht
- `types` bestimmt welche Felder in welcher Reihenfolge angezeigt werden
- `columns` definiert jeden einzelnen Feldtyp (input, select, datetime, check, etc.)
- `ctrl` definiert Systemfelder (deleted, hidden, sortierung)

### PHP-Code lesen als PM:
- `public function listAction()` = wird aufgerufen wenn die Listenansicht angezeigt wird
- `$this->eventRepository->findUpcoming()` = "hole alle kommenden Events aus der Datenbank"
- `$this->view->assign('events', $events)` = "gib die Events ans Template weiter"
- `$query->matching($query->greaterThanOrEqual(...))` = "WHERE event_date >= heute"

### Warum das fuer die PM-Rolle relevant ist:
- Custom Content Elements sind das Herzstueck vieler TYPO3-Projekte
- Als PM muss ich den Aufwand fuer neue Funktionen einschaetzen koennen
- Ich muss verstehen was Redakteure im Backend sehen und ob das intuitiv ist
- Ich muss technische Risiken erkennen (Performance, Caching, Skalierung)
- Die TCA-Konfiguration bestimmt die Redakteurserfahrung — das ist PM-Territorium
