<?php

namespace OWC\Elasticsearch\Plugin;

abstract class ServiceProvider
{

	/**
	 * Instance of the plugin.
	 *
	 * @var \OWC\Elasticsearch\Plugin\BasePlugin
	 */
	protected $plugin;

	public function __construct(BasePlugin $plugin)
	{
		$this->plugin = $plugin;
	}

	/**
	 * Register the service provider.
	 */
	public abstract function register();

}