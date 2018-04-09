<?php
/**
 * Bootstrap OWC Elasticsearch
 *
 * @wordpress-plugin
 * Plugin Name:       OWC Elasticsearch
 * Plugin URI:        https://www.yardinternet.nl
 * Description:       Core of OWC Elasticsearch
 * Version:           1.0.0
 * Author:            Edwin Siebel
 * Author URI:        https://www.yardinternet.nl/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       owc-elasticsearch
 */

use OWC\Elasticsearch\Plugin;
use OWC\Elasticsearch\Plugin\BasePlugin;

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

BasePlugin::addStartUpHooks(__FILE__);
BasePlugin::addTearDownHooks(__FILE__);

add_action('plugins_loaded', function() {
	$plugin = ( new Plugin(__DIR__) )->boot();
}, 9);
