<?php
/*
Plugin Name: PHPCS Diff
Plugin URI:  https://github.com/Automattic/phpcs-diff
Description: Add WP-CLI command for generating PHP CodeSniffer reports for diff retrieved from version systems
Version:     0.0.1
Author:      Automattic
Author URI:  https://automattic.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: phpcs_diff
Domain Path: /languages
*/

require_once 'vendor/autoload.php';

if ( false === defined( 'PHPCS_DIFF_PLUGIN_DIR' ) ) {
	define( 'PHPCS_DIFF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

define( 'PHPCS_DIFF_COMMAND', PHPCS_DIFF_PLUGIN_DIR . 'vendor/bin/phpcs' );
define( 'PHPCS_DIFF_STANDARDS', PHPCS_DIFF_PLUGIN_DIR . 'vendor/wp-coding-standards/wpcs' );

// Load the command only if we're running WordPress via WP CLI.
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once( PHPCS_DIFF_PLUGIN_DIR . 'wp-cli-command.php' );
}