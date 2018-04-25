<?php

namespace OWC\Elasticsearch\Admin\ElasticPress;

class ElasticPress
{
	/**
	 * @var \OWC\Elasticsearch\Config
	 */
	private $config;

	/**
	 * ElasticPress constructor.
	 *
	 * @param \OWC\Elasticsearch\Config $config
	 */
	public function __construct($config)
	{
		$this->config = $config;
	}

	/**
	 * Initialize ElasticPress integration.
	 */
	public function initElasticPress()
	{
		$this->setIndexables();
		$this->setLanguage();
		$this->setPostSyncArgs();
	}

	/**
	 * Filters the posttypes which gets indexed in the ElasticSearch instance
	 */
	public function setIndexables()
	{
		$indexablesFromConfig = apply_filters('owc/elasticsearch/elasticpress/indexables', $this->config->get('elasticpress.indexables'));
		add_filter('ep_indexable_post_types', function($post_types) use ($indexablesFromConfig) {
			return $indexablesFromConfig;
		}, 10, 1);
	}

	/**
	 * Set the language for the ES instance.
	 */
	public function setLanguage()
	{
		$languageFromConfig = $this->config->get('elasticpress.language');
		add_filter('ep_analyzer_language', function($language, $analyzer) use ($languageFromConfig) {
			return $languageFromConfig;
		}, 10, 2);
	}

	/**
	 * Set the args of the post which is synced to the instance.
	 */
	public function setPostSyncArgs()
	{
		add_filter('ep_post_sync_args', function($postArgs, $postID) {
			$postArgs = $this->transform($postArgs, $postID);

			return $postArgs;
		}, 10, 2);
	}

	/**
	 * Transforms the postArgs to a filterable object.
	 *
	 * @param $postArgs
	 * @param $postID
	 *
	 * @return array
	 */
	protected function transform($postArgs, $postID): array
	{
		$postArgs['post_author'] = isset($postArgs['post_author']) ? $postArgs['post_author'] : '';
		if ( apply_filters('owc/elasticsearch/elasticpress/postargs/remote-author', true, $postID) ) {
			unset($postArgs['post_author']);
		}

		$postArgs['meta'] = isset($postArgs['meta']) ? $postArgs['meta'] : [];
		$postArgs['meta'] = apply_filters('owc/elasticsearch/elasticpress/postargs/meta', $postArgs['meta'], $postID);

		$postArgs['terms'] = isset($postArgs['terms']) ? $postArgs['terms'] : [];
		$postArgs['terms'] = apply_filters('owc/elasticsearch/elasticpress/postargs/terms', $postArgs['terms'], $postID);

		$postArgs = apply_filters('owc/elasticsearch/elasticpress/postargs/all', $postArgs, $postID);

		return $postArgs;
	}

	/**
	 * Define all the necessary settings.
	 */
	public function setSettings()
	{

		$settings = $this->getSettings();

		if ( isset($settings['_owc_setting_elasticsearch_url']) && ( ! defined('EP_HOST') ) ) {

			if ( isset($settings['_owc_setting_elasticsearch_shield']) && ( ! defined('ES_SHIELD') ) ) {
				define('ES_SHIELD', $settings['_owc_setting_elasticsearch_shield']);
			}

			$url = parse_url($settings['_owc_setting_elasticsearch_url']);
			define('EP_HOST', $url['scheme'] . '://' . ES_SHIELD . '@' . $url['host'] . '/');

			update_option('ep_host', EP_HOST);

		}

		if ( isset($settings['_owc_setting_elasticsearch_prefix']) && ( ! defined('EP_INDEX_PREFIX') ) ) {
			define('EP_INDEX_PREFIX', $settings['_owc_setting_elasticsearch_prefix']);
		}

		add_filter('ep_index_name', [$this, 'setIndexNameByEnvironment'], 10, 2);
	}

	/**
	 * Sets the uniformed indexName for ElasticSearch, based on prefix, environment variable and site ID.
	 *
	 * @param $indexName
	 * @param $siteID
	 *
	 * @return string
	 */
	public function setIndexNameByEnvironment($indexName, $siteID)
	{
		$prefix = 'owc-pdc';
		if ( defined('EP_INDEX_PREFIX') && EP_INDEX_PREFIX ) {
			$prefix = rtrim(EP_INDEX_PREFIX, '-') . '--' . $prefix;
		}

		$buildIndexName = array_filter([
			$prefix,
			$siteID,
			$this->getEnvironmentVariable()
		]);

		$indexName = implode('--', $buildIndexName);

		return $indexName;

	}

	/**
	 * @return array|false|string
	 */
	protected function getEnvironmentVariable()
	{
		return getenv('environment');
	}

	/**
	 * @return array
	 */
	public function getSettings()
	{
		return get_option('_owc_pdc_base_settings', []);
	}
}
