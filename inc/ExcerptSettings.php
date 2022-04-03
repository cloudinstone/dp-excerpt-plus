<?php

namespace dp\Excerpt;

class ExcerptSettings {
	public static function init() {
		add_action('init', array(__CLASS__, 'register_settings'));
	}

	public static function register_settings() {
		register_setting(
			'dp_excerpt_settings',
			'dp_excerpt_settings',
			array(
				'type'         => 'object',
				// 'sanitize_callback' => array( __CLASS__, 'sanitize_settings' ),
				'default'      => array(
					'length' => 280,
					'length_type'  => 'chars',
					'trim_marker'     => '&hellip;'
				),
				'show_in_rest' => array(
					'schema' => array(
						'properties' => array(
							'length'  => array(
								'type' => 'number',
							),
							'length_type'     => array(
								'type' => 'string',
							),
							'trim_marker'     => array(
								'type' => 'string',
							),
						),
					),
				),
			)
		);
	}

	public static function sanitize_settings($settings) {
		if (!empty($settings['front_display_rule'])) {
			$rule = $settings['front_display_rule'];

			if (!empty($rule['scope']) && $rule['scope'] != 'custom') {
				$keys_to_remove                 = array('logged_in', 'not_logged_in', 'caps', 'roles');
				$settings['front_display_rule'] = array_diff_key($rule, array_flip($keys_to_remove));
			}
		}

		return $settings;
	}

	public static function get_settings() {
		global $wp_registered_settings;

		$defaults = $wp_registered_settings['dp_excerpt_settings']['default'];

		$settings = get_option('dp_excerpt_settings');

		return $settings;

		$settings = array_merge($defaults, $settings);

		return $settings;
	}
}
