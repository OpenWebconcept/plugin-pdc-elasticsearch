<?php

return [

    /**
     * Service Providers.
     */
    'providers'    => [
        /**
         * Global providers.
         */
        OWC\PDC\Elasticsearch\Admin\ElasticPress\ElasticPressServiceProvider::class,

        /**
         * Providers specific to the admin.
         */
        'admin'    => [
            OWC\PDC\Elasticsearch\Admin\Settings\SettingsServiceProvider::class,
        ],

        /**
         * Providers specific to the network admin.
         */
        'network'  => [

        ],

        /**
         * Providers specific to the frontend.
         */
        'frontend' => [],
    ],
    /**
     * Dependencies upon which the plugin relies.
     *
     * Required: type, label
     * Optional: message
     *
     * Type: plugin
     * - Required: file
     * - Optional: version
     *
     * Type: class
     * - Required: name
     */
    'dependencies' => [
        [
            'type'    => 'plugin',
            'label'   => 'OpenPDC Base',
            'version' => '2.1.5',
            'file'    => 'pdc-base/pdc-base.php',
        ],
        [
            'type'    => 'plugin',
            'label'   => 'ElasticPress',
            'version' => '2.8.1',
            'file'    => 'elasticpress/elasticpress.php',
        ],
    ],
];
