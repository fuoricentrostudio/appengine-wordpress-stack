<?php
/*
Plugin Name:  Register Theme Directory
Plugin URI:   https://roots.io/bedrock/
Description:  Register default theme directory
Version:      1.0.0
Author:       Roots
Author URI:   https://roots.io/
License:      MIT License
*/

if (!defined('WP_DEFAULT_THEME')) {
  register_theme_directory(ABSPATH . 'wp-content/themes');
}

if (get_site_transient( 'appengine_version_id' ) !== getenv('CURRENT_VERSION_ID') ) {
    delete_site_transient('theme_roots');
    switch_theme(get_stylesheet());
    set_site_transient( 'appengine_version_id', getenv('CURRENT_VERSION_ID') );
}