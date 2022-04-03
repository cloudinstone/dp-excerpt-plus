<?php

/**
 * Plugin Name:       (dp) Excerpt Plus
 * Description:       Get more control of WordPress Excerpt (Admin-Bar) - Hide excerpt from front-end based on user roles and capabilities, auto hide/show etc.
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Version:           1.0.0
 * Author:            (dp)
 * Author URI:        https://getdp.io
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       dp-excerpt-plus
 * Domain Path: /languages
 * Tags: admin-bar, excerpt
 */

define('DP_EXCERPT_DIR', plugin_dir_path(__FILE__));
define('DP_EXCERPT_URL', plugin_dir_url(__FILE__));

require_once DP_EXCERPT_DIR . 'inc/Plugin.php';
dp\Excerpt\Plugin::load();
