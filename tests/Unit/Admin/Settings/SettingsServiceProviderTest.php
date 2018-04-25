<?php

namespace OWC\Elasticsearch\Admin\Settings;

use Mockery as m;
use OWC\Elasticsearch\Config;
use OWC\Elasticsearch\Plugin\BasePlugin;
use OWC\Elasticsearch\Plugin\Loader;
use OWC\Elasticsearch\Tests\TestCase;

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

		$plugin->loader->shouldReceive('addFilter')->withArgs([
			'owc/pdc-base/config/settings',
			$service,
			'addSettings'
		])->once();

		$plugin->loader->shouldReceive('addFilter')->withArgs([
			'owc/pdc-base/config/settings_pages',
			$service,
			'addTab'
		])->once();

		$service->register();

		$configMetaboxes = [
			'elasticsearch' => [
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
			]

		];

		$existingMetaboxes = [
			'base' => [
				'id'             => 'metadata',
				'settings_pages' => 'base_settings_page',
				'tab'            => 'base',
				'fields'         => [
					'general' => [
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
			]
		];

		$expectedMetaboxesAfterMerge = [

			'base'          => [
				'id'             => 'metadata',
				'settings_pages' => 'base_settings_page',
				'tab'            => 'base',
				'fields'         => [
					'general' => [
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
			],
			'elasticsearch' => [
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
			]
		];

		$config->shouldReceive('get')->with('settings')->once()->andReturn($configMetaboxes);

		$this->assertEquals($expectedMetaboxesAfterMerge, $service->addSettings($existingMetaboxes));

		$existingSettingsPages = [
			'base' => [

				'id'   => 'pdc_base_settings',
				'tabs' => [
					'base' => 'Base'
				]
			]
		];

		$configSettingsPages = [
			'base' => [

				'id'   => 'pdc_base_settings',
				'tabs' => [
					'elasticsearch' => 'Elasticsearch'
				]
			]
		];

		$expectedSettingsPages = [
			'base' => [

				'id'   => 'pdc_base_settings',
				'tabs' => [
					'base' => 'Base',
					'elasticsearch' => 'Elasticsearch'
				]
			]
		];

		$config->shouldReceive('get')->with('settings_pages')->once()->andReturn($configSettingsPages);

		$this->assertEquals($expectedSettingsPages, $service->addTab($existingSettingsPages));
	}
}
