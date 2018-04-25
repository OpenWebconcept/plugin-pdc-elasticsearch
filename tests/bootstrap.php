<?php
/**
 * PHPUnit bootstrap file
 */

/**
 * Load dependencies with Composer autoloader.
 */
require __DIR__ . '/../vendor/autoload.php';

//$_tests_dir = getenv('WP_TESTS_DIR');
//if ( ! $_tests_dir ) {
//	$_tests_dir = rtrim(sys_get_temp_dir(), '/\\') . '/wordpress-tests-lib';
//}
//
//if ( file_exists($_tests_dir . '/includes/functions.php') ) {
//	// Give access to tests_add_filter() function.
//	require_once $_tests_dir . '/includes/functions.php';
//}
//
//if ( file_exists($_tests_dir . '/includes/bootstrap.php') ) {
//	// Start up the WP testing environment.
//	require $_tests_dir . '/includes/bootstrap.php';
//}

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
