# TYPO3 PM BIBLE — Referenzguide fuer Projektmanager:innen

> Umfassendes Nachschlagewerk fuer die PM-Rolle bei einer TYPO3-Agentur.
> Abgestimmt auf die Anforderungen bei e-pixler GmbH.

---

## Inhaltsverzeichnis

1. [TYPO3 Grundlagen](#1-typo3-grundlagen)
2. [TypoScript](#2-typoscript)
3. [Fluid Templating](#3-fluid-templating)
4. [PHP in TYPO3](#4-php-in-typo3)
5. [HTML & CSS](#5-html--css)
6. [SEO in TYPO3](#6-seo-in-typo3)
7. [UX & Barrierefreiheit](#7-ux--barrierefreiheit)
8. [Agile Methoden im Agenturalltag](#8-agile-methoden-im-agenturalltag)
9. [Projektmanagement-Werkzeuge](#9-projektmanagement-werkzeuge)
10. [Kundenkommunikation](#10-kundenkommunikation)
11. [TYPO3 Glossar](#11-typo3-glossar)

---

## 1. TYPO3 Grundlagen

### Was ist TYPO3?
- Open-Source Enterprise CMS, seit 1998
- Besonders stark im DACH-Raum (Deutschland, Oesterreich, Schweiz)
- Ideal fuer grosse, mehrsprachige Websites mit komplexen Rechtestrukturen
- Typische Kunden: oeffentliche Einrichtungen, Verbaende, Mittelstand (z.B. DRK, VBB, Berliner Baeder-Betriebe)

### TYPO3 Versionen (relevant)
| Version | PHP | Status | LTS bis |
|---------|-----|--------|---------|
| v11 LTS | 7.4 - 8.1 | Maintenance | Okt 2024 |
| v12 LTS | 8.1 - 8.3 | Active LTS | Okt 2026 |
| v13 LTS | 8.2 - 8.4 | Active LTS | Okt 2028 |

### Architektur-Ueberblick
```
TYPO3 Projekt
├── typo3/                  # TYPO3 Core (nicht anfassen!)
├── typo3conf/
│   └── ext/                # Extensions (eigene + third-party)
│       └── site_package/   # Site-Extension (Templates, Config)
├── fileadmin/              # Uploads, Medien
├── config/
│   └── sites/              # Site Configuration (YAML)
│       └── main/
│           └── config.yaml # Sprachen, Domains, Routing
└── composer.json           # Abhaengigkeiten
```

### Wichtige Konzepte fuer PMs

**Backend vs. Frontend:**
- Backend = Redaktionsoberflaeche (wo Redakteure Inhalte pflegen)
- Frontend = Die fertige Website (was Besucher sehen)

**Seitenbaum (Page Tree):**
- Hierarchische Struktur aller Seiten
- Bestimmt Navigation und URL-Struktur
- Jede Seite hat eine UID (eindeutige ID)

**Content Elements:**
- Bausteine einer Seite (Text, Bild, Video, Formulare etc.)
- Koennen individuell erweitert werden
- Redakteure waehlen aus vorgefertigten Elementen

**Extensions:**
- Funktionserweiterungen fuer TYPO3
- System Extensions (Core), Third-Party (TER/Packagist), Custom (projektspezifisch)
- Site Extension/Package = das zentrale Paket fuer ein Projekt (Templates, CSS, JS, Config)

**Site Configuration (config.yaml):**
```yaml
base: 'https://www.example.com/'
languages:
  - title: Deutsch
    languageId: 0
    base: /
    locale: de_DE.UTF-8
  - title: English
    languageId: 1
    base: /en/
    locale: en_US.UTF-8
routeEnhancers:
  PageTypeSuffix:
    type: PageType
    default: /
```

---

## 2. TypoScript

### Was ist TypoScript?
- TYPO3-eigene Konfigurationssprache (KEIN Programmiersprache!)
- Steuert wie Inhalte im Frontend gerendert werden
- Zwei Bereiche: **Constants** (Variablen) und **Setup** (Konfiguration)

### Grundsyntax
```typoscript
# Zuweisung
page.title = Meine Website

# Objekt erstellen
page = PAGE
page {
    typeNum = 0
    10 = TEXT
    10.value = Hallo Welt
}

# Kopieren
lib.kopie < lib.original

# Loeschen
lib.element >

# Bedingung
[page["uid"] == 5]
    page.10.value = Spezialseite
[END]
```

### Wichtige TypoScript-Objekte

| Objekt | Zweck | Beispiel |
|--------|-------|---------|
| PAGE | Hauptobjekt der Seite | `page = PAGE` |
| FLUIDTEMPLATE | Fluid-Template rendern | `page.10 = FLUIDTEMPLATE` |
| HMENU / TMENU | Navigation erstellen | `lib.nav = HMENU` |
| TEXT | Textausgabe | `10 = TEXT` |
| IMAGE | Bildausgabe | `20 = IMAGE` |
| COA | Content Object Array (Container) | `lib.header = COA` |
| CONTENT | Datenbank-Inhalte laden | `styles.content.get` |
| RECORDS | Einzelne Datensaetze laden | `10 = RECORDS` |

### HMENU — Navigation (als PM wichtig!)
```typoscript
# Hauptnavigation mit 2 Ebenen
lib.mainNav = HMENU
lib.mainNav {
    # Erste Ebene
    1 = TMENU
    1 {
        NO {
            wrapItemAndSub = <li>|</li>
        }
        ACT = 1        # Active State (Elternseite)
        ACT {
            wrapItemAndSub = <li class="active">|</li>
        }
        CUR = 1        # Current State (aktuelle Seite)
        CUR {
            wrapItemAndSub = <li class="current">|</li>
        }
    }
    # Zweite Ebene
    2 = TMENU
    2 {
        wrap = <ul class="submenu">|</ul>
    }
}
```

### Constants vs Setup
```typoscript
# constants.typoscript — Werte definieren
site.name = Berliner Kulturverein
navigation.maxDepth = 2

# setup.typoscript — Werte verwenden
page.meta.title = {$site.name}
```

### Was ein PM wissen muss
- TypoScript lesen und verstehen, nicht schreiben
- Erkennen ob Kundenanforderungen in der Config abgedeckt sind
- Wissen was HMENU, FLUIDTEMPLATE, Conditions bedeuten
- Constants = konfigurierbare Werte, Setup = Logik

---

## 3. Fluid Templating

### Was ist Fluid?
- Template-Engine von TYPO3 (aehnlich wie Twig/Jinja/Handlebars)
- Trennt Design (HTML) von Logik (PHP)
- Drei Ebenen: **Layouts** → **Templates** → **Partials**

### Verzeichnisstruktur
```
Resources/Private/
├── Layouts/
│   └── Default.html        # Grundgeruest (Header, Footer)
├── Templates/
│   └── Page/
│       ├── Default.html     # Standard-Seitentemplate
│       └── Landingpage.html # Spezial-Template
└── Partials/
    ├── Navigation/
    │   └── Main.html        # Navigations-Partial
    └── Footer/
        └── Default.html     # Footer-Partial
```

### Grundsyntax
```html
<!-- Variable ausgeben -->
<p>{page.title}</p>

<!-- Variable mit Escaping -->
<p>{content -> f:format.raw()}</p>

<!-- Bedingung -->
<f:if condition="{showBreadcrumb}">
    <nav class="breadcrumb">
        <f:render partial="Navigation/Breadcrumb" />
    </nav>
</f:if>

<!-- Schleife -->
<f:for each="{menuItems}" as="item">
    <li class="{f:if(condition: item.active, then: 'active')}">
        <a href="{item.link}">{item.title}</a>
    </li>
</f:for>

<!-- Partial einbinden -->
<f:render partial="Header/Logo" arguments="{_all}" />

<!-- Section definieren und rendern -->
<f:section name="sidebar">
    <aside>{sidebarContent}</aside>
</f:section>
```

### Layout-Template-Partial Zusammenspiel
```html
<!-- Layout: Default.html -->
<html>
<body>
    <header>
        <f:render partial="Navigation/Main" arguments="{mainNavigation: mainNavigation}" />
    </header>
    <main>
        <f:render section="content" />
    </main>
    <footer>
        <f:render partial="Footer/Default" />
    </footer>
</body>
</html>

<!-- Template: Default.html -->
<f:layout name="Default" />
<f:section name="content">
    <div class="container">
        <f:format.raw>{content}</f:format.raw>
    </div>
</f:section>
```

### Wichtige ViewHelpers
| ViewHelper | Zweck |
|-----------|-------|
| `f:if` | Bedingungen |
| `f:for` | Schleifen |
| `f:render` | Partials/Sections einbinden |
| `f:link.page` | Link zu TYPO3-Seite |
| `f:image` | Bild mit Bildverarbeitung |
| `f:format.raw` | HTML unescaped ausgeben |
| `f:translate` | Uebersetzungen |
| `f:cObject` | TypoScript-Objekt rendern |

### Was ein PM wissen muss
- Fluid = HTML mit speziellen Tags (ViewHelpers)
- Layout → Template → Partial = Vererbungshierarchie
- Partials sind wiederverwendbare Bausteine
- Aenderungen am Design = Fluid-Templates anpassen
- Neue Seitentypen = neues Template + TypoScript-Mapping

---

## 4. PHP in TYPO3

### Wo kommt PHP in TYPO3 vor?
- **Extensions:** Eigene Funktionalitaet (Controller, Models, Repositories)
- **Content Elements:** Custom Content Elements mit PHP-Backend
- **Middleware:** Request/Response-Verarbeitung
- **Commands:** CLI-Befehle (Scheduler, Imports)

### Extension-Struktur (MVC)
```
EXT:my_extension/
├── Classes/
│   ├── Controller/
│   │   └── EventController.php    # Logik, verarbeitet Requests
│   ├── Domain/
│   │   ├── Model/
│   │   │   └── Event.php          # Datenmodell
│   │   └── Repository/
│   │       └── EventRepository.php # Datenbankabfragen
│   └── ViewHelpers/
│       └── FormatDateViewHelper.php
├── Configuration/
│   ├── TCA/
│   │   └── tx_myextension_domain_model_event.php  # Backend-Formulare
│   ├── TypoScript/
│   │   ├── setup.typoscript
│   │   └── constants.typoscript
│   └── FlexForms/
│       └── Settings.xml           # Plugin-Einstellungen im Backend
├── Resources/
│   ├── Private/
│   │   └── Templates/            # Fluid-Templates
│   └── Public/
│       ├── Css/
│       └── JavaScript/
└── ext_emconf.php                # Extension-Metadaten
```

### Controller-Beispiel (was PMs lesen koennen sollten)
```php
<?php
namespace Vendor\MyExtension\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class EventController extends ActionController
{
    // Repository wird injiziert (Dependency Injection)
    public function __construct(
        protected EventRepository $eventRepository
    ) {}

    // list Action = Alle Events auflisten
    public function listAction(): ResponseInterface
    {
        $events = $this->eventRepository->findAll();
        $this->view->assign('events', $events);
        return $this->htmlResponse();
    }

    // show Action = Einzelnes Event anzeigen
    public function showAction(Event $event): ResponseInterface
    {
        $this->view->assign('event', $event);
        return $this->htmlResponse();
    }
}
```

### TCA — Table Configuration Array
- Definiert wie Backend-Formulare aussehen
- Welche Felder ein Redakteur sieht
- Validierungsregeln

```php
// Beispiel: Event hat Titel, Datum, Beschreibung
'columns' => [
    'title' => [
        'label' => 'Titel',
        'config' => [
            'type' => 'input',
            'max' => 255,
            'eval' => 'trim,required',
        ],
    ],
    'date' => [
        'label' => 'Datum',
        'config' => [
            'type' => 'datetime',
        ],
    ],
],
```

### Was ein PM wissen muss
- MVC-Pattern: Controller (Logik) → Model (Daten) → View (Fluid Template)
- Actions = Funktionen die aufgerufen werden (listAction, showAction, etc.)
- Repository = Datenbankschicht (findAll, findByUid, etc.)
- TCA = Backend-Formularkonfiguration
- Man muss PHP nicht schreiben, aber Controller-Logik lesen koennen

---

## 5. HTML & CSS

### HTML5 Semantik (wichtig fuer Barrierefreiheit)
```html
<header>       <!-- Kopfbereich -->
<nav>          <!-- Navigation -->
<main>         <!-- Hauptinhalt -->
<article>      <!-- Eigenstaendiger Inhalt -->
<section>      <!-- Thematischer Abschnitt -->
<aside>        <!-- Seitenleiste -->
<footer>       <!-- Fussbereich -->
<figure>       <!-- Bild mit Beschriftung -->
<figcaption>   <!-- Bildbeschriftung -->
```

### CSS Grundlagen fuer PMs
```css
/* Responsive Design — Mobile First */
.container {
    width: 100%;
    padding: 0 16px;
}

/* Tablet */
@media (min-width: 768px) {
    .container {
        max-width: 720px;
        margin: 0 auto;
    }
}

/* Desktop */
@media (min-width: 1200px) {
    .container {
        max-width: 1140px;
    }
}

/* Flexbox — Layout */
.navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Grid — Komplexere Layouts */
.content-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}
```

### CSS-Methodologien (Agentur-relevant)
- **BEM (Block Element Modifier):** `.nav__item--active`
- **SCSS/SASS:** Verschachtelung, Variablen, Mixins
- **CSS Custom Properties:** `--primary-color: #003366;`

### Was ein PM wissen muss
- Responsive Breakpoints verstehen (Mobile, Tablet, Desktop)
- Semantisches HTML = bessere Barrierefreiheit + SEO
- BEM-Naming erkennen koennen
- Grundverstaendnis von Flexbox/Grid fuer Layout-Diskussionen

---

## 6. SEO in TYPO3

### TYPO3-eigene SEO-Features
- **EXT:seo** (Core Extension seit v9)
- Automatische XML-Sitemap
- Meta-Tags pro Seite im Backend
- Canonical URLs
- hreflang fuer Mehrsprachigkeit
- Open Graph / Twitter Cards

### TypoScript SEO-Konfiguration
```typoscript
# Meta-Tags
page.meta {
    description.field = description
    og:title.field = title
    og:description.field = description
    og:image.dataWrap = {TSFE:absRefPrefix}fileadmin/og-image.jpg
    robots = index,follow
}

# Canonical
page.headerData.10 = TEXT
page.headerData.10 {
    typolink {
        parameter.data = page:uid
        forceAbsoluteUrl = 1
        returnLast = url
    }
    wrap = <link rel="canonical" href="|">
}

# XML Sitemap (EXT:seo)
plugin.tx_seo {
    config {
        xmlSitemap {
            sitemaps {
                pages {
                    provider = TYPO3\CMS\Seo\XmlSitemap\PagesXmlSitemapDataProvider
                    config {
                        excludedDoktypes = 137,138
                    }
                }
            }
        }
    }
}
```

### SEO-Checkliste fuer PMs
- [ ] Title-Tags eindeutig und unter 60 Zeichen?
- [ ] Meta-Descriptions vorhanden und unter 155 Zeichen?
- [ ] Canonical URLs korrekt gesetzt?
- [ ] hreflang bei mehrsprachigen Seiten?
- [ ] XML-Sitemap erreichbar (/sitemap.xml)?
- [ ] robots.txt korrekt?
- [ ] Ladezeiten optimiert (Core Web Vitals)?
- [ ] Bilder mit alt-Texten?
- [ ] Strukturierte Daten (Schema.org)?
- [ ] Saubere URL-Struktur (Speaking URLs)?

### Was ein PM wissen muss
- SEO-Grundlagen sind Pflicht fuer Kundengspraeche
- TYPO3 bringt viel SEO out-of-the-box mit
- Redakteure muessen Metadaten pflegen — PM muss das briefen
- Core Web Vitals = Performance-Metriken von Google

---

## 7. UX & Barrierefreiheit

### WCAG 2.1 — Web Content Accessibility Guidelines
Vier Prinzipien (POUR):
1. **Perceivable (Wahrnehmbar):** Alternativtexte, Untertitel, Kontraste
2. **Operable (Bedienbar):** Tastaturnavigation, keine Zeitlimits
3. **Understandable (Verstaendlich):** Klare Sprache, konsistente Navigation
4. **Robust:** Kompatibel mit Screenreadern und assistiven Technologien

### BITV 2.0 (Deutschland-spezifisch)
- Barrierefreie-Informationstechnik-Verordnung
- Pflicht fuer oeffentliche Stellen (relevant fuer e-pixler-Kunden wie DRK, VBB!)
- Basiert auf WCAG 2.1 Level AA
- Seit 2025: auch fuer privatwirtschaftliche Unternehmen relevant (European Accessibility Act)

### Barrierefreiheit in TYPO3
```html
<!-- Gute Praxis -->
<img src="event.jpg" alt="Jazz-Konzert im Kulturhaus am 15. Maerz">
<button aria-label="Navigation oeffnen">
    <span class="icon-menu"></span>
</button>
<nav aria-label="Hauptnavigation">...</nav>
<a href="/kontakt" title="Zur Kontaktseite">Kontakt</a>

<!-- Schlechte Praxis -->
<img src="event.jpg" alt="">
<div onclick="openMenu()">Menu</div>
<nav>...</nav>
```

### UX-Grundlagen fuer PMs
- **User Journey:** Pfad des Nutzers von Einstieg bis Ziel
- **Wireframes:** Grobe Layouts vor dem Design
- **Prototyping:** Klickbare Entwuerfe (Figma, Adobe XD)
- **Usability Testing:** Echte Nutzer testen die Seite
- **Mobile First:** Zuerst fuer Mobile designen, dann Desktop

### Was ein PM wissen muss
- BITV/WCAG-Konformitaet bei oeffentlichen Kunden ist Pflicht
- Barrierefreiheit frueh im Projekt einplanen, nicht nachtraeglich
- Kontraste, Schriftgroessen, Tastaturnavigation pruefen lassen
- TYPO3 hat gute Barrierefreiheit im Backend und Frontend

---

## 8. Agile Methoden im Agenturalltag

### Scrum (angepasst fuer Agenturen)
```
Sprint (2 Wochen)
├── Sprint Planning (Mo, 1h)
│   └── Was schaffen wir in diesem Sprint?
├── Daily Standup (taeglich, 15 min)
│   └── Was habe ich gemacht? Was mache ich heute? Blocker?
├── Sprint Review (Fr der 2. Woche, 1h)
│   └── Demo fuer Stakeholder/Kunden
└── Sprint Retrospektive (Fr, 30 min)
    └── Was lief gut? Was koennen wir verbessern?
```

### Kanban (haeufig in Agenturen)
```
| Backlog | To Do | In Progress | Review | Done |
|---------|-------|-------------|--------|------|
| Task 7  | Task 5| Task 3      | Task 2 | Task 1|
| Task 8  | Task 6| Task 4      |        |      |
```
- WIP-Limits (Work in Progress): Max. Aufgaben pro Spalte
- Continuous Flow statt feste Sprints
- Gut fuer Agenturen mit parallelen Projekten

### User Stories
```
Als [Rolle] moechte ich [Funktion], damit [Nutzen].

Beispiel:
Als Besucher der DRK-Website moechte ich Blutspendetermine
in meiner Naehe finden, damit ich schnell einen passenden
Termin buchen kann.

Akzeptanzkriterien:
- [ ] PLZ-Suche funktioniert
- [ ] Ergebnisse nach Entfernung sortiert
- [ ] Kalender-Export (ICS) moeglich
- [ ] Mobile-optimierte Darstellung
```

### Schaetzung
- **Story Points:** Relative Komplexitaet (Fibonacci: 1, 2, 3, 5, 8, 13)
- **T-Shirt Sizes:** XS, S, M, L, XL
- **Planning Poker:** Team schaetzt gemeinsam

### Was ein PM wissen muss
- Scrum und Kanban sind Werkzeuge, kein Dogma
- Agenturen nutzen oft hybride Ansaetze
- PM = Scrum Master + Product Owner in Personalunion
- Kundensprints ≠ Interne Sprints (Erwartungsmanagement!)

---

## 9. Projektmanagement-Werkzeuge

### Typische Tools in Agenturen
| Tool | Zweck |
|------|-------|
| Jira / Redmine / Asana | Ticket-Management |
| Confluence / Notion | Dokumentation |
| Figma / Adobe XD | Design & Prototyping |
| Slack / Teams | Kommunikation |
| Git / GitLab / GitHub | Versionskontrolle |
| Toggl / Harvest | Zeiterfassung |

### TYPO3-spezifische Workflows
```
Anfrage → Konzept → Design → Umsetzung → QA → Staging → Go-Live

1. Anfrage/Briefing
   - Kundenanforderungen aufnehmen
   - Technische Machbarkeit pruefen

2. Konzept
   - Informationsarchitektur
   - Wireframes
   - Technisches Konzept (Extensions, Schnittstellen)

3. Design
   - Mockups in Figma
   - Styleguide / Design System
   - Kundenfreigabe

4. Umsetzung
   - TYPO3 Setup (Site Package, Extensions)
   - Frontend-Entwicklung (Fluid, CSS, JS)
   - Backend-Konfiguration (TCA, TypoScript)
   - Inhaltsmodellierung

5. QA
   - Cross-Browser-Testing
   - Barrierefreiheit pruefen
   - Performance-Tests
   - SEO-Check

6. Staging
   - Kundenpraesentation
   - Feedback einarbeiten
   - Abnahme

7. Go-Live
   - DNS-Umstellung
   - Monitoring
   - Redaktionsschulung
```

### Deployment in TYPO3-Projekten
- **Composer-based:** `composer install` auf dem Server
- **CI/CD:** GitLab CI / GitHub Actions
- **Environments:** Local → Staging → Production
- **Datenbank:** Migrations ueber TYPO3 Upgrade Wizard

---

## 10. Kundenkommunikation

### Zwischen Kunde und Entwickler uebersetzen

**Kunde sagt:** "Die Seite ist langsam."
**PM versteht:** Performance-Problem
**PM fragt nach:** Welche Seite? Seit wann? Mobil oder Desktop?
**PM ans Team:** "Bitte Core Web Vitals fuer /veranstaltungen pruefen. Kunde meldet langsame Ladezeiten seit dem letzten Deployment."

**Kunde sagt:** "Koennt ihr da mal schnell was aendern?"
**PM versteht:** Change Request
**PM klaert:** Scope, Aufwand, Auswirkungen auf Budget/Timeline
**PM zum Kunden:** "Die Aenderung betrifft 3 Templates und benoetigt ca. 4h Entwicklung + QA. Soll ich das in den naechsten Sprint einplanen?"

**Kunde sagt:** "Das sieht auf dem Handy komisch aus."
**PM versteht:** Responsive Bug
**PM fragt nach:** Welches Geraet? Welcher Browser? Screenshot?
**PM ans Team:** "Responsive Bug auf iPhone 14, Safari. Navigation ueberlappt Hero-Bereich. Screenshot im Ticket."

### E-Mail-Templates fuer PMs

**Statusupdate:**
```
Betreff: [Projektname] Sprint-Update KW XX

Liebe Frau Schmidt,

hier das Update zum aktuellen Sprint:

Abgeschlossen:
- Neue Navigationsstruktur implementiert
- SEO-Metadaten fuer alle Hauptseiten gepflegt

In Arbeit:
- Barrierefreiheitsanpassungen (WCAG AA)
- Performance-Optimierung Bilderladung

Naechste Schritte:
- Staging-Review am Donnerstag um 14 Uhr
- Bitte bereiten Sie Feedback zur Navigation vor

Beste Gruesse
```

**Change Request:**
```
Betreff: [Projektname] Aenderungsanfrage — Bewertung

Lieber Herr Mueller,

vielen Dank fuer Ihre Aenderungswuensche. Hier meine Einschaetzung:

Anfrage: Zusaetzliche Filterfunktion auf der Veranstaltungsseite
Aufwand: ca. 12 Stunden (Entwicklung + QA)
Auswirkung: Verzoegerung Go-Live um ~3 Tage
Kosten: Im Rahmen des Aenderungsbudgets abgedeckt

Empfehlung: Umsetzung im naechsten Sprint nach Go-Live,
um den Termin zu halten. Die Filterfunktion kann problemlos
nachtraeglich ergaenzt werden.

Moechten Sie die Aenderung jetzt oder nach Go-Live einplanen?

Beste Gruesse
```

### Eskalations-Framework
1. **Gelb:** Verzoegerung < 1 Woche → PM loest intern
2. **Orange:** Verzoegerung > 1 Woche oder Budgetueberschreitung → PM informiert Kunden proaktiv
3. **Rot:** Grundlegende Aenderung an Scope/Budget/Timeline → Eskalationsmeeting mit Stakeholdern

---

## 11. TYPO3 Glossar

| Begriff | Erklaerung |
|---------|-----------|
| Backend | Redaktionsoberflaeche von TYPO3 |
| Content Element | Inhaltsbaustein (Text, Bild, etc.) |
| DataHandler | Verarbeitet Datenoperationen im Backend |
| doktype | Seitentyp (Standard=1, Ordner=254, Shortcut=4) |
| EXT: | Prefix fuer Extension-Pfade |
| Extbase | MVC-Framework in TYPO3 |
| FAL | File Abstraction Layer (Dateiverwaltung) |
| FlexForm | Flexible Formulare fuer Plugin-Einstellungen |
| Fluid | Template-Engine |
| HMENU | Hierarchisches Menu-Objekt in TypoScript |
| Install Tool | Systemkonfiguration und Updates |
| Page TSconfig | Konfiguration fuer Backend-Seiten |
| Scheduler | Cronjob-Verwaltung in TYPO3 |
| Site Package | Zentrale Extension fuer ein Projekt |
| TCA | Table Configuration Array (Backend-Formulare) |
| TER | TYPO3 Extension Repository |
| TMENU | Text-basiertes Menu-Rendering |
| TSconfig | Backend-Konfigurationssprache |
| TypoScript | Frontend-Konfigurationssprache |
| uid | Unique Identifier (eindeutige ID) |
| ViewHelper | Fluid-Funktion (z.B. f:if, f:for) |

---

## Schnellreferenz: Was muss ich als PM koennen?

### Code LESEN (nicht schreiben!)
- [x] TypoScript-Konfigurationen verstehen
- [x] Fluid-Templates lesen und Struktur erkennen
- [x] PHP-Controller-Logik nachvollziehen
- [x] HTML/CSS bewerten (Semantik, Responsive, BEM)
- [x] SQL-Grundlagen fuer Datenbank-Diskussionen

### Kommunizieren
- [x] Technische Sachverhalte fuer Kunden uebersetzen
- [x] Kundenanforderungen fuer Entwickler praezisieren
- [x] Statusupdates und Change Requests professionell formulieren
- [x] Erwartungsmanagement bei Scope/Budget/Timeline

### Steuern
- [x] Sprints planen und priorisieren
- [x] Risiken fruehzeitig erkennen
- [x] QA-Prozesse sicherstellen
- [x] Go-Live-Checklisten abarbeiten
- [x] Retrospektiven moderieren
