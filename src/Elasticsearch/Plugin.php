<?php

namespace OWC\Elasticsearch;

use OWC\Elasticsearch\Plugin\BasePlugin;

class Plugin extends BasePlugin
{

	/**
	 * Name of the plugin.
	 *
	 * @var string
	 */
	const NAME = 'owc-elasticsearch';

	/**
	 * Version of the plugin.
	 * Used for setting versions of enqueue scripts and styles.
	 *
	 * @var string
	 */
	const VERSION = '1.0.0';

	/**
	 * Boot the plugin.
	 * @throws \Exception
	 */
	public function boot()
	{

	}

	/**
	 * Get settings from config file, and allow to hook into it.
	 *
	 * @return array
	 */
	public function getSettings()
	{
		return $this->config->get('core.settings');
	}

}
