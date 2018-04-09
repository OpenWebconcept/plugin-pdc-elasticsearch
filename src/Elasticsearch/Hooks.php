<?php

namespace OWC\Elasticsearch;

class Hooks
{

	/**
	 * This method is called when the plugin is being activated.
	 */
	public static function pluginActivation()
	{
		/** Add transient to allow for notice in admin */
		set_transient('owc-elasticsearch-plugin-actions-notice', true, 5);
	}

	/**
	 * This method is called immediately after any plugin is activated, and may be used to detect the activation of
	 * plugins. If a plugin is silently activated (such as during an update), this hook does not fire.
	 *
	 * @param $plugin
	 * @param $network_activation
	 */
	public static function pluginActivated($plugin, $network_activation)
	{

	}

	/**
	 * This method is run immediately after any plugin is deactivated, and may be used to detect the deactivation of
	 * other plugins.
	 *
	 * @param $plugin
	 * @param $network_activation
	 */
	public static function pluginDeactivated($plugin, $network_activation)
	{

	}

	/**
	 * This method registers a plugin function to be run when the plugin is deactivated.
	 */
	public static function pluginDeactivation()
	{

	}

	/**
	 * This method is run when the plugin is activated.
	 * This method run is when the user clicks on the uninstall link that calls for the plugin to uninstall itself.
	 * The link won’t be active unless the plugin hooks into the action.
	 */
	public static function uninstallPlugin()
	{

	}
}