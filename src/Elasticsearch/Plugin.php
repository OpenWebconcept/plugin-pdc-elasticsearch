<?php

namespace OWC\Elasticsearch;

use OWC\Elasticsearch\Plugin\BasePlugin;
use OWC\Elasticsearch\Admin\Admin;

class Plugin extends BasePlugin
{

	/**
	 * Name of the plugin.
	 *
	 * @var string
	 */
	const NAME = 'pdc-elasticsearch';

	/**
	 * Version of the plugin.
	 * Used for setting versions of enqueue scripts and styles.
	 *
	 * @var string
	 */
	const VERSION = '0.1';

	/**
	 * Boot the plugin.
	 * @throws \Exception
	 */
	public function boot()
	{
		$this->config->setPluginName(self::NAME);
		$this->config->setFilterExceptions(['admin', 'core', 'cli']);
		$this->config->boot();

		$this->bootServiceProviders();

		if ( is_admin() ) {
			$admin = new Admin($this);
			$admin->boot();
		}

		$this->loader->addAction('init', $this->config, 'filter', 9);
		$this->loader->register();
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
