<?php

namespace OWC\Elasticsearch\Admin\Settings;

use OWC\Elasticsearch\Plugin\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{

	/**
	 * Register the service provider.
	 */
	public function register()
	{

		$this->plugin->loader->addFilter('rwmb_meta_boxes', $this, 'registerSettings', 11, 1);
		$this->plugin->loader->addAction('admin_init', $this, 'getSettingsOption');
	}

	/**
	 * Register metaboxes for settings page
	 *
	 * @param $metaboxes
	 *
	 * @return array
	 */
	public function registerSettings($metaboxes)
	{

		$configMetaboxes = [
			'elasticsearch' => [
				'id'             => 'elasticsearch',
				'title'          => __('Elasticsearch instellingen', 'pdc-base'),
				'settings_pages' => '_owc_pdc_elasticsearch_settings',
				'fields'         => [
					'test' => [
						'heading'    => [
							'type' => 'heading',
							'name' => __('Portal', 'pdc-base'),
						],
						'portal_url' => [
							'name' => __('Portal URL', 'pdc-base'),
							'desc' => __('URL inclusief http(s)://', 'pdc-base'),
							'id'   => 'setting_portal_url',
							'type' => 'text'
						]
					]
				]
			]
		];

		return array_merge($metaboxes, $configMetaboxes);
	}

	/**
	 *
	 */
	public function getSettingsOption()
	{
		$this->plugin->settings = get_option(\OWC_PDC_Base\Core\Settings\SettingsServiceProvider::PREFIX . 'pdc_elasticsearch_settings');
	}
}