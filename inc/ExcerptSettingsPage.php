<?php

namespace dp\Excerpt;

use \dp\Excerpt\ExcerptSettings;

class ExcerptSettingsPage {
	public static function init() {
		add_action('admin_menu', array(__CLASS__, 'add_page'));
	}

	public static function add_page() {
		$page_hook = add_submenu_page(
			'options-general.php',
			__('Excerpt Settings', 'dp-excerpt-plus'),
			__('Excerpt', 'dp-excerpt-plus'),
			'manage_options',
			'dp-excerpt-general',
			array(__CLASS__, 'render_page'),
			10
		);

		add_action("load-{$page_hook}", array(__CLASS__, 'load_page'));
	}

	public static function load_page() {
		add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
		add_filter('admin_body_class', array(__CLASS__, 'admin_body_class'));
	}

	public static function render_page() { ?>
		<div class="dp-admin-page-wrap" id="dp-excerpt-settings-page-wrap">

		</div>
<?php
	}

	public static function enqueue_scripts() {
		// js file.
		$script_asset = require DP_EXCERPT_DIR . 'build/admin.asset.php';

		wp_enqueue_script(
			'dp-excerpt-admin',
			DP_EXCERPT_URL . 'build/admin.js',
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		// set translations.
		wp_set_script_translations('dp-excerpt-admin', 'dp-excerpt-plus', DP_EXCERPT_DIR . 'languages/');

		// css file.
		$admin_css = 'build/admin.css';
		wp_enqueue_style(
			'dp-excerpt-admin',
			DP_EXCERPT_URL . $admin_css,
			array('wp-components'),
			filemtime(DP_EXCERPT_DIR . $admin_css)
		);

		// Build inline scripts.
		$settings = ExcerptSettings::get_settings();

		wp_add_inline_script('dp-excerpt-admin', 'var dpExcerptSettings = ' . wp_json_encode($settings), 'before');
	}

	public static function admin_body_class($classes) {
		return $classes .= 'dp-excerpt-settings-page';
	}
}
