# Ticket 03: Custom Content Element — Event-Kalender

## Kunde
**Deutsches Rotes Kreuz (DRK) — Landesverband Berlin** — Einer der groessten Wohltaetigkeitsverbaende Deutschlands.

## Szenario
Das DRK Berlin moechte auf seiner TYPO3-Website einen Event-Kalender einfuehren. Redakteure sollen Veranstaltungen (Blutspendetermine, Erste-Hilfe-Kurse, Ehrenamts-Treffen) im Backend anlegen und auf verschiedenen Seiten anzeigen koennen.

**Anforderungen des Kunden:**
- Redakteure koennen Events im TYPO3-Backend anlegen (Titel, Datum, Ort, Beschreibung, Kategorie)
- Listenansicht: Kommende Events chronologisch sortiert
- Detailansicht: Einzelnes Event mit allen Infos
- Filterung nach Kategorie (Blutspende, Erste Hilfe, Ehrenamt)
- Vergangene Events automatisch ausblenden
- Barrierefreiheit (oeffentlicher Auftraggeber)

Der Entwickler hat die Extension gebaut und bittet um PM-Review der Architektur und des Codes.

## Aufgabe als PM
1. Extension-Struktur (MVC) verstehen
2. PHP-Code lesen: Controller, Model, Repository
3. TCA-Konfiguration pruefen (Backend-Formulare)
4. Fluid-Templates fuer List/Detail bewerten
5. Feedback an Entwickler und Status an Kunden

## Relevante Dateien
- `code/Classes/Controller/EventController.php` — Controller
- `code/Classes/Domain/Model/Event.php` — Datenmodell
- `code/Classes/Domain/Repository/EventRepository.php` — Datenbankabfragen
- `code/Configuration/TCA/tx_drkevents_domain_model_event.php` — Backend-Formular
- `code/Configuration/TypoScript/setup.typoscript` — Plugin-Registrierung
- `code/Resources/Private/Templates/Event/List.html` — Listenansicht
- `code/Resources/Private/Templates/Event/Show.html` — Detailansicht
