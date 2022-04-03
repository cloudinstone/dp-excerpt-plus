<?php

namespace dp\Excerpt;

class ExcerptHook {
    public static function init() {
        add_filter('excerpt_more', array(__CLASS__, 'excerpt_more'));
        // add_filter('excerpt_length', array(__CLASS__, 'excerpt_length'));
        add_filter('the_excerpt', array(__CLASS__, 'the_excerpt'));
    }

    public static function excerpt_more($excerpt) {
        return '...';
    }

    public static function excerpt_length($excerpt) {
        // return 280;
    }

    public static function the_excerpt($excerpt) {
        $settings = get_option('dp_excerpt_settings');

        // $trimmer = new ExcerptTrimmer($settings);
        // $excerpt = $trimmer->trim($excerpt);

        $excerpt = mb_strimwidth($excerpt, 0, $settings['length'], $settings['trim_marker']);

        return $excerpt;
    }
}
