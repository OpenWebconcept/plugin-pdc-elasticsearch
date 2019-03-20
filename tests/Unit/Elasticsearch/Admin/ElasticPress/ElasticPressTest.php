<?php

namespace OWC\PDC\Elasticsearch\Admin\ElasticPress;

use Mockery as m;
use OWC\PDC\Base\Foundation\Config;
use OWC\PDC\Base\Foundation\Loader;
use OWC\PDC\Base\Foundation\Plugin;
use OWC\PDC\Elasticsearch\Admin\ElasticPress\ElasticPress;
use OWC\PDC\Elasticsearch\Tests\Unit\TestCase;
use WP_Mock;

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
        WP_Mock::setUp();

        $this->config = m::mock(Config::class);

        $this->plugin         = m::mock(Plugin::class);
        $this->plugin->config = $this->config;
        $this->plugin->loader = m::mock(Loader::class);

        $this->service = new ElasticPress($this->config);
    }

    public function tearDown()
    {
        WP_Mock::tearDown();
    }

    /** @test */
    public function it_sets_the_language_from_the_config()
    {
        $this->plugin->config->shouldReceive('get')->with('elasticpress.language')->andReturn('dutch');

        WP_Mock::expectFilterAdded('ep_analyzer_language', function ($language, $analyzer) {

            return $language;
        }, 10, 2);

        $this->service->setLanguage();

        $this->assertTrue(true);
    }

    /** @test */
    public function it_sets_the_indexables_from_the_config()
    {
        $this->plugin->config->shouldReceive('get')->with('elasticpress.indexables')->andReturn([]);

        WP_Mock::expectFilterAdded('ep_indexable_post_types', function ($post_types) {
            return $post_types;
        }, 11, 1);

        $this->service->setIndexables();

        $this->assertTrue(true);
    }

    /** @test */
    public function it_sets_the_correct_post_args_for_syncing()
    {
        WP_Mock::expectFilterAdded( 'ep_post_sync_args_post_prepare_meta', function ($postArgs, $postID) {

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

        $expected = 'www.google.com--1--development';

        WP_Mock::userFunction('get_site_url', [
            'return' => 'http://www.google.com',
        ]);

        $actual = $this->service->setIndexNameByEnvironment($indexName, $siteID);

        $this->assertEquals($expected, $actual);

        putenv('environment=test');

        $expected = 'www.google.com--1--test';
        $actual   = $this->service->setIndexNameByEnvironment($indexName, $siteID);

        $this->assertEquals($expected, $actual);

        putenv('environment=');

        $expected = 'www.google.com--1';
        $actual   = $this->service->setIndexNameByEnvironment($indexName, $siteID);

        $this->assertEquals($expected, $actual);

        define('EP_INDEX_PREFIX', 'prefix');
        putenv('environment=test');

        $expected = 'prefix--www.google.com--1--test';
        $actual   = $this->service->setIndexNameByEnvironment($indexName, $siteID);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_transforms_the_post_args_to_required_format()
    {

        $postIDStub = 1;
        WP_Mock::userFunction('is_wp_error', [
            'return' => false,
        ]);

        $termStub1       = new \StdClass();
        $termStub1->name = 'test 2';

        $termStub2       = new \StdClass();
        $termStub2->name = 'test 3';

        WP_Mock::userFunction('wp_get_post_terms', [
            'return' => [
                $termStub1,
                $termStub2,
            ],
        ]);

        $postArgsStub = [
            'post_id'           => $postIDStub,
            'ID'                => $postIDStub,
            'post_author'       => [
                'login'        => '',
                'display_name' => '',
                'raw'          => '',
            ],
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
            'meta'              => [
                'terms' => [],
            ],
            'post_meta'         => [
                'terms' => '',
            ],
        ];

        $actual = $this->invokeMethod($this->service, 'transform', [
            $postArgsStub,
            $postIDStub,
        ]);

        $expected = [
            'post_id'           => $postIDStub,
            'ID'                => $postIDStub,
            'post_author'       => [
                'login'        => '',
                'display_name' => '',
                'raw'          => '',
            ],
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
            'meta'              => [
                'terms' => [
                    'value' => 'test 2,test 3,test 2,test 3',
                ],
            ],
            'terms'             => [],
            'post_meta'         => [
                'terms' => '',
            ],
        ];

        $this->assertEquals($expected, $actual);
    }
}
