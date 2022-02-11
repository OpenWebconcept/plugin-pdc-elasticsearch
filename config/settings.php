<?php declare(strict_types=1);

return [
    'id'             => 'elasticsearch',
    'title'          => __('Elasticsearch', 'pdc-elasticsearch'),
    'settings_pages' => '_owc_pdc_base_settings',
    'tab'            => 'elasticsearch',
    'fields'         => [
        'elasticsearch' => [
            'url'    => [
                'id'   => 'setting_elasticsearch_url',
                'name' => __('Instance url (incl. http(s)://)', 'pdc-elasticsearch'),
                'desc' => __('Should be different for every OTAP environment!', 'pdc-elasticsearch'),
                'type' => 'text'
            ],
            'shield' => [
                'id'   => 'setting_elasticsearch_shield',
                'name' => __('Instance shield', 'pdc-elasticsearch'),
                'desc' => __('If applicable, use {username}:{password} notation.', 'pdc-elasticsearch'),
                'type' => 'text'
            ],
            'prefix' => [
                'id'   => 'setting_elasticsearch_prefix',
                'name' => __('Instance prefix', 'pdc-elasticsearch'),
                'desc' => __('This prefix is used to group multiple sources in ElasticSearch.', 'pdc-elasticsearch'),
                'type' => 'text'
            ]
        ]
    ]
];
