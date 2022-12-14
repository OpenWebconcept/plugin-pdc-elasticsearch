<?php
/**
 * Provider which set up the ElasticPress specific settings.
 */

namespace OWC\PDC\Elasticsearch\Admin\ElasticPress;

/**
 * Provider which set up the ElasticPress specific settings.
 */
class ElasticPress
{
    /**
     * Config of the PDC Base plugin.
     *
     * @var \OWC\PDC\Base\Foundation\Config
     */
    private $config;

    /**
     * ElasticPress constructor.
     *
     * @param \OWC\PDC\Base\Foundation\Config $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Initialize ElasticPress integration.
     *
     * @return void
     */
    public function initElasticPress()
    {
        $this->setIndexables();
        $this->setStatuses();
        $this->setIndexPostsArgs();
        $this->setLanguage();
        $this->setPostSyncArgs();
        $this->whitelistMeta();
    }

    /**
     * Sets the filter to modify the posttypes which gets indexed in the ElasticSearch instance.
     *
     * @return void
     */
    public function setIndexables()
    {
        $indexablesFromConfig = $this->config->get('elasticpress.indexables');
        add_filter(
            'ep_indexable_post_types',
            function ($post_types) use ($indexablesFromConfig) {
                return $indexablesFromConfig;
            },
            11,
            1
        );
    }

    /**
     * Sets additional meta_query information to further determine which posts gets indexed in the ElasticSearch instance
     */
    public function setIndexPostsArgs()
    {
        add_filter(
            'ep_index_posts_args',
            function ($args) {
                $args['meta_query'] = [
                    [
                        'key'     => '_owc_pdc_active',
                        'value'   => 1,
                        'compare' => '=',
                    ],
                ];

                return $args;
            },
            10,
            1
        );
    }

    /**
     * Whitelist all the meta that we want in to insert into Elasticsearch.
     *
     * @return void
     */
    public function whitelistMeta()
    {
        add_filter('ep_prepare_meta_allowed_protected_keys', function ($meta, $post) {
            $meta = [
                '_owc_pdc_links_group',
                '_owc_pdc_downloads_group',
                '_owc_pdc_forms_group',
                '_owc_pdc_titel_alternatief',
                '_owc_pdc_afspraak_url',
                '_owc_pdc_afspraak_title',
                '_owc_pdc_afspraak_meta',
                '_owc_pdc_tags',
                '_owc_pdc_faq_group',
                '_owc_pdc_other_meta',
            ];

            return $meta;
        }, 10, 2);
    }

    /**
     * Filters the post statuses for indexation by elasticPress
     *
     * @return void
     */
    public function setStatuses()
    {
        add_filter(
            'ep_indexable_post_status',
            function ($statuses) {
                return ['publish'];
            },
            11,
            1
        );
    }

    /**
     * Set the language for the ES instance.
     *
     * @return void
     */
    public function setLanguage()
    {
        $languageFromConfig = $this->config->get('elasticpress.language');
        add_filter(
            'ep_analyzer_language',
            function ($language, $analyzer) use ($languageFromConfig) {
                return $languageFromConfig;
            },
            10,
            2
        );
    }

    /**
     * Set the args of the post which is synced to the instance.
     *
     * @return void
     */
    public function setPostSyncArgs()
    {
        add_filter(
            'ep_post_sync_args_post_prepare_meta',
            function ($postArgs, $postID) {
                $postArgs = $this->transform($postArgs, $postID);

                return $postArgs;
            },
            10,
            2
        );
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
        if (apply_filters('owc/pdc-elasticsearch/elasticpress/postargs/remote-author', true, $postID)) {
            $postArgs['post_author']['raw'] = $postArgs['post_author']['display_name'] = $postArgs['post_author']['login'] = '';
        }

        $postArgs['meta'] = isset($postArgs['meta']) ? $postArgs['meta'] : [];
        $postArgs['meta'] = apply_filters('owc/pdc-elasticsearch/elasticpress/postargs/meta', $postArgs['meta'], $postID);

        $postArgs['terms'] = isset($postArgs['terms']) ? $postArgs['terms'] : [];
        $postArgs['terms'] = apply_filters('owc/pdc-elasticsearch/elasticpress/postargs/terms', $postArgs['terms'], $postID);

        //adding pdc-item taxonomies as 'meta.terms' field, filled with concatenated term names.
        $taxonomies_data = [
            ['taxonomy_id' => 'pdc-type'],
            ['taxonomy_id' => 'pdc-doelgroep'],
        ];
        $collected_terms = [];
        foreach ($taxonomies_data as $taxonomy_data) {
            $terms = wp_get_post_terms($postID, $taxonomy_data['taxonomy_id']);
            if (!is_wp_error($terms)) {
                foreach ($terms as $term) {
                    $collected_terms[] = $term->name;
                }
            }
        }
        $postArgs['meta']['terms']['value'] = implode(',', $collected_terms);

        $postArgs = apply_filters('owc/pdc-elasticsearch/elasticpress/postargs/all', $postArgs, $postID);

        return $postArgs;
    }

    /**
     * Define all the necessary settings.
     *
     * @return void
     */
    public function setSettings()
    {
        $settings = $this->getSettings();

        if (isset($settings['_owc_setting_elasticsearch_url']) && (!defined('EP_HOST'))) {
            if (isset($settings['_owc_setting_elasticsearch_shield']) && (!defined('ES_SHIELD'))) {
                define('ES_SHIELD', $settings['_owc_setting_elasticsearch_shield']);
            }

            $url = parse_url($settings['_owc_setting_elasticsearch_url']);

            $epHost[] = $url['scheme'] . '://';
            $epHost[] = defined('ES_SHIELD') ? ES_SHIELD . '@' : '';
            $epHost[] = $url['host'];
            $epHost[] = !empty($url['port']) ? ':' . $url['port'] : '';
            $epHost[] = '/';
            define('EP_HOST', implode('', $epHost));

            update_option('ep_host', EP_HOST);
        }

        if (isset($settings['_owc_setting_elasticsearch_prefix']) && (!defined('EP_INDEX_PREFIX'))) {
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
        $siteUrl      = pathinfo(get_site_url());
        $siteBasename = $siteUrl['basename'];

        if (defined('EP_INDEX_PREFIX') && EP_INDEX_PREFIX) {
            $siteBasename = EP_INDEX_PREFIX . '--' . $siteBasename;
        }

        $buildIndexName = array_filter(
            [
                $siteBasename,
                $siteID,
                $this->getEnvironmentVariable(),
            ]
        );

        $indexName = implode('--', $buildIndexName);

        return $indexName;
    }

    /**
     * Get the environment variable.
     *
     * @return array|false|string
     */
    protected function getEnvironmentVariable()
    {
        return getenv('environment');
    }

    /**
     * Return settings from database.
     *
     * @return array
     */
    public function getSettings()
    {
        return get_option('_owc_pdc_base_settings', []);
    }
}
