<?php

namespace OWC\Elasticsearch\Tests\ElasticPress;

use Exception;
use Mockery as m;
use OWC\Elasticsearch\Admin\ElasticPress\ElasticPressServiceProvider;
use OWC\Elasticsearch\Config;
use OWC\Elasticsearch\Plugin\BasePlugin;
use OWC\Elasticsearch\Plugin\Loader;
use OWC\Elasticsearch\Tests\TestCase;
use WP_Mock;

class ElasticPressServiceProviderTest extends TestCase
{

	/**
	 * @var ElasticPressServiceProvider
	 */
	protected $service;

	/**
	 * @var
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

		$this->plugin         = m::mock(BasePlugin::class);
		$this->plugin->config = $this->config;
		$this->plugin->loader = m::mock(Loader::class);

		$this->service = new ElasticPressServiceProvider($this->plugin);
	}

	public function tearDown()
	{
		WP_Mock::tearDown();
	}

	/** @test */
	public function it_returns_an_exception_when_elasticpress_is_not_installed()
	{

		\WP_Mock::userFunction('is_plugin_active', [
			'args'   => [
				'elasticpress/elasticpress.php'
			],
			'times'  => 1,
			'return' => false
		]);

		$this->expectException(Exception::class);
		$this->expectExceptionMessage('Plugin ElasticPress should be installed and active to run this plugin');

		$this->service->boot();
	}
}
