<?php
/**
 * Provider which registers the ElasticPress specific settings.
 */

namespace OWC\PDC\Elasticsearch\Admin\ElasticPress;

use OWC\PDC\Base\Foundation\ServiceProvider;

/**
 * Provider which registers the ElasticPress specific settings.
 */
class ElasticPressServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     */
    public function register()
    {
        $elasticPress = new ElasticPress($this->plugin->config);

        $this->plugin->loader->addAction('init', $elasticPress, 'setSettings', 10, 1);
        $this->plugin->loader->addAction('init', $elasticPress, 'initElasticPress', 10, 1);
    }
}
