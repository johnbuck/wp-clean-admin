<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   WP_Clean_Admin
 * @author    John Buckingham <john@pancakecreative.com>
 * @license   GPL-2.0+
 * @link      http://pancakecreative.com
 * @copyright 2013 John Buckingham
*/

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// TODO: Define uninstall functionality here