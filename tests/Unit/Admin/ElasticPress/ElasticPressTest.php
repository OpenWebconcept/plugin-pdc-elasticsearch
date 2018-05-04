<?php

namespace OWC\Elasticsearch\Tests\Admin\ElasticPress;

use Mockery as m;
use OWC\Elasticsearch\Admin\ElasticPress\ElasticPress;
use OWC\Elasticsearch\Config;
use OWC\Elasticsearch\Plugin\BasePlugin;
use OWC\Elasticsearch\Plugin\Loader;
use OWC\Elasticsearch\Tests\TestCase;

class ElasticPressTest extends TestCase
{

	/**
	 * @var ElasticPress
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
		\WP_Mock::setUp();

		$this->config = m::mock(Config::class);

		$this->plugin         = m::mock(BasePlugin::class);
		$this->plugin->config = $this->config;
		$this->plugin->loader = m::mock(Loader::class);

		$this->service = new ElasticPress($this->config);
	}

	public function tearDown()
	{
		\WP_Mock::tearDown();
	}

	/** @test */
	public function it_sets_the_language_from_the_config()
	{
		$this->plugin->config->shouldReceive('get')->with('elasticpress.language')->andReturn('dutch');

		\WP_Mock::expectFilterAdded('ep_analyzer_language', function($language, $analyzer) {

			return $language;
		}, 10, 2);

		$this->service->setLanguage();

		$this->assertTrue(true);
	}

	/** @test */
	public function it_sets_the_indexables_from_the_config()
	{
		$this->plugin->config->shouldReceive('get')->with('elasticpress.indexables')->andReturn([]);

		\WP_Mock::expectFilterAdded('ep_indexable_post_types', function($post_types) {

			return $post_types;
		}, 10, 1);

		$this->service->setIndexables();

		$this->assertTrue(true);
	}

	/** @test */
	public function it_sets_the_correct_post_args_for_syncing()
	{
		\WP_Mock::expectFilterAdded('ep_post_sync_args', function($postArgs, $postID) {

			return $postArgs;
		}, 10, 2);

		$this->service->setPostSyncArgs();

		$this->assertTrue(true);
	}

	/** @test */
	public function it_sets_the_correct_index_name()
	{
		$indexName = 'test';
		$siteID    = 1;

		putenv('environment=development');

		$expected  = 'owc-pdc--1--development';
		$actual    = $this->service->setIndexNameByEnvironment($indexName, $siteID);

		$this->assertEquals($expected, $actual);

		putenv('environment=test');

		$expected = 'owc-pdc--1--test';
		$actual   = $this->service->setIndexNameByEnvironment($indexName, $siteID);

		$this->assertEquals($expected, $actual);

		putenv('environment=');

		$expected = 'owc-pdc--1';
		$actual   = $this->service->setIndexNameByEnvironment($indexName, $siteID);

		$this->assertEquals($expected, $actual);

		define('EP_INDEX_PREFIX', 'prefix--');
		putenv('environment=test');

		$expected = 'prefix--owc-pdc--1--test';
		$actual   = $this->service->setIndexNameByEnvironment($indexName, $siteID);

		$this->assertEquals($expected, $actual);
	}

	/** @test */
	public function it_transforms_the_post_args_to_required_format()
	{

		$postIDStub   = 1;
		$postArgsStub = [
			'post_id'           => $postIDStub,
			'ID'                => $postIDStub,
			'post_author'       => '',
			'post_date'         => '',
			'post_date_gmt'     => '',
			'post_title'        => '',
			'post_excerpt'      => '',
			'post_content'      => '',
			'post_status'       => '',
			'post_name'         => '',
			'post_modified'     => '',
			'post_modified_gmt' => '',
			'post_parent'       => '',
			'post_type'         => '',
			'post_mime_type'    => '',
			'permalink'         => '',
			'guid'              => ''
		];

		$actual = $this->invokeMethod($this->service, 'transform', [
			$postArgsStub,
			$postIDStub
		]);

		$expected = [
			'post_id'           => $postIDStub,
			'ID'                => $postIDStub,
			'post_date'         => '',
			'post_date_gmt'     => '',
			'post_title'        => '',
			'post_excerpt'      => '',
			'post_content'      => '',
			'post_status'       => '',
			'post_name'         => '',
			'post_modified'     => '',
			'post_modified_gmt' => '',
			'post_parent'       => '',
			'post_type'         => '',
			'post_mime_type'    => '',
			'permalink'         => '',
			'guid'              => '',
			'meta'              => [],
			'terms'             => [],
		];

		$this->assertEquals($expected, $actual);
	}
}
