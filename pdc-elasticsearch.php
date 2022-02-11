<?php declare(strict_types=1);

/**
 * Plugin Name:       Yard| PDC Elasticsearch
 * Plugin URI:        https://www.yard.nl
 * Description:       PDC Elasticsearch
 * Version:           1.1.3
 * Author:            Yard | Digital Agency
 * Author URI:        https://www.yard.nl/
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       pdc-elasticsearch
 * Domain Path:       /languages
 */

use OWC\PDC\Elasticsearch\Autoloader;
use OWC\PDC\Elasticsearch\Foundation\Plugin;

/**
 * If this file is called directly, abort.
 */
if (! defined('WPINC')) {
    die;
}

/**
 * manual loaded file: the autoloader.
 */
require_once __DIR__ . '/autoloader.php';
$autoloader = new Autoloader();

/**
 * Begin execution of the plugin
 *
 * This hook is called once any activated plugins have been loaded. Is generally used for immediate filter setup, or
 * plugin overrides. The plugins_loaded action hook fires early, and precedes the setup_theme, after_setup_theme, init
 * and wp_loaded action hooks.
 */
\add_action('plugins_loaded', function () {
    (new Plugin(__DIR__))->boot();
}, 10);
