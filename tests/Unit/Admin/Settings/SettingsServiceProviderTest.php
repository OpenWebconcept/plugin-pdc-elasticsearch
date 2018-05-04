<?php

namespace OWC\Elasticsearch\Admin\Settings;

use Mockery as m;
use OWC\Elasticsearch\Config;
use OWC\Elasticsearch\Plugin\BasePlugin;
use OWC\Elasticsearch\Plugin\Loader;
use OWC\Elasticsearch\Tests\TestCase;
use StdClass;

class SettingsServiceProviderTest extends TestCase
{

	public function setUp()
	{
		\WP_Mock::setUp();
	}

	public function tearDown()
	{
		\WP_Mock::tearDown();
	}

	/** @test */
	public function check_registration_of_settings_metaboxes()
	{
		$config = m::mock(Config::class);
		$plugin = m::mock(BasePlugin::class);

		$plugin->config = $config;
		$plugin->loader = m::mock(Loader::class);

		$service = new SettingsServiceProvider($plugin);

		$plugin->loader->shouldReceive('addAction')->withArgs([
			'owc/pdc-base/plugin',
			$service,
			'addTab',
			10,
			1
		])->once();

		$plugin->loader->shouldReceive('addAction')->withArgs([
			'owc/pdc-base/plugin',
			$service,
			'addSettings',
			10,
			1
		])->once();

		$service->register();

		$configMetaboxes = [
			'id'             => 'elasticsearch',
			'settings_pages' => 'base_settings_page',
			'tab'            => 'elasticsearch',
			'fields'         => [
				'elasticsearch' => [
					'testfield_noid' => [
						'type' => 'heading'
					],
					'testfield1'     => [
						'id' => 'metabox_id1'
					],
					'testfield2'     => [
						'id' => 'metabox_id2'
					]
				]
			]
		];

		$config->shouldReceive('get')->with('settings')->once()->andReturn($configMetaboxes);

		$basePlugin         = new StdClass();
		$basePlugin->config = m::mock(Config::class);

		$basePlugin->config->shouldReceive('set')->withArgs([
			'settings.elasticsearch',
			$configMetaboxes
		])->once();

		$this->assertTrue(true);

		$service->addSettings($basePlugin);

		/**
		 * Add Tab
		 */

		$configSettingsPages = __('Elasticsearch', 'PDC settings tab', 'pdc-elasticsearch');

		$config->shouldReceive('get')->with('settings_pages.elasticsearch')->once()->andReturn($configSettingsPages);

		$basePlugin         = new \StdClass();
		$basePlugin->config = m::mock(Config::class);

		$basePlugin->config->shouldReceive('set')->withArgs([
			'settings_pages.base.tabs.elasticsearch',
			$configSettingsPages
		])->once();

		$this->assertTrue(true);

		$service->addTab($basePlugin);
	}
}
