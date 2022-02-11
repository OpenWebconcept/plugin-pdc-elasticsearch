<?php declare(strict_types=1);
/**
 * Provider to register the settings in the admin.
 */

namespace OWC\PDC\Elasticsearch\Admin\Settings;

use OWC\PDC\Base\Foundation\ServiceProvider;

/**
 * Provider to register the settings in the admin.
 */
class SettingsServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->plugin->loader->addAction('owc/pdc-base/plugin', $this, 'addTab', 10, 1);
        $this->plugin->loader->addAction('owc/pdc-base/plugin', $this, 'addSettings', 10, 1);
    }

    /**
     * Inject the PDC Base plugin to be used to inject settings into the config.
     *
     * @param $basePlugin
     *
     * @return void
     */
    public function addTab($basePlugin)
    {
        $configMetaboxes = $this->plugin->config->get('settings_pages.elasticsearch');

        $basePlugin->config->set('settings_pages.base.tabs.elasticsearch', $configMetaboxes);
    }

    /**
     * Register metaboxes for settings page.
     *
     * @param $basePlugin
     *
     * @return void
     */
    public function addSettings($basePlugin)
    {
        $configMetaboxes = $this->plugin->config->get('settings');

        $basePlugin->config->set('settings.elasticsearch', $configMetaboxes);
    }
}
