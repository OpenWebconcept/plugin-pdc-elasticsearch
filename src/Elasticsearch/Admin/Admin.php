<?php

namespace OWC\Elasticsearch\Admin;

use Exception;
use OWC\Elasticsearch\Plugin\ServiceProvider;
use OWC\Elasticsearch\Plugin\BasePlugin;

class Admin
{

    /**
     * Instance of the plugin.
     *
     * @var $plugin \OWC\Elasticsearch\Plugin
     */
    protected $plugin;

    /**
     * Instance of the actions and filters loader.
     *
     * @var $plugin \OWC\Elasticsearch\Plugin\Loader
     */
    protected $loader;

    /**
     * Admin constructor.
     *
     * @param \OWC\Elasticsearch\Plugin\BasePLugin $plugin
     */
    public function __construct(BasePlugin $plugin)
    {
        $this->plugin = $plugin;
        $this->loader = $plugin->loader;
    }

	/**
	 * Boot up the frontend
	 * @throws Exception
	 */
    public function boot()
    {
        $this->bootServiceProviders();
    }

	/**
	 * Boot service providers
	 * @throws Exception
	 */
    private function bootServiceProviders()
    {
        $services = $this->plugin->config->get('core.providers.admin');

        foreach ($services as $service) {
            $service = new $service($this->plugin);

            if ( ! $service instanceof ServiceProvider) {
                throw new Exception('Provider must extend ServiceProvider.');
            }

            /**
             * @var \OWC\Elasticsearch\Plugin\ServiceProvider $service
             */
            $service->register();
        }
    }

}
