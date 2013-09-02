<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that also follow
 * WordPress coding standards and PHP best practices.
 *
 * @package   WP_Clean_Admin
 * @author    John Buckingham <john@pancakecreative.com>
 * @license   GPL-2.0+
 * @link      http://pancakecreative.com
 * @copyright 2013 John Buckingham
 *
 * @wordpress-plugin
 * Plugin Name: WP Clean Admin
 * Plugin URI:  http://pancakecreative.com/wp-content/assets/clean-admin
 * Description: A simple plugin to clean up with admin and make it more accessible to clients and those who want a cleaner, more accessible admin interface.
 * Version:     0.3
 * Author:      John Buckingham
 * Author URI:  http://pancakecreative.com/
 * Text Domain: wp-clean-admin
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// plugin definitions
define( 'WP_CLEAN_ADMIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'WP_CLEAN_ADMIN_BASEFOLDER', plugin_basename( dirname( __FILE__ ) ) );
define( 'WP_CLEAN_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

require_once( plugin_dir_path( __FILE__ ) . 'classes/class-wp-clean-admin.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'WP_Clean_Admin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'WP_Clean_Admin', 'deactivate' ) );

WP_Clean_Admin::get_instance();