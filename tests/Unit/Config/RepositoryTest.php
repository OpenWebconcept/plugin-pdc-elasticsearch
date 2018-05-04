<?php

namespace OWC\Elasticsearch\Tests\Config;

use OWC\Elasticsearch\Config;
use OWC\Elasticsearch\Tests\TestCase;

class RepositoryTest extends TestCase
{

	/**
	 * @var \OWC\Elasticsearch\Config
	 */
	protected $repository;

	/**
	 * @var array
	 */
	protected $config;

	public function setUp()
	{
		\WP_Mock::setUp();

		$this->repository = new Config(__DIR__ . '/config');
	}

	public function tearDown()
	{
		\WP_Mock::tearDown();
	}

	/** @test */
	public function gets_value_correctly()
	{
		$this->repository->boot();

		$config = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'testfile' => [
					'in_directory' => 'directory',
				],
				'multi'    => [
					'deep' => [
						'multi_level' => 'works'
					]
				]
			]
		];

		$this->assertEquals($config, $this->repository->all());
		$this->assertEquals($config, $this->repository->get(false));
		$this->assertEquals(true, $this->repository->get('test.single_file'));
		$this->assertEquals('directory', $this->repository->get('directory.testfile.in_directory'));
		$this->assertEquals('works', $this->repository->get('directory.multi.deep.multi_level'));
	}

	/** @test */
	public function check_setting_of_path()
	{

		$path = '/test/path/config/';
		$this->repository->setPath($path);

		$this->assertEquals($this->repository->getPath(), $path);
	}

	/** @test */
	public function check_setting_of_protected_nodes()
	{
		$this->repository->boot();

		$expectedConfig = [
			'test'      => [
				'test'
			],
			'directory' => [
				'testfile' => [
					'in_directory' => 'directory',
				],
				'multi'    => [
					'deep' => [
						'multi_level' => 'works'
					]
				]
			]
		];
		$this->repository->set('test', ['test']);
		$this->assertEquals($expectedConfig, $this->repository->all());

		$this->repository->setProtectedNodes(['test']);
		$this->repository->set('test', ['test2']);
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => 'test'
		];
		$this->repository->boot();
		$this->repository->set('directory', 'test');
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'test' => 'node'
			]
		];
		$this->repository->set('directory', ['test' => 'node']);
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'test' => [
					'node' => 'nog deeper'
				]
			]
		];
		$this->repository->set('directory', ['test' => ['node' => 'nog deeper']]);
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'testfile' => 'test',
				'multi'    => [
					'deep' => [
						'multi_level' => 'works'
					]
				]
			]
		];
		$this->repository->boot();
		$this->repository->set('directory.testfile', 'test');
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'testfile' => [
					'test' => 'node'
				],
				'multi'    => [
					'deep' => [
						'multi_level' => 'works'
					]
				]
			]
		];
		$this->repository->set('directory.testfile', ['test' => 'node']);
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'testfile' => [
					'test' => [
						'node' => 'nog deeper'
					]
				],
				'multi'    => [
					'deep' => [
						'multi_level' => 'works'
					]
				]
			]
		];
		$this->repository->set('directory.testfile', ['test' => ['node' => 'nog deeper']]);
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'testfile' => [
					'in_directory' => 'directory',
				],
				'multi'    => 'test'
			]
		];
		$this->repository->boot();
		$this->repository->set('directory.multi', 'test');
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'testfile' => [
					'in_directory' => 'directory',
				],
				'multi'    => [
					'deep' => 'test'
				]
			]
		];
		$this->repository->boot();
		$this->repository->set('directory.multi.deep', 'test');
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'testfile' => [
					'in_directory' => 'directory',
				],
				'multi'    => [
					'deep' => [
						'multi_level' => 'works_also_via_set'
					]
				]
			]
		];
		$this->repository->set('directory.multi.deep', ['multi_level' => 'works_also_via_set']);
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'         => [
				'single_file' => true
			],
			'directory'    => [
				'testfile' => [
					'in_directory' => 'directory',
				],
				'multi'    => [
					'deep' => [
						'multi_level' => 'works'
					]
				]
			],
			'doesnotexist' => [
				'directory' => [
					'multi' => [
						'deep' => null
					]
				]
			]
		];
		$this->repository->boot();
		$this->repository->set('doesnotexist.directory.multi.deep');
		$this->assertEquals($expectedConfig, $this->repository->all());

		$expectedConfig = [
			'test'      => [
				'single_file' => true
			],
			'directory' => [
				'testfile' => [
					'in_directory' => 'directory',
				],
				'multi'    => [
					'deep' => [
						'multi_level' => 'works'
					]
				]
			],
			''          => null
		];
		$this->repository->boot();
		$this->repository->set([null => null]);
		$this->assertEquals($expectedConfig, $this->repository->all());
	}
}
