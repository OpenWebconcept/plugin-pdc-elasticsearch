<?php

return [
	'elasticsearch' => [
		'id'             => 'elasticsearch',
		'title'          => __('Elasticsearch', 'pdc-elasticsearch'),
		'settings_pages' => '_owc_pdc_base_settings',
		'tab'            => 'elasticsearch',
		'fields'         => [
			'elasticsearch' => [
				'url'    => [
					'id'   => 'setting_elasticsearch_url',
					'name' => __('Instance url', 'pdc-elasticsearch'),
					'desc' => __('URL inclusief http(s)://', 'pdc-elasticsearch'),
					'type' => 'text'
				],
				'shield' => [
					'id'   => 'setting_elasticsearch_shield',
					'name' => __('Instance shield', 'pdc-elasticsearch'),
					'desc' => __('URL inclusief http(s)://', 'pdc-elasticsearch'),
					'type' => 'text'
				],
				'prefix' => [
					'id'   => 'setting_elasticsearch_prefix',
					'name' => __('Instance prefix', 'pdc-elasticsearch'),
					'desc' => __('', 'pdc-elasticsearch'),
					'type' => 'text'
				]
			]
		]
	]
];