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
		$this->plugin->loader->addFilter('mb_settings_pages', $this, 'addTab', 10, 1);
		$this->plugin->loader->addFilter('rwmb_meta_boxes', $this, 'addSettings', 10, 1);
	}

	/**
	 * @param $settings
	 *
	 * @return array
	 */
	public function addTab($pdcBaseTabSettings)
	{

		$tabSettings = $this->plugin->config->get('settings_pages');

		$mergedTabSettings = array_merge( $pdcBaseTabSettings['base']['tabs'], $tabSettings['base']['tabs']);
		$pdcBaseTabSettings['base']['tabs'] = $mergedTabSettings;

		return $pdcBaseTabSettings;
	}

	/**
	 * register metaboxes for settings page
	 *
	 * @param $rwmbMetaboxes
	 *
	 * @return array
	 */
	public function addSettings($pdcBaseMetaboxes)
	{
		$configMetaboxes = [
			'elasticsearch' => [
				'id'             => 'elasticsearch',
				'title'          => __('Elasticsearch', 'owc-elasticsearch'),
				'settings_pages' => '_owc_pdc_base_settings',
				'tab'            => 'elasticsearch',
				'fields'         => [
					[
						'id'   => 'setting_elasticsearch_url',
						'name' => __('Instance url', 'owc-elasticsearch'),
						'desc' => __('URL inclusief http(s)://', 'owc-elasticsearch'),
						'type' => 'text'
					],
					[
						'id'   => 'setting_elasticsearch_shield',
						'name' => __('Instance shield', 'owc-elasticsearch'),
						'desc' => __('URL inclusief http(s)://', 'owc-elasticsearch'),
						'type' => 'text'
					],
					[
						'id'   => 'setting_elasticsearch_prefix',
						'name' => __('Instance prefix', 'owc-elasticsearch'),
						'desc' => __('', 'owc-elasticsearch'),
						'type' => 'text'
					]
				]
			]
		];

		$configMetaboxes = $this->plugin->config->get('settings');

		return array_merge($pdcBaseMetaboxes, $configMetaboxes);
	}
}
