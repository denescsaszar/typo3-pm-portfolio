# Ticket 02: Fluid Templates — Landingpage Redesign

## Kunde
**Berliner Baeder-Betriebe (BBB)** — Betreiber der oeffentlichen Schwimmbaeder in Berlin.

## Szenario
Die Berliner Baeder-Betriebe moechten eine neue Landingpage fuer ihre Sommerkampagne "Berlin schwimmt!". Die aktuelle Startseite ist veraltet und nicht mobiloptimiert.

**Anforderungen des Kunden:**
- Hero-Bereich mit grossem Hintergrundbild und Claim
- Kachel-Grid mit den 3 beliebtesten Freibaedern (Bild, Name, Oeffnungszeiten, Link)
- Aktuelle Meldungen (News-Teaser, max. 3 Stueck)
- Call-to-Action Button "Freibad in deiner Naehe finden"
- Responsive: Mobile First
- Barrierefreiheit (BITV 2.0 Konformitaet, da oeffentlicher Auftraggeber)

Der Entwickler hat das Fluid-Template, das Layout und zwei Partials erstellt und bittet um PM-Review vor dem Staging-Deploy.

## Aufgabe als PM
1. Fluid-Templates lesen und verstehen (Layout, Template, Partials)
2. Pruefen ob alle Kundenanforderungen abgedeckt sind
3. Barrierefreiheit im Template bewerten
4. Feedback an Entwickler und Statusupdate an Kunden formulieren

## Relevante Dateien
- `code/Layouts/Default.html` — Grundlayout
- `code/Templates/Page/Landingpage.html` — Landingpage-Template
- `code/Partials/Hero/Campaign.html` — Hero-Partial
- `code/Partials/Card/FreibadCard.html` — Freibad-Kachel-Partial
