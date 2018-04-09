<?php

namespace OWC\Elasticsearch\Commands;

use OWC\Elasticsearch\Plugin\BasePlugin;

abstract class BaseCommand
{

	/**
	 * Instance of the plugin.
	 *
	 * @var BasePlugin
	 */
	protected $plugin;

	public function __construct(BasePlugin $plugin)
	{
		$this->plugin = $plugin;
	}

	/**
	 * WP CLI invokes the class to execute the command.
	 *
	 * @param array $args
	 * @param array $assocArgs
	 *
	 * @return mixed
	 */
	abstract public function __invoke(array $args = [], array $assocArgs = []);
}