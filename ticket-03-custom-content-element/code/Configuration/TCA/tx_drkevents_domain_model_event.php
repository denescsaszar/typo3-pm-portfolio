<?php
/**
 * TCA-Konfiguration fuer die Event-Tabelle.
 * Definiert die Backend-Formularfelder fuer Redakteure.
 */
return [
    'ctrl' => [
        'title' => 'DRK Veranstaltung',
        'label' => 'title',
        'label_alt' => 'event_date, location',
        'label_alt_force' => true,
        'sortby' => 'event_date',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'iconfile' => 'EXT:drk_events/Resources/Public/Icons/event.svg',
    ],

    'types' => [
        '1' => [
            'showitem' => '
                --div--;Allgemein,
                    title, event_date, category,
                --div--;Ort,
                    location, address,
                --div--;Details,
                    description, max_participants,
                    registration_required, registration_link,
                --div--;Zugriff,
                    hidden, starttime, endtime,
            ',
        ],
    ],

    'columns' => [
        'title' => [
            'label' => 'Titel',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'event_date' => [
            'label' => 'Datum & Uhrzeit',
            'config' => [
                'type' => 'datetime',
                'required' => true,
            ],
        ],
        'category' => [
            'label' => 'Kategorie',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'Blutspende', 'value' => 'blutspende'],
                    ['label' => 'Erste-Hilfe-Kurs', 'value' => 'erste-hilfe'],
                    ['label' => 'Ehrenamt', 'value' => 'ehrenamt'],
                ],
                'required' => true,
            ],
        ],
        'location' => [
            'label' => 'Veranstaltungsort',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'address' => [
            'label' => 'Adresse',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'description' => [
            'label' => 'Beschreibung',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'rows' => 10,
            ],
        ],
        'max_participants' => [
            'label' => 'Max. Teilnehmer (0 = unbegrenzt)',
            'config' => [
                'type' => 'number',
                'size' => 5,
                'range' => ['lower' => 0],
            ],
        ],
        'registration_required' => [
            'label' => 'Anmeldung erforderlich',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
            ],
        ],
        'registration_link' => [
            'label' => 'Anmelde-Link',
            'config' => [
                'type' => 'link',
            ],
        ],
    ],
];
