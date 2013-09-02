<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   WP_Clean_Admin
 * @author    John Buckingham <john@pancakecreative.com>
 * @license   GPL-2.0+
 * @link      http://pancakecreative.com
 * @copyright 2013 John Buckingham
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<div class="wrap">

	<?php screen_icon('options-general'); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

    <?php
    if ( ! empty( $message ) ) {
        echo '<div class="updated"><p>' . $message . '</p></div>';
    }
        
    if ( isset ( $_GET['tab'] ) ) $this->wp_clean_admin_admin_tabs( $_GET['tab'] ); else $this->wp_clean_admin_admin_tabs( 'general' );
    ?>
    
    <div id="poststuff">
   	    <form method="post" action="options.php">
	        <?php
	        
	        // wp_nonce_field('clean-admin-settings');
	        
	        if ( $pagenow == 'plugins.php' && $_GET['page'] == 'wp-clean-admin' ) { 
	        
	        	if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab']; 
	        	else $tab = 'general'; 
	        	
	        	echo '<table class="form-table">';
	        	switch ( $tab ){
	        		case 'general' :
	        			
    			        @settings_fields('wp_clean_admin-general');
    			        @do_settings_fields('wp_clean_admin-general');
    			        do_settings_sections('wp-clean-admin-general');
    			        @submit_button();
	        			
	        		break; 
	        		case 'hide' : 

	        			@settings_fields('wp_clean_admin-hide');
	        			@do_settings_fields('wp_clean_admin-hide');
				        do_settings_sections('wp-clean-admin-hide');
						@submit_button();

	        		break;
	        		case 'customize' :
	        		
	        			@settings_fields('wp_clean_admin-customize');
	        			@do_settings_fields('wp_clean_admin-customize');
	        			do_settings_sections('wp-clean-admin-customize');
						@submit_button();
						
	        		break;
	        		case 'reset' : 
	        			?>
	        			<?php
	        		break;
	        		
	        	}
	        	echo '</table>';
	        }
	        ?>
	    </form>
	</div>

</div>
