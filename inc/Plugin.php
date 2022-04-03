<?php

namespace dp\Excerpt;


use dp\Excerpt\ExcerptSettings;
use dp\Excerpt\ExcerptSettingsPage;

class Plugin {
	public static function load() {
		require_once DP_EXCERPT_DIR . 'inc/ExcerptSettings.php';
		ExcerptSettings::init();

		require_once DP_EXCERPT_DIR . 'inc/ExcerptHook.php';
		ExcerptHook::init();

		if (is_admin()) {
			require_once DP_EXCERPT_DIR . 'inc/ExcerptSettingsPage.php';
			ExcerptSettingsPage::init();
		}


		add_action('init', array(__CLASS__, 'load_textdomain'));
		// add_filter( 'load_textdomain_mofile', array( __CLASS__, 'load_textdomain_mofile' ), 10, 2 );

		add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(__CLASS__, 'plugin_action_links'));
	}


	public static function remove_wp_logo() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('wp-logo');
	}

	public static function load_textdomain_mofile($mofile, $domain) {

		if ('dp-excerpt-plus' === $domain && false !== strpos($mofile, WP_LANG_DIR . '/plugins/')) {
			$locale = apply_filters('plugin_locale', determine_locale(), $domain);

			$mofile = WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/languages/' . $domain . '-' . $locale . '.mo';
		}

		return $mofile;
	}

	public static function load_textdomain() {
		load_plugin_textdomain('dp-excerpt-plus', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}

	public static function plugin_action_links($actions) {
		$actions[] = '<a href="' . esc_url(get_admin_url(null, 'options-general.php?page=dp-excerpt-general')) . '">' . __('Settings', 'dp-admin') . '</a>';

		return $actions;
	}
}
