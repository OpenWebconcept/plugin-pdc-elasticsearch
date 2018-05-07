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
		$this->config->setProtectedNodes(['core']);
		$this->config->boot();

		$this->bootServiceProviders();

		$this->loader->addAction('init', $this, 'addActionPlugin', 9);

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
