<?php

return [

	/**
	 * Service Providers.
	 */
	'providers' => [
		/**
		 * Global providers.
		 */

		/**
		 * Providers specific to the admin.
		 */
		'admin'    => [
			OWC\PDC\Elasticsearch\Admin\Settings\SettingsServiceProvider::class,
			OWC\PDC\Elasticsearch\ElasticPress\ElasticPressServiceProvider::class,
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
