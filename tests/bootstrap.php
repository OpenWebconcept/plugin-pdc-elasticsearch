<?php

/**
 * PHPUnit bootstrap file
 */

/**
 * Load dependencies with Composer autoloader.
 */
require __DIR__ . '/../vendor/autoload.php';

/**
 * Bootstrap WordPress Mock.
 */
\WP_Mock::setUsePatchwork(true);
\WP_Mock::bootstrap();

$GLOBALS['pdc-elasticsearch'] = [
	'active_plugins' => ['pdc-elasticsearch/pdc-elasticsearch.php'],
];

class WP_CLI
{
	public static function add_command()
	{
	}
}

if ( ! function_exists('get_echo') ) {

	/**
	 * Capture the echo of a callable function.
	 *
	 * @param       $callable
	 * @param array $args
	 *
	 * @return string
	 */
	function get_echo($callable, $args = [])
	{
		ob_start();
		call_user_func_array($callable, $args);

		return ob_get_clean();
	}
}
