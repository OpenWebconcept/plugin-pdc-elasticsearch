<?php

namespace OWC\Elasticsearch;

class Helpers
{

    /**
     * Test to see if executing an AJAX call specific to the WP Migrate DB family of plugins.
     *
     * @return bool
     */
    public static function isAjax()
    {
        // must be doing AJAX the WordPress way
        if ( ! defined('DOING_AJAX') || ! DOING_AJAX) {
            return false;
        }

        // must be on blog #1 (first site) if multisite
        if (is_multisite() && 1 != get_current_site()->id) {
            return false;
        }

        return true;
    }

    /**
     * @param null $optionField
     * @param null $key
     *
     * @return mixed|void
     * @throws \Exception
     * @internal param null $option
     */
    public static function getSiteSetting($optionField = null, $key = null)
    {
        if (empty($optionField)) {
            throw new \Exception('An option field key should be given.');
        }

        $siteSettings = get_option($optionField, []);

        if (isset($key)) {

            if ( ! isset($siteSettings[$key])) {
                return null;
            }

            return $siteSettings[$key];
        }

        return $siteSettings;
    }
}