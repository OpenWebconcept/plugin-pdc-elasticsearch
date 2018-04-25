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
		$this->plugin->loader->addFilter('owc/pdc-base/config/settings_pages', $this, 'addTab');
		$this->plugin->loader->addFilter('owc/pdc-base/config/settings', $this, 'addSettings');
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

		$configMetaboxes = $this->plugin->config->get('settings');

		return array_merge($pdcBaseMetaboxes, $configMetaboxes);
	}
}
