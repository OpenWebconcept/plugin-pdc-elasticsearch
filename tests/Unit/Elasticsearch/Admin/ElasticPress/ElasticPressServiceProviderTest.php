<?php

namespace OWC\PDC\Elasticsearch\Admin\ElasticPress;

use Mockery as m;
use OWC\PDC\Base\Foundation\Config;
use OWC\PDC\Base\Foundation\Loader;
use OWC\PDC\Base\Foundation\Plugin;
use OWC\PDC\Elasticsearch\Admin\ElasticPress\ElasticPress;
use OWC\PDC\Elasticsearch\Admin\ElasticPress\ElasticPressServiceProvider;
use OWC\PDC\Elasticsearch\Tests\Unit\TestCase;
use WP_Mock;

class ElasticPressServiceProviderTest extends TestCase
{
    /**
     * @var ElasticPressServiceProvider $service
     */
    protected $service;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var
     */
    protected $plugin;

    public function setUp()
    {
        WP_Mock::setUp();

        $this->config = m::mock(Config::class);

        $this->plugin         = m::mock(Plugin::class);
        $this->plugin->config = $this->config;
        $this->plugin->loader = m::mock(Loader::class);

        $this->service = new ElasticPressServiceProvider($this->plugin);
        $this->markTestIncomplete();
    }

    public function tearDown()
    {
        WP_Mock::tearDown();
    }
}
