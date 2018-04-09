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
			OWC\Elasticsearch\Admin\Settings\SettingsServiceProvider::class,
			OWC\Elasticsearch\Admin\ElasticPress\ElasticPressServiceProvider::class,
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

	'settings' => [

	],
];