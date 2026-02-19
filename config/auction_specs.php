<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Auction Category Specifications
    |--------------------------------------------------------------------------
    |
    | This file defines the dynamic fields required for different auction categories.
    | The keys must match the category 'slug'.
    |
    */

    'vintage-cars' => [
        'fields' => [
            [
                'name' => 'year',
                'label' => 'Year',
                'type' => 'number',
                'placeholder' => 'e.g. 1965',
                'required' => false,
            ],
            [
                'name' => 'mileage',
                'label' => 'Mileage (km)',
                'type' => 'number',
                'placeholder' => 'e.g. 50000',
                'required' => false,
            ],
            [
                'name' => 'fuel_type',
                'label' => 'Fuel Type',
                'type' => 'select',
                'options' => ['Petrol', 'Diesel', 'Electric'],
                'required' => false,
            ],
        ],
        'has_document' => true,
        'document_label' => 'Vehicle Documentation (PDF/Image)',
        'document_hint' => 'Registration, Title, or Inspection reports.',
    ],

    'jewelry' => [
        'fields' => [
            [
                'name' => 'metal',
                'label' => 'Metal Type',
                'type' => 'text',
                'placeholder' => 'e.g. 24K Gold',
                'required' => false,
            ],
        ],
        'has_document' => true,
        'document_label' => 'Certificate of Authenticity',
    ],

    'art' => [
        'fields' => [
            [
                'name' => 'artist',
                'label' => 'Artist Name',
                'type' => 'text',
                'placeholder' => 'e.g. Vincent van Gogh',
                'required' => false,
            ],
        ],
        'has_document' => true,
        'document_label' => 'Authenticity Document',
    ],
];
