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
		if ( ! is_plugin_active('elasticpress/elasticpress.php') ) {
			throw new \Exception('Plugin ElasticPress should be installed and active to run this plugin');
		}

		$this->plugin->loader->addAction('init', $this, 'initElasticPress', 10, 1);
	}

	/**
	 * Initialize ElasticPress integration.
	 */
	public function initElasticPress()
	{

		$elasticPress = new ElasticPress($this->plugin->config);

		$elasticPress->setIndexables();
		$elasticPress->setLanguage();
		$elasticPress->setPostSyncArgs();
		$elasticPress->setTaxonomySyncArgs();
	}
}