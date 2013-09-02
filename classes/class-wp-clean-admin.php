<?php
/**
 * WP Clean Admin
 *
 * @package   WP_Clean_Admin
 * @author    John Buckingham <john@pancakecreative.com>
 * @license   GPL-2.0+
 * @link      http://pancakecreative.com
 * @copyright 2013 John Buckingham
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists( 'WP_Clean_Admin_Settings' ) ) {

	/**
	 * WP Clean Admin class
	 *
	 * TODO: Rename this class to a proper name for your plugin.
	 *
	 * @package WP_Clean_Admin
	 * @author  John Buckingham <john@pancakecreative.com>
	 */
	class WP_Clean_Admin {
	
		/**
		 * Plugin version, used for cache-busting of style and script file references.
		 *
		 * @since   0.3
		 *
		 * @var     string
		 */
		protected $version = '0.3';
	
		/**
		 * Unique identifier for your plugin.
		 *
		 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
		 * match the Text Domain file header in the main plugin file.
		 *
		 * @since    0.3
		 *
		 * @var      string
		 */
		protected $plugin_slug = 'wp-clean-admin';
	
		/**
		 * Instance of this class.
		 *
		 * @since    0.3
		 *
		 * @var      object
		 */
		protected static $instance = null;
	
		/**
		 * Slug of the plugin screen.
		 *
		 * @since    0.3
		 *
		 * @var      string
		 */
		protected $plugin_screen_hook_suffix = null;
	
		/**
		 * Initialize the plugin by setting localization, filters, and administration functions.
		 *
		 * @since     0.3
		 */
		private function __construct() {
	
			// Load plugin text domain
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
	
			// Initialize settings with the WordPress settings API
			require_once( sprintf( '%s/admin/settings-init.php', WP_CLEAN_PLUGIN_PATH ) );
			$WP_Clean_Admin_Settings_Init = new WP_Clean_Admin_Settings_Init();
	
			// Add the options page and menu item.
			add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
			//add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 10, 2);
			
			// Load admin style sheet and JavaScript.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	
			// Load public-facing style sheet and JavaScript.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			
			// Load login style sheet
			add_action( 'login_head', array( $this, 'enqueue_custom_login_stylesheets' ) );
			
			// Comment			
	        add_action( 'admin_head', array( $this, 'customize_admin_styles' ) );
		
			add_action( 'admin_menu', array( $this, 'remove_admin_menu_pages' ) );
			add_action( 'admin_menu', array( $this, 'remove_admin_menu_items' ) );
			add_action( 'admin_menu', array( $this, 'remove_admin_submenus' ) );
			
			add_filter( 'manage_pages_columns', array( $this, 'customize_admin_columns' ) );
			add_filter( 'manage_posts_columns', array( $this, 'customize_admin_columns' ) );
			
			add_action( 'wp_dashboard_setup', array( $this, 'remove_dashboard_widgets' ) );
							
			add_action( '_admin_menu', array( $this, 'remove_admin_editor_menu' ), 1 );
			add_action( 'admin_init', array( $this, 'remove_admin_meta_boxes') );
			
			// TODO: Give this its own function
			// Hide WordPress Update notices, but only for non-admin users
			//if ( !current_user_can('activate_plugins') ) {
			//	add_filter( 'pre_site_transient_update_core', create_function('$a', "return null;") );
			//}
			
			add_filter( 'admin_footer_text', array(&$this, 'customize_admin_footer') );
			
			add_action( 'admin_head', array( $this, 'customize_admin_logo' ) );
			add_action( 'login_head', array( $this, 'customize_admin_login_logo' ) );
			add_filter( 'bloginfo', array( $this, 'customize_admin_bloginfo_name' ), 10, 2 );
	
			add_action( 'admin_bar_menu', array( $this, 'customize_admin_howdy' ) );
			
			add_action( 'admin_init', array( $this, 'add_gravity_forms_access' ) );
				
		}
	
		/**
		 * Return an instance of this class.
		 *
		 * @since     0.3
		 *
		 * @return    object    A single instance of this class.
		 */
		public static function get_instance() {
	
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
	
			return self::$instance;
		}
	
		/**
		 * Fired when the plugin is activated.
		 *
		 * @since    0.3
		 *
		 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
		 */
		public static function activate( $network_wide ) {
			// TODO: Define activation functionality here
		}
	
		/**
		 * Fired when the plugin is deactivated.
		 *
		 * @since    0.3
		 *
		 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
		 */
		public static function deactivate( $network_wide ) {
			// TODO: Define deactivation functionality here
		}
	
		/**
		 * Load the plugin text domain for translation.
		 *
		 * @since    0.3
		 */
		public function load_plugin_textdomain() {
	
			$domain = $this->plugin_slug;
			$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	
			load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
			load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
		}
	
		/**
		 * Register and enqueue admin-specific style sheet.
		 *
		 * @since     0.3
		 *
		 * @return    null    Return early if no settings page is registered.
		 */
		public function enqueue_admin_styles() {
	
			if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
				return;
			}
	
			$screen = get_current_screen();
			if ( $screen->id == $this->plugin_screen_hook_suffix ) {
				wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'admin/css/admin.css', __FILE__ ), array(), $this->version );
			}
	
		}
	
		/**
		 * Register and enqueue admin-specific JavaScript.
		 *
		 * @since     0.3
		 *
		 * @return    null    Return early if no settings page is registered.
		 */
		public function enqueue_admin_scripts() {
	
			if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
				return;
			}
	
			$screen = get_current_screen();
			if ( $screen->id == $this->plugin_screen_hook_suffix ) {
				wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'admin/js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );
			}
	
		}
	
		/**
		 * Register and enqueue public-facing style sheet.
		 *
		 * @since    0.3
		 */
		public function enqueue_styles() {
			wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), array(), $this->version );
		}
	
		/**
		 * Register and enqueues public-facing JavaScript files.
		 *
		 * @since    0.3
		 */
		public function enqueue_scripts() {
			wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery' ), $this->version );
		}
		
		/**
		 * Enqueue any custom stylesheets that you'd like to use on the login page. 
		 *
		 * @since    0.3
		 */
		public function enqueue_login_styles() {
			wp_enqueue_style( $this->plugin_slug . '-login-styles', plugins_url( 'css/login.css', __FILE__ ), array(), $this->version );
		}
		
		/**
		 * Register the administration menu for this plugin into the WordPress Dashboard menu.
		 *
		 * @since    0.3
		 */
		public function add_plugin_admin_menu() {
			$this->plugin_screen_hook_suffix = add_plugins_page(
				__( 'WP Clean Admin', $this->plugin_slug ),
				__( 'Clean Admin', $this->plugin_slug ),
				'read',
				$this->plugin_slug,
				array( $this, 'add_plugin_admin_page' )
			);
	
		}
		
		/**
		 * Render the settings page for this plugin.
		 *
		 * @since    0.3
		 */
		public function add_plugin_admin_page() {
		global $pagenow;
		
			if ( ! current_user_can( 'update_core' ) )
				wp_die(__('You do not have sufficient permissions to access this page.', 'wp-clean-admin'));
		        
	        include( sprintf( '%s/admin/admin.php', WP_CLEAN_PLUGIN_PATH ) );
	        //include_once( WP_CLEAN_PLUGIN_PATH . 'admin/admin.php' );
		}
		
		/**
		 * Render the tabs for the settings page.
		 *
		 * @since    0.3
		 */
		public function wp_clean_admin_admin_tabs( $current = 'general' ) { 
		    $tabs = array( 'general' => 'General', 'hide' => 'Hide', 'customize' => 'Customize' ); 
		    $links = array();
		    //echo '<div id="icon-themes" class="icon32"><br></div>';
		    echo '<h2 class="nav-tab-wrapper">';
		    foreach( $tabs as $tab => $name ){
		        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
		        echo "<a class='nav-tab$class' href='?page=wp-clean-admin&tab=$tab'>$name</a>";
		        
		    }
		    echo '</h2>';
		}
		
		
	// Here be functionality
	
		/**
		 * clean up WordPress dashboard with this CSS
		 * This is straight out of the Minimal Admin plugin and credit belongs to Aaron Rutley
		 *
		 * @since    0.2
		 */
		public function customize_admin_styles() { ?>
	        <style type="text/css">
	            #wp-admin-bar-comments,
	            #wp-admin-bar-new-content,
	            #wp-admin-bar-wpseo-menu,
	            #footer,
	            #collapse-menu,
	            .column-wpseo-score,
	            .column-wpseo-title,
	            .column-wpseo-metadesc,
	            .column-wpseo-focuskw,
	            .menu-icon-dashboard,
	            .wp-menu-separator,
	            li#wp-admin-bar-site-name.menupop .ab-sub-wrapper {
	                display: none;
	            }
	
	        <?php
	            $options = get_option('wp-clean-admin');
	            $option_hide_screen_options = $options['hide_screen_options'];
	            if ($option_hide_screen_options == '1') {
	                echo '#screen-options-link-wrap { display:none; }';
	                echo '#contextual-help-link-wrap { display:none; }';
	                echo '.tablenav.top { display:none; }';
	            }
	        ?>
	        </style>
	        <?php
		 }
	
		/**
		 * TODO: Add this description.
		 *
		 * @since    0.2
		 */
		public function remove_admin_menu_pages() {
			// If the user is an editor or below
			if ( !current_user_can('activate_plugins') ) {
				remove_menu_page('edit.php?post_type=slide');
				remove_menu_page('tools.php');
				remove_menu_page('index.php');
				remove_menu_page('gf_edit_forms');
				remove_submenu_page('themes.php', 'themes.php');		
				remove_submenu_page('themes.php', 'nav-menus.php');		
				remove_submenu_page('manage_options', 'uber-menu');
				
				$options = get_option('wp-clean-admin');
				$option_hide_posts = $options['hide_posts'];
				if ($option_hide_posts == '1') {
				    remove_menu_page('edit.php');
				}			
			}
		}
		
		/**
		 * Remove the admin menu that we don't want.
		 * TODO: Make this define-able from the admin page
		 *
		 * @since    0.2
		 */
		public function remove_admin_menu_items() {
			global $menu;
			if ( !current_user_can('activate_plugins') ) {
				$restricted = array(__('Links'), __('Comments'), __('Media'),
				__('Plugins'), __('Tools'), __('Users'));
				end ($menu);
				while (prev($menu)){
					$value = explode(' ',$menu[key($menu)][0]);
					if(in_array($value[0] != NULL?$value[0]:"" , $restricted)) {
						unset($menu[key($menu)]);
					}
				}
			}
		}
		
		/**
		 * Remove admin submenus.
		 * TODO: Make each of these this define-able from the admin page
		 *
		 * @since    0.2
		 */
		public function remove_admin_submenus() {
			if ( !current_user_can('activate_plugins') ) {
				global $submenu;
				unset($submenu['index.php'][10]); // Removes 'Updates'.
				unset($submenu['themes.php'][5]); // Removes 'Themes'.
				unset($submenu['options-general.php'][15]); // Removes 'Writing'.
				unset($submenu['options-general.php'][25]); // Removes 'Discussion'.
				unset($submenu['edit.php'][16]); // Removes 'Tags'.  
			}
		}
		
		/**
		 * Remove the "editor" submenu in Appearance > Editor.
		 * TODO: Make this define-able from the admin page
		 *
		 * @since    0.2
		 */
		public function remove_admin_editor_menu() {
			if ( !current_user_can('activate_plugins') ) {
				remove_action('admin_menu', '_add_themes_utility_last', 101);
			}
		}
	
		/**
		 * Remove some pesky admin columns.
		 * TODO: Make each of these this define-able from the admin page
		 *
		 * @since    0.2
		 */
		public function customize_admin_columns( $defaults ) {
			if ( !current_user_can('activate_plugins') ) {
				unset($defaults['comments']);
				//unset( $defaults['author'] );
				//unset( $defaults['date'] );
				//unset( $defaults['categories'] );
				//unset( $defaults['tags'] );
				
				// remove the Yoast SEO columns
				unset( $columns['wpseo-score'] );
				unset( $columns['wpseo-title'] );
				unset( $columns['wpseo-metadesc'] );
				unset( $columns['wpseo-focuskw'] );
			}
	
			return $defaults;
			
		}
		
		/**
		 * Clear out the admin widgets on the WordPress Admin Dashboard. Nobody uses that junk anyway.
		 * TODO: Make each of these this define-able from the admin page
		 *
		 * @since    0.2
		 */
		public function remove_dashboard_widgets() {
			if ( !current_user_can( 'manage_options' ) ) {
				global $wp_meta_boxes;
				unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] );
				unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'] );
				unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
				unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'] );
				unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'] );
				unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] ); 
			}
		}
		
		/**
		 * Remove the standard WP widgets that just clutter up life and confuse clients.
		 * TODO: Make each of these this define-able from the admin page
		 *
		 * @since    0.2
		 */
		public function remove_standard_wp_widgets() {
			if ( !current_user_can( 'manage_options' ) ) {
				unregister_widget( 'WP_Widget_Calendar' );
				unregister_widget( 'WP_Widget_Search' );
				unregister_widget( 'WP_Widget_Archives' );
				unregister_widget( 'WP_Widget_Meta' );
				unregister_widget( 'WP_Widget_Search' );
				unregister_widget( 'WP_Widget_Categories' );
				unregister_widget( 'WP_Widget_Recent_Comments' );
				unregister_widget( 'WP_Widget_Recent_Posts' );
				unregister_widget( 'WP_Widget_RSS' );
				unregister_widget( 'WP_Widget_Tag_Cloud' );
			}
		}
		
		/**
		 * Remove admin submenus.
		 * TODO: Make each of these this define-able from the admin page
		 *
		 * @since    0.2
		 */
		public function remove_admin_meta_boxes() {
			if ( !current_user_can( 'activate_plugins' ) ) {		
				/* Removes meta boxes from Posts */
				remove_meta_box( 'postcustom','post','normal' );
				remove_meta_box( 'trackbacksdiv','post','normal' );
				remove_meta_box( 'commentstatusdiv','post','normal' );
				remove_meta_box( 'commentsdiv','post','normal' );
				remove_meta_box( 'tagsdiv-post_tag','post','normal' );
				remove_meta_box( 'postexcerpt','post','normal' );
				/* Removes meta boxes from pages */
				remove_meta_box( 'postcustom','page','normal' );
				remove_meta_box( 'trackbacksdiv','page','normal' );
				remove_meta_box( 'commentstatusdiv','page','normal' );
				remove_meta_box( 'commentsdiv','page','normal' );
			}  
		}
		
	
		/**
		 * Change up the admin footer to say cool stuff.
		 * TODO: Make this define-able from the admin page, and set up a default value.
		 *
		 * @since    0.2
		 */
		public function customize_admin_footer() {
			echo 'Created by <a href="http://example.com">Pancake Creative</a>. ';
			echo 'Powered by <a href="http://WordPress.org">WordPress</a>.';
		}
		
		/**
		 * Use a custom logo on the WP admin bar.
		 * TODO: Make each of these this define-able from the admin page
		 *
		 * @since    0.2
		 */
		public function customize_admin_logo() {
		  echo '<style type="text/css">
		    #header-logo { background-image: url('.get_bloginfo('template_directory').'/images/admin_logo.png) !important; }
		    </style>';
		}
		
		/**
		 * Use a custom logo on the Login page.
		 * TODO: Make each of these this define-able from the admin page
		 *
		 * @since    0.2
		 */
		public function customize_admin_login_logo() {
		  echo '<style type="text/css">
		    h1 a { background-image:url('.get_bloginfo('template_directory').'/images/login_logo.png) !important; }
		    </style>';
		}
	 
		/**
		 * Use a custom site title in the WP admin bar.
		 * TODO: Make each of these this define-able from the admin page
		 *
		 * @since    0.2
		 */
		public function customize_admin_bloginfo_name( $output, $show ) {
	        if ( is_admin() && $show == "name" ) {
	        	$output = 'New Title of Your Admin Page';
	        }
	        
	        return $output;
		}
		
		/**
		 * Alter the "howdy" text to say something different and awesomer.
		 * TODO: Make each of these this define-able from the admin page, set up a default.
		 *
		 * @since    0.2
		 */
		public function customize_admin_howdy( $wp_admin_bar ) {
		    $avatar = get_avatar( get_current_user_id(), 16 );
		    if ( ! $wp_admin_bar->get_node( 'my-account' ) )
		        return;
		    $wp_admin_bar->add_node( array(
		        'id' => 'my-account',
		        'title' => sprintf( 'Logged in as %s', wp_get_current_user()->display_name ) . $avatar,
		    ) );
		}
		
		/**
		 * Grant editor access to gravity forms.
		 * This is straight out of the Minimal Admin plugin and credit belongs to Aaron Rutley
		 * TODO: Make each of these this define-able from the admin page
		 *
		 * @since    0.2
		 */
		public function add_gravity_forms_access( ){
			$role = get_role( 'editor' );
			$role->add_cap( 'gform_full_access' );
		}
	
	}
}