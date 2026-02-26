# PM Response — Ticket 02: Fluid Templates — Landingpage Redesign

## Zusammenfassung fuer den Kunden

Liebe Frau Weber,

das Entwicklerteam hat die Landingpage fuer "Berlin schwimmt!" fertiggestellt. Hier der aktuelle Stand:

**Alle Anforderungen sind umgesetzt:**

1. **Hero-Bereich:** Grosses Kampagnenbild mit dem Claim "Berlin schwimmt!" und dem Untertitel "Entdecke die schoensten Freibaeder der Stadt". Der Call-to-Action Button "Freibad in deiner Naehe finden" ist prominent platziert.

2. **Freibad-Kacheln:** Die 3 beliebtesten Freibaeder werden als Karten mit Bild, Name, Adresse, Oeffnungszeiten und Link zur Detailseite angezeigt.

3. **Aktuelle Meldungen:** Die 3 neuesten News werden als Teaser mit Datum, Titel und Kurztext angezeigt.

4. **Responsive:** Die Seite ist Mobile First entwickelt — auf dem Smartphone werden die Kacheln untereinander angezeigt, auf dem Desktop nebeneinander.

5. **Barrierefreiheit:** Die Seite erfuellt die BITV 2.0 Anforderungen:
   - Skip-Navigation ("Zum Hauptinhalt springen")
   - Alle Bilder mit Alternativtexten
   - Semantische HTML-Struktur (header, main, footer, article)
   - Screenreader-optimierte Texte (z.B. "Oeffnungszeiten fuer Sommerbad Kreuzberg")

**Naechster Schritt:** Staging-Link folgt morgen. Koennen wir fuer Mittwoch 10 Uhr einen Review-Termin einplanen?

Beste Gruesse

---

## Technische Rueckmeldung ans Entwicklerteam

### Template-Review

**Status: Freigabe mit kleinen Anmerkungen**

Sehr saubere Arbeit! Die Template-Struktur ist gut organisiert. Meine Punkte:

### Was passt
- Layout/Template/Partial-Trennung ist vorbildlich
- Skip-Link und ARIA-Rollen sind korrekt implementiert
- `aria-labelledby` verknuepft Sections mit ihren Ueberschriften
- Bilder: Hero hat `loading="eager"`, Karten haben `loading="lazy"` — perfekt
- `sr-only` Klasse fuer Screenreader-Texte bei den Karten-Links
- BEM-Namenskonvention durchgaengig verwendet
- `<time datetime="">` fuer maschinenlesbare Datumsangaben

### Offene Punkte

1. **Hero alt-Text ist leer:** `alt=""` beim Hero-Bild ist technisch korrekt (dekorativ), aber da es ein Kampagnenbild ist, waere ein beschreibender alt-Text besser fuer SEO:
   ```html
   alt="Sommerkampagne Berlin schwimmt - Freibad mit Badegaesten"
   ```

2. **News-Limit:** Das Limit auf 3 News wird aktuell ueber `{iterator.index} < 3` im Template gesteuert. Besser waere es, das im Controller/Repository zu loesen:
   ```php
   $this->newsRepository->findLatest(3);
   ```
   So wird die Logik nicht im Template versteckt.

3. **Fehlerfall: Keine Pools/News:** Was passiert wenn `{popularPools}` oder `{latestNews}` leer ist? Bitte `f:if` Fallback ergaenzen:
   ```html
   <f:if condition="{popularPools}">
       <f:then><!-- Grid --></f:then>
       <f:else><p>Aktuell keine Freibaeder verfuegbar.</p></f:else>
   </f:if>
   ```

4. **Sprachvarianten:** Hardcoded Texte wie "Die 3 beliebtesten Freibaeder" sollten ueber `f:translate` laufen, falls spaeter eine englische Version kommt:
   ```html
   <h2 id="popular-pools-heading">
       <f:translate key="landingpage.popularPools.heading" />
   </h2>
   ```

5. **Open Graph Tags:** Fuer die Kampagnenseite brauchen wir spezifische OG-Tags (Bild, Beschreibung) fuer Social Media Shares. Ist das im TypoScript oder Backend geloest?

### Vorgeschlagene Timeline
- Di: Anmerkungen einarbeiten (geschaetzt 2h)
- Mi: Internes QA + Barrierefreiheitstest
- Do 10:00: Staging-Review mit Kundin
- Fr: Feedback einarbeiten
- Mo naechste Woche: Go-Live Kampagnenseite

---

## Erkenntnisse als PM

### Fluid-Konzepte die ich hier angewandt habe:

- **Layout → Template → Partial Hierarchie:**
  - Layout = Grundgeruest (Header, Footer, Main-Bereich)
  - Template = Seitentyp-spezifischer Inhalt (Landingpage)
  - Partial = Wiederverwendbare Bausteine (Hero, Card)

- **f:render:** Bindet Partials ein und uebergibt Daten via `arguments`
- **f:for:** Iteriert ueber Datenlisten (Pools, News)
- **f:if:** Bedingte Anzeige (Subtitle nur wenn vorhanden)
- **f:link.page:** Erzeugt TYPO3-interne Links (kein Hardcoding von URLs!)
- **f:image:** Bildausgabe mit automatischer Groessenanpassung
- **f:format.raw:** Gibt HTML-Content unescaped aus
- **f:format.date:** Formatiert Datumsangaben

### Barrierefreiheits-Checks die ich als PM pruefe:
- Skip-Navigation vorhanden?
- Semantische HTML-Tags (header, main, nav, article, section)?
- ARIA-Rollen und Labels korrekt?
- Bilder mit sinnvollen alt-Texten?
- Screenreader-Texte (sr-only) wo noetig?
- Fokus-Reihenfolge logisch?
- Farbkontraste ausreichend? (muss im CSS geprueft werden)

### Warum das fuer die PM-Rolle relevant ist:
- Templates bestimmen was der Kunde auf der Seite sieht — ich muss pruefen ob das dem Briefing entspricht
- Barrierefreiheit ist bei oeffentlichen Kunden (BBB) rechtlich verpflichtend — ich muss das im Review sicherstellen
- Die Template-Struktur beeinflusst wie flexibel spaetere Aenderungen sind — ich muss einschaetzen koennen ob die Architektur zukunftsfaehig ist
