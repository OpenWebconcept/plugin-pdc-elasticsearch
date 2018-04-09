<?php

namespace OWC\Elasticsearch\Commands;

use Exception;
use OWC\Elasticsearch\Plugin\ServiceProvider;
use WP_CLI;

class CommandsServiceProvider extends ServiceProvider
{

	/**
	 * Array of all the commands that are configured.
	 *
	 * @var array
	 */
	private $commands;

	/**
	 * Register the service provider.
	 * @throws Exception
	 */
	public function register()
	{
		if ( ! class_exists('WP_CLI') ) {
			return;
		}

		$this->commands = $this->plugin->config->get('cli.commands');

		if ( ! empty($this->commands) ) {
			$this->addCommands();
		}
	}

	/**
	 * Add all the commands
	 * @throws Exception
	 */
	private function addCommands()
	{
		/**
		 * Register all the widgets.
		 */
		foreach ( $this->commands as $command => $handler ) {
			if ( ! is_subclass_of($handler, BaseCommand::class) ) {
				throw new Exception('Command must be instance of BaseCommand.');
			}

			WP_CLI::add_command($command, new $handler($this->plugin));
		}
	}

}