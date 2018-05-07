<?php
/**
 * Bootstrap PDC Elasticsearch
 *
 * @wordpress-plugin
 * Plugin Name:       PDC Elasticsearch
 * Plugin URI:        https://www.yardinternet.nl
 * Description:       Core of PDC Elasticsearch
 * Version:           0.1
 * Author:            Edwin Siebel, Ruud Laan
 * Author URI:        https://www.yardinternet.nl/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pdc-elasticsearch
 */

use OWC\Elasticsearch\Plugin;

/**
 * If this file is called directly, abort.
 */
if ( ! defined('WPINC') ) {
	die;
}

/**
 * Only manual loaded file: the autoloader.
 */
require_once __DIR__ . '/autoloader.php';
new AutoloaderElasticSearch();

/**
 * Begin execution of the plugin
 *
 * This hook is called once any activated plugins have been loaded. Is generally used for immediate filter setup, or
 * plugin overrides. The plugins_loaded action hook fires early, and precedes the setup_theme, after_setup_theme, init
 * and wp_loaded action hooks.
 */

add_action('plugins_loaded', function() {
	$plugin = ( new Plugin(__DIR__) )->boot();
}, 10);
