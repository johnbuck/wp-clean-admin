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

/**
 * WP Clean Admin Settings class
 *
 *
 * @package WP_Clean_Admin
 * @author  John Buckingham <john@pancakecreative.com>
 */

if( ! class_exists( 'WP_Clean_Admin_Settings_Init' ) ) {

	class WP_Clean_Admin_Settings_Init {	

		public function __construct() {
			// register actions
            add_action( 'admin_init', array( $this, 'settings_init' ) );                   	
		}

        /**
         * hook into WP's admin_init action hook
         */
        public function settings_init() {
        	
        	// Register General settings
        	register_setting( 'wp_clean_admin-general', 'setting_a', array(&$this, 'validate_text_input') );
        	register_setting( 'wp_clean_admin-general', 'setting_b', array(&$this, 'validate_text_input') );
        	register_setting( 'wp_clean_admin-general', 'setting_c', array(&$this, 'validate_text_input') );
        	register_setting( 'wp_clean_admin-general', 'setting_d', array(&$this, 'validate_text_input') );
        	register_setting( 'wp_clean_admin-general', 'setting_e', array(&$this, 'validate_text_input') );

        	// Register Hide settings
        	register_setting( 'wp_clean_admin-hide', 'hide_posts' );
        	register_setting( 'wp_clean_admin-hide', 'hide_screen_options' );
			register_setting( 'wp_clean_admin-hide', 'hide_posts' );
			register_setting( 'wp_clean_admin-hide', 'hide_post_columns' );
        	register_setting( 'wp_clean_admin-hide', 'hide_page_columns' );
			register_setting( 'wp_clean_admin-hide', 'hide_posts' );
			register_setting( 'wp_clean_admin-hide', 'hide_dashboard_widgets' );
			register_setting( 'wp_clean_admin-hide', 'hide_core_update' );

			// Register Customize settings 
        	register_setting( 'wp_clean_admin-customize', 'use_custom_admin_footer' );
        	register_setting( 'wp_clean_admin-customize', 'customize_admin_footer' );
        	register_setting( 'wp_clean_admin-customize', 'use_custom_admin_logo' );
        	register_setting( 'wp_clean_admin-customize', 'customize_admin_logo' );
			register_setting( 'wp_clean_admin-customize', 'use_custom_login_logo' );
			register_setting( 'wp_clean_admin-customize', 'customize_login_logo' );
			

			// Add the "General" settings section
			add_settings_section(
			    'wp_clean_admin-general',								// ID used to identify this section and with which to register options 
			    'General Settings',										// Title to be displayed on the administration page
			    array(&$this, 'settings_section_wp_plugin_template'),	// Callback used to render the description of the section
			    'wp-clean-admin-general'								// Page on which to add this section of options
			);

			// Add the "Hide" settings section
			add_settings_section(
			    'wp_clean_admin-hide',									// ID used to identify this section and with which to register options 
			    'Hide Admin Areas',										// Title to be displayed on the administration page
			    array(&$this, 'settings_section_wp_plugin_template'),	// Callback used to render the description of the section
			    'wp-clean-admin-hide'									// Page on which to add this section of options
			);

			// Add the "Customize" settings section
			add_settings_section(
			    'wp_clean_admin-customize',								// ID used to identify this section and with which to register options 
			    'Customize Admin Areas',								// Title to be displayed on the administration page
			    array(&$this, 'settings_section_wp_plugin_template'),	// Callback used to render the description of the section
			    'wp-clean-admin-customize'								// Page on which to add this section of options
			);
			        	
        	// Add the "General" settings fields
            add_settings_field(
                'setting_a',											// ID used to identify the field throughout the theme
                'Setting A', 											// The label to the left of the option interface element
                array(&$this, 'settings_field_input_text'),				// The name of the function responsible for rendering the option interface
                'wp-clean-admin-general', 								// The page on which this option will be displayed
                'wp_clean_admin-general',								// The name of the section to which this field belongs
                array(
                    'field' => 'setting_a'								// The array of arguments to pass to the callback. In this case, just a description.
                )
            );
            
            add_settings_field(
                'setting_b', 											// ID used to identify the field throughout the theme
                'Setting B', 											// The label to the left of the option interface element
                array(&$this, 'settings_field_input_text'), 			// The name of the function responsible for rendering the option interface
                'wp-clean-admin-general', 								// The page on which this option will be displayed
                'wp_clean_admin-general', 								// The name of the section to which this field belongs
                array(
                    'field' => 'setting_b'								// The array of arguments to pass to the callback. In this case, just a description.
                )
            );

            add_settings_field(
                'setting_c',											// ID used to identify the field throughout the theme
                'Setting C', 											// The label to the left of the option interface element
                array(&$this, 'settings_field_input_text'),				// The name of the function responsible for rendering the option interface
                'wp-clean-admin-general', 								// The page on which this option will be displayed
                'wp_clean_admin-general',								// The name of the section to which this field belongs
                array(
                    'field' => 'setting_c'								// The array of arguments to pass to the callback. In this case, just a description.
                )
            );

			add_settings_field(
			    'setting_d',											// ID used to identify the field throughout the theme
			    'Setting D', 											// The label to the left of the option interface element
			    array(&$this, 'settings_field_input_textarea'),			// The name of the function responsible for rendering the option interface
			    'wp-clean-admin-general', 								// The page on which this option will be displayed
			    'wp_clean_admin-general',								// The name of the section to which this field belongs
			    array(
			        'field' => 'setting_d'								// The array of arguments to pass to the callback. In this case, just a description.
			    )
			);

			add_settings_field(
			    'setting_e',											// ID used to identify the field throughout the theme
			    'Setting E', 											// The label to the left of the option interface element
			    array(&$this, 'settings_field_input_checkbox'),			// The name of the function responsible for rendering the option interface
			    'wp-clean-admin-general', 								// The page on which this option will be displayed
			    'wp_clean_admin-general',								// The name of the section to which this field belongs
			    array(
			        'field' => 'setting_e'								// The array of arguments to pass to the callback. In this case, just a description.
			    )
			);

        } 
        
		/**
		 * This function provides descriptions for settings sections
		 */
        public function settings_section_wp_plugin_template() {
        
            // Think of this as help text for the section.
            echo 'These settings do things for the WP Plugin Template.';
        }
        
        /**
         * Generate text inputs for settings fields
         */
        public function settings_field_input_text( $args ) {
        
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $value = get_option($field);
            // echo a proper input type="text"
            echo sprintf('<input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value);
        }
        
        /**
         * Generate textarea inputs for settings fields
         */
        public function settings_field_input_textarea( $args ) {
        	
            // Get the field name from the $args array
        	$field = $args['field'];
            // Get the value of this setting
        	$value = get_option($field);
        	// Render the output        	
        	echo  sprintf('<textarea id="%s" name="%s" rows="5" cols="50">%s</textarea>', $field, $field, $value);        	
        }
        
		/**
		 * Generate checkbox inputs for settings fields
		 */
		public function settings_field_input_checkbox( $args ) {
			
		    // Get the field name from the $args array
			$field = $args['field'];
		    // Get the value of this setting and check to see if the checkbox has been checked
			$value = checked( get_option($field), TRUE, FALSE);
			// Render the output
			echo sprintf('<input type="checkbox" name="%s" id="%s" value="1" %s>', $field, $field, $value );        	
		}		
        
        /**
         * Validate text field inputs in the general settings group
         */
        public function validate_text_input( $input ) {  
              
            if ( is_array( $input ) ) {
	            // Define the array for the updated options  
	            $output = array();  
           		// Loop through each of the options sanitizing the data  
	            foreach( $input as $key => $value ) {  
	                if( isset ( $input[$key] ) ) {  
	                    $output[$key] =  strip_tags( stripslashes( $input[$key] ) );  
	                } // end if   
	            } // end foreach  
            } else {
            	$output = strip_tags( stripslashes( $input ) );
            }

            // Return the new collection  
            return apply_filters( 'sanitize_general_settings', $output, $input );          
        }

    } 
}