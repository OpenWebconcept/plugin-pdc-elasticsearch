<?php

return [

    /**
     * Service Providers.
     */
    'providers' => [
        /**
         * Global providers.
         */
        OWC\PDC\Elasticsearch\Admin\ElasticPress\ElasticPressServiceProvider::class,

        /**
         * Providers specific to the admin.
         */
        'admin'    => [
            OWC\PDC\Elasticsearch\Admin\Settings\SettingsServiceProvider::class
        ],

        /**
         * Providers specific to the network admin.
         */
        'network'  => [

        ],

        /**
         * Providers specific to the frontend.
         */
        'frontend' => [

        ]
    ],
    'dependencies' => [

    ]
];
