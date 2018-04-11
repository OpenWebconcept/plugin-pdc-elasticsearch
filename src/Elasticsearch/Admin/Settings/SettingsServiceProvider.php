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
		add_filter('owc/pdc_base/config/settings_pages', [$this, 'addTab']);
		add_filter('owc/pdc_base/config/settings', [$this, 'addSettings']);
	}

	/**
	 * @param $settings
	 * @return array
	 */
	public function addTab($settings)
	{
		$settings['base']['tabs']['elasticsearch'] = __('Elasticsearch', '');
		return $settings;
	}

	/**
	 * Register metaboxes for settings page
	 *
	 * @param $metaboxes
	 * @return array
	 */
	public function addSettings($metaboxes)
	{
		$configMetaboxes = [
			'elasticsearch' => [
				'id' => 'elasticsearch',
				'title' => __('Elasticsearch', 'owc-elasticsearch'),
				'settings_pages' => '_owc_pdc_base_settings',
				'tab' => 'elasticsearch',
				'fields' => [
					'elasticsearch' => [
						'url' => [
							'id' => 'setting_elasticsearch_url',
							'name' => __('Instance url', 'owc-elasticsearch'),
							'desc' => __('URL inclusief http(s)://', 'owc-elasticsearch'),
							'type' => 'text'
						],
						'shield' => [
							'id' => 'setting_elasticsearch_shield',
							'name' => __('Instance shield', 'owc-elasticsearch'),
							'desc' => __('URL inclusief http(s)://', 'owc-elasticsearch'),
							'type' => 'text'
						],
						'prefix' => [
							'id' => 'setting_elasticsearch_prefix',
							'name' => __('Instance prefix', 'owc-elasticsearch'),
							'desc' => __('', 'owc-elasticsearch'),
							'type' => 'text'
						]
					]
				]
			]
		];

		$configMetaboxes = array_merge($metaboxes, $configMetaboxes);
		return $configMetaboxes;
	}
}
