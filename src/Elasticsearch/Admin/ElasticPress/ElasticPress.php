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
	 * Filters the posttypes which gets indexed in the ElasticSearch instance
	 */
	public function setIndexables()
	{
		$indexablesFromConfig = apply_filters('owc/elasticsearch/elasticpress/indexables', $this->config->get('elasticpress.indexables'));
		add_filter('ep_indexable_post_types', function ($post_types) use ($indexablesFromConfig) {
			return $indexablesFromConfig;
		}, 10, 1);
	}

	/**
	 * Set the language for the ES instance.
	 */
	public function setLanguage()
	{
		$languageFromConfig = apply_filters('owc/elasticsearch/elasticpress/language', $this->config->get('elasticpress.language'));
		add_filter('ep_analyzer_language', function ($language, $analyzer) use ($languageFromConfig) {
			return $languageFromConfig;
		}, 10, 2);
	}

	/**
	 * Set the args of the post which is synced to the instance.
	 */
	public function setPostSyncArgs()
	{
		add_filter('ep_post_sync_args', function ($postArgs, $postID) {
			$postArgs = $this->transform($postArgs, $postID);

			return $postArgs;
		}, 10, 2);
	}

	/**
	 * @param $postArgs
	 * @param $postID
	 *
	 * @return array
	 */
	protected function transform($postArgs, $postID): array
	{
		$title_alternative = get_post_meta($postID, '_gb_pdc_titel_alternatief', true);

		if (!empty($title_alternative)) {

			$title_alternative = strip_tags($title_alternative);
			$title_alternative = preg_replace('#[\n\r]+#s', ' ', $title_alternative);
			$postArgs['post_title'] = $title_alternative;
		}

		// code to get up additional meta
		// set up data like this:
		// $additional_prepared_meta[ $key ] = array( $value );
		// note that the value is enclosed into an array
		// you can add one or multiple new elements by key => value association to the array
		// afterwards merge new and old data

		$meta_ids = [
			['id' => '_gb_meta_data', 'single' => true],
			['id' => '_gb_pdc_tags', 'single' => true],
			['id' => '_gb_pdc_faq_group', 'single' => true]
		];

		$additional_prepared_meta = [];

		foreach ($meta_ids as $meta_id) {

			//concatenate FAQ_answers into single text node
			if ('_gb_pdc_faq_group' == $meta_id['id']) {
				$data = '';
				$faqs = get_post_meta($postID, $meta_id['id'], $meta_id['single']);

				foreach ($faqs as $faq) {
					$data .= $faq['_gb_pdc_faq_answer'];
				}
			} else {
				$data = get_post_meta($postID, $meta_id['id'], $meta_id['single']);
			}

			if (false === $data) {
				$data = '';
			}
			$additional_prepared_meta[$meta_id['id']] = [$data];
		}

		//adding pdc-item taxonomies as 'gb_meta_data' fields, defaults to array(), or filled with one or more selected taxonomies.

		$taxonomies_data = [
			['taxonomy_id' => 'pdc-type'],
			['taxonomy_id' => 'pdc-doelgroep']
		];
		foreach ($taxonomies_data as $taxonomy_data) {

			$terms = wp_get_post_terms($postID, $taxonomy_data['taxonomy_id']);

			if (!is_wp_error($terms)) {

				$collected_terms = [];

				foreach ($terms as $term) {
					$collected_terms[] = $term->slug;
				}
				$additional_prepared_meta['_gb_' . $taxonomy_data['taxonomy_id']] = $collected_terms;
			}
		}

		$additional_prepared_meta['_gb_pdc_active'] = (int)0;

		$pdc_active = get_post_meta($postID, '_gb_pdc_active', true);
		if (1 == $pdc_active) {
			$additional_prepared_meta['_gb_pdc_active'] = (int)1;
		}

		$new_prepared_meta = array_merge($postArgs['post_meta'], $additional_prepared_meta);
		$postArgs['post_meta'] = $new_prepared_meta;

		unset($postArgs['post_author']);

		return $postArgs;
	}

	/**
	 *
	 */
	public function setTaxonomySyncArgs()
	{
		add_filter('ep_post_sync_args', function ($postArgs, $postID) {
			$postArgs = $this->transform($postArgs, $postID);

			return $postArgs;
		}, 10, 2);
	}

	/**
	 * Define all the necessary settings.
	 */
	public function setSettings()
	{

		$settings = $this->getSettings();

		if (isset($settings['_owc_setting_elasticsearch_url']) && (!defined('EP_HOST'))) {
			define('EP_HOST', $settings['_owc_setting_elasticsearch_url']);
		}

		if (isset($settings['_owc_setting_elasticsearch_shield']) && (!defined('ES_SHIELD'))) {
			define('ES_SHIELD', $settings['_owc_setting_elasticsearch_shield']);
		}

		if (isset($settings['_owc_setting_elasticsearch_prefix']) && (!defined('EP_INDEX_PREFIX'))) {
			define('EP_INDEX_PREFIX', $settings['_owc_setting_elasticsearch_prefix']);
		}

		add_filter('ep_index_name', [$this, 'setIndexNameByEnvironment'], 10, 2);
	}

	/**
	 * @param $indexName
	 * @param $siteID
	 * @return string
	 */
	public function setIndexNameByEnvironment($indexName, $siteID)
	{

		if (!$this->getEnvironmentVariable()) {
			return $indexName;
		}

		$prefix = 'owc-pdc';
		if (defined('EP_INDEX_PREFIX') && EP_INDEX_PREFIX) {
			$prefix = rtrim(EP_INDEX_PREFIX, '-');
		}

		return sprintf('%s-%s-%d', $prefix, $this->getEnvironmentVariable(), $siteID);

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

	/**
	 * Initialize ElasticPress integration.
	 */
	public function initElasticPress()
	{
		$this->setIndexables();
		$this->setLanguage();
		$this->setPostSyncArgs();
		$this->setTaxonomySyncArgs();
	}
}
