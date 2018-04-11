<?php

namespace OWC\Elasticsearch\Admin\ElasticPress;

use OWC\Elasticsearch\Plugin\ServiceProvider;

class ElasticPressServiceProvider extends ServiceProvider
{
	/**
	 * Register the service provider
	 * @throws \Exception
	 */
	public function register()
	{
		if (!is_plugin_active('elasticpress/elasticpress.php')) {
			throw new \Exception('Plugin ElasticPress should be installed and active to run this plugin');
		}

		$elasticPress = new ElasticPress($this->plugin->config);

		$this->plugin->loader->addAction('plugins_loaded', $elasticPress, 'setSettings', 10, 1);
		$this->plugin->loader->addAction('init', $elasticPress, 'initElasticPress', 10, 1);
	}
}
