# PM Response — Ticket 01: TypoScript Site Configuration

## Zusammenfassung fuer den Kunden

Lieber Herr Mueller,

vielen Dank fuer Ihr Feedback zur Navigation. Ich habe mir die technische Umsetzung unseres Entwicklerteams angeschaut und kann Ihnen bestaetigen:

**Alle vier Anforderungen werden abgedeckt:**

1. **Reduzierte Hauptnavigation:** Die Navigation zeigt kuenftig nur noch die 5 Hauptkategorien an, die wir gemeinsam definiert haben. Die bisherigen 12 Punkte werden als Unterpunkte in die neue Struktur einsortiert.

2. **Mega-Menu:** Wenn Besucher ueber einen Hauptpunkt hovern, oeffnet sich ein uebersichtliches Dropdown mit den jeweiligen Unterkategorien. Das funktioniert auch mobil per Touch.

3. **Breadcrumb-Navigation:** Auf jeder Unterseite sehen Besucher den Pfad zurueck zur Startseite (z.B. "Start > Veranstaltungen > Konzerte > Jazz-Abend"). Das verbessert die Orientierung und ist auch gut fuer die Suchmaschinenoptimierung.

4. **Footer-Navigation:** Impressum, Datenschutz und Barrierefreiheitserklaerung werden im Footer verlinkt — rechtlich sauber und sofort erreichbar.

Der naechste Schritt waere ein Staging-Termin, bei dem Sie die neue Navigation live testen koennen. Passt Ihnen Donnerstag um 14 Uhr?

Beste Gruesse

---

## Technische Rueckmeldung ans Entwicklerteam

### Code-Review der TypoScript-Konfiguration

**Status: Freigabe mit Anmerkungen**

Die Konfiguration sieht solide aus. Hier meine Punkte:

### Was passt
- HMENU-Struktur mit 2 Ebenen fuer das Mega-Menu ist korrekt aufgesetzt
- Breadcrumb nutzt `special = rootline` — Standard-Ansatz, gut
- Footer-Navigation ueber `special = directory` mit konfigurierbarer Parent-ID ist flexibel
- Active/Current States sind sauber getrennt (ACT vs CUR)
- Aria-Labels fuer Breadcrumb sind drin (Barrierefreiheit)

### Offene Punkte
1. **Mobile Navigation:** Im TypoScript sehe ich nur die Desktop-Variante. Brauchen wir ein separates Hamburger-Menu oder wird das rein ueber CSS/JS geloest?

2. **Sprachnavigation:** Der Kunde hat 2 Sprachen (DE/EN). Soll die Sprachumschaltung in die Hauptnavigation oder als separates Element?

3. **Performance:** `expAll = 1` laedt alle Untermenues direkt. Bei 5 Hauptpunkten mit je 8-10 Unterpunkten ist das OK, aber wir sollten Caching aktivieren:
   ```
   lib.mainNavigation.cache.key = nav-{page:uid}
   lib.mainNavigation.cache.lifetime = 3600
   ```

4. **Breadcrumb auf Startseite:** Aktuell wuerde der Breadcrumb auch auf der Startseite angezeigt (nur "Start"). Sollen wir das per Condition ausblenden?
   ```
   [page["uid"] == {$site.rootPageId}]
       lib.breadcrumb >
   [END]
   ```

### Vorgeschlagene Timeline
- Mo: Staging-Deploy der Navigation
- Di: Internes Review + QA
- Mi: Kundenpraesentation vorbereiten
- Do 14:00: Staging-Termin mit Kunde
- Fr: Feedback einarbeiten

---

## Erkenntnisse als PM

### TypoScript-Grundlagen die ich hier angewandt habe:
- **HMENU / TMENU:** Das Standardobjekt fuer Navigationen in TYPO3. HMENU definiert das Menu, TMENU rendert es als Text/HTML.
- **NO / ACT / CUR:** Menu-States — Normal, Active (Elternseite), Current (aktuelle Seite)
- **special = rootline:** Erzeugt einen Breadcrumb-Pfad von der Root-Seite bis zur aktuellen Seite
- **special = directory:** Zeigt alle Kindseiten eines bestimmten Ordners an
- **Constants vs Setup:** Constants definieren konfigurierbare Werte (z.B. Footer-Parent-ID), Setup nutzt diese fuer die eigentliche Konfiguration
- **EXT:site_kulturverein:** Verweis auf die Site-Extension, das zentrale Paket fuer alle projektspezifischen Dateien

### Warum das fuer die PM-Rolle relevant ist:
Als PM muss ich nicht selbst TypoScript schreiben, aber ich muss:
- Erkennen ob eine Konfiguration die Kundenanforderungen abdeckt
- Technische Risiken fruehzeitig identifizieren (Performance, Mobile, Sprachen)
- Zwischen Kundenwunsch und technischer Realitaet vermitteln
- Realistische Timelines schaetzen basierend auf der Komplexitaet
