<?php

namespace OWC\Elasticsearch\Frontend;

use Exception;
use OWC\Elasticsearch\Plugin\BasePlugin;
use OWC\Elasticsearch\Plugin\ServiceProvider;

class Frontend
{

	/**
	 * Instance of the plugin.
	 *
	 * @var \OWC\Elasticsearch\Plugin $plugin
	 */
	protected $plugin;

	/**
	 * Instance of the actions and filters loader.
	 *
	 * @var \OWC\Elasticsearch\Plugin\Loader $loader
	 */
	protected $loader;

	/**
	 * Frontend constructor.
	 *
	 * @param \OWC\Elasticsearch\Plugin\BasePlugin $plugin
	 */
	public function __construct(BasePlugin $plugin)
	{
		$this->plugin = $plugin;
		$this->loader = $plugin->loader;
	}

	/**
	 * Boot up the frontend.
	 * @throws Exception
	 */
	public function boot()
	{
		$this->bootServiceProviders();
	}

	/**
	 * Boot service providers
	 * @throws Exception
	 */
	private function bootServiceProviders()
	{
		$services = $this->plugin->config->get('core.providers.frontend');

		foreach ( $services as $service ) {
			$service = new $service($this->plugin);

			if ( ! $service instanceof ServiceProvider ) {
				throw new Exception('Provider must extend ServiceProvider.');
			}

			/**
			 * @var \OWC\Elasticsearch\Plugin\ServiceProvider $service
			 */
			$service->register();
		}
	}

}