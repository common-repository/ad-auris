<?php
/*/**
* @package ad-auris
*/


/*
Plugin Name: Ad Auris
Plugin: https://ad-auris.com
Description: A Wordpress plugin helper for Ad Auris's Auto Narration feature and for inserting an embed code into your article template.
Version: 1.0.0
Author: Ad Auris
Author URI: https://ad-auris.com
License: GPLv2 or later
Text Domain: ad-auris
*/

if ( ! defined( 'ABSPATH' ) ) exit;


if ( !function_exists("ad_auris_wp_plugin") ) {
	
	
	$options = get_option( 'ad_auris_wp_plugin_settings' );
	
	if ( ( isset( $options['ad_auris_wp_plugin_section3_text_field_1'] ) ) && ( !empty( $options['ad_auris_wp_plugin_section3_text_field_2'] ) ) && is_numeric( $options['ad_auris_wp_plugin_section3_text_field_2'] ) ) {
					
			$function_custom_priority = ( $options['ad_auris_wp_plugin_section3_text_field_2'] );
	
	} else {
		$function_custom_priority = '10';
	}
	
		
	function ad_auris_wp_plugin($content) {
	
		$options = get_option( 'ad_auris_wp_plugin_settings' );

    #End the function if the input value is not a string
		if ( !is_string($options['ad_auris_wp_plugin_section2_textarea_field_1']) ) {
      return $content;
    }
		
		#if ( is_single()/post || is_singular()/Post&Page + && is_main_query()/main post query
		if ( ( is_single() && is_main_query() ) || ( ( ( isset( $options['ad_auris_wp_plugin_section3_checkbox_field_2'] ) ) && ( !empty( $options['ad_auris_wp_plugin_section3_checkbox_field_2'] ) ) ) && ( is_singular() && is_main_query() ) ) && ( ( empty( $options['ad_auris_wp_plugin_section3_text_field_1'] ) ) || ( !is_page( $page_id_exclude_array ) ) ) ) {
			
			/* Top content */
			if ( ( ( isset( $options['ad_auris_wp_plugin_section2_textarea_field_1'] ) ) && ( !empty( $options['ad_auris_wp_plugin_section2_textarea_field_1'] ) ) && ( !isset( $options['ad_auris_wp_plugin_section2_checkbox_field_1'] ) ) && ( empty( $options['ad_auris_wp_plugin_section2_checkbox_field_1'] ) ) )  ) {
			// && ( !is_single( $post_id_exclude_top_array ) )
			$content = '<!-- ad_auris WP: Add custom content to top of post/page --><div id="ad_auris-wp-acctp-top"><iframe style="width: 100%; height: 250px; border: none; display: none" data-org=' . stripslashes( $options['ad_auris_wp_plugin_section2_textarea_field_1'] ) . ' allowfullscreen="false" frameborder="0" id="ad-auris-iframe" scrolling="no"></iframe><script src="https://cdn.jsdelivr.net/npm/ad-auris-iframe-distribution@latest/script.js"></script></div><!-- /ad_auris WP: Add custom content to top of post/page --><!-- ad_auris WP: Add custom content to bottom of post/page: Standard Content START --><div id="ad_auris-wp-acctp-original-content">' . $content;
			
			} else {
				$content = '<!-- ad_auris WP: Add custom content to bottom of post/page: Standard Content START --><div id="ad_auris-wp-acctp-original-content">' . $content;
			}
			
			/* Bottom contet */
			if ( ( ( isset( $options['ad_auris_wp_plugin_section1_textarea_field_1'] ) ) && ( !empty( $options['ad_auris_wp_plugin_section1_textarea_field_1'] ) ) && ( !isset( $options['ad_auris_wp_plugin_section1_checkbox_field_1'] ) ) && ( empty( $options['ad_auris_wp_plugin_section1_checkbox_field_1'] ) ) ) && ( !is_single( $post_id_exclude_bottom_array ) ) ) {
			
			$content = $content . '<!-- ad_auris WP: Add custom content to bottom of post/page: Standard Content START --></div><!-- ad_auris WP: Add custom content to bottom of post/page --><div id="ad_auris-wp-acctp-bottom"><iframe style="width: 100%; height: 250px; border: none; display: none" data-org=' . stripslashes( $options['ad_auris_wp_plugin_section1_textarea_field_1'] ) . ' allowfullscreen="false" frameborder="0" id="ad-auris-iframe" scrolling="no"></iframe><script src="https://cdn.jsdelivr.net/npm/ad-auris-iframe-distribution@latest/script.js"></script></div><!-- /ad_auris WP: Add custom content to bottom of post/page -->';
			
			} else {
				$content = $content . '<!-- ad_auris WP: Add custom content to bottom of post/page: Standard Content END --></div>';
			}
			
		

			return $content;
		
		} else { return $content; } # Page etc.
	}

	add_filter( 'the_content', 'ad_auris_wp_plugin', $function_custom_priority );
}

function add_custom_css() {
    wp_enqueue_style('custom-css', get_template_directory_uri() . '/custom.css');   
    // Add dynamic style if a single page is displayed
    if ( is_single() ) {
        $color = "#000111";
        $custom_css = ".mycolor{ background: {$color}; }";
        wp_add_inline_style( 'custom-css', $custom_css );
    }
}
add_action( 'wp_enqueue_scripts', 'add_custom_css' );


/* ----- WP Admin ----- */

add_action( 'admin_menu', 'ad_auris_wp_plugin_add_admin_menu' );
add_action( 'admin_init', 'ad_auris_wp_plugin_settings_init' );

# Menu
function ad_auris_wp_plugin_add_admin_menu() { 

	// add_options_page( 'ad_auris WP: Add custom content', 'ad_auris WP: Add custom content', 'manage_options', 'ad_auris_wp_plugin', 'ad_auris_wp_plugin_options_page' );
	add_menu_page( 'Auto Narration', 'Ad Auris', 'manage_options', 'ad_auris_wp_plugin', 'ad_auris_wp_plugin_options_page', 'dashicons-controls-play', 80 );
}



# Prepare
function ad_auris_wp_plugin_settings_init() { 

	register_setting( 'ad_auris WP: Add custom content (pluginPage)', 'ad_auris_wp_plugin_settings', 'sanitize_callback' );

	# Define section 2 + header h2 (info)
	add_settings_section(
		'ad_auris_wp_plugin_section2', 
		__( 'RSS Auto Narrate Settings', 'ad_auris_wp_plugin' ), 
		'ad_auris_wp_plugin_settings_section_callback2', 
		'ad_auris WP: Add custom content (pluginPage)'
	);
	
	# Define content field (top)
	add_settings_field( 
		'ad_auris_wp_plugin_section2_textarea_field_1', 
		__( 'RSS Key:', 'ad_auris_wp_plugin' ), 
		'ad_auris_wp_plugin_section2_textarea_field_1_render', 
		'ad_auris WP: Add custom content (pluginPage)', 
		'ad_auris_wp_plugin_section2' 
	);
	

	# Add to page
	add_settings_field( 
		'ad_auris_wp_plugin_section3_checkbox_field_2', 
		__( 'Add to pages:', 'ad_auris_wp_plugin' ), 
		'ad_auris_wp_plugin_section3_checkbox_field_2_render', 
		'ad_auris WP: Add custom content (pluginPage)', 
		'ad_auris_wp_plugin_section3' 
	);
	
	
	# Clear at uninstall
	add_settings_field( 
		'ad_auris_wp_plugin_section3_checkbox_field_1', 
		__( 'Clear plugin data:', 'ad_auris_wp_plugin' ), 
		'ad_auris_wp_plugin_section3_checkbox_field_1_render', 
		'ad_auris WP: Add custom content (pluginPage)', 
		'ad_auris_wp_plugin_section3' 
	);

}	
	
# Make content field (top)
function ad_auris_wp_plugin_section2_textarea_field_1_render() { 

	$options = get_option( 'ad_auris_wp_plugin_settings' );
	?>
	
	<textarea cols='' rows='1' style='width:100%' name='ad_auris_wp_plugin_settings[ad_auris_wp_plugin_section2_textarea_field_1]'><?php if ( isset( $options['ad_auris_wp_plugin_section2_textarea_field_1'] ) && !empty( $options['ad_auris_wp_plugin_section2_textarea_field_1'] )) { echo $options['ad_auris_wp_plugin_section2_textarea_field_1']; } else { echo ""; } ?></textarea>
	
	<?php
	echo '<br /><br />';
	echo __( 'Ensure your special RSS Key is included before saving.', 'ad-auris' );
}

# Add to page exclude
function ad_auris_wp_plugin_section3_text_field_1_render() { 

	$options = get_option( 'ad_auris_wp_plugin_settings' );
	?>
	<input type='text' name='ad_auris_wp_plugin_settings[ad_auris_wp_plugin_section3_text_field_1]' value='<?php 
	
	if ( isset( $options['ad_auris_wp_plugin_section3_text_field_1'] ) && !empty( $options['ad_auris_wp_plugin_section3_text_field_1'] )) {	
		echo $options['ad_auris_wp_plugin_section3_text_field_1']; 
	} else { echo __( '', 'ad_auris_wp_plugin' ); }
	
	?>' cols='' style='width:100%' >
	<?php
	
	echo __( 'Page(s) ID, comma separated (if active "add to pages" option). Only numbers and commas, without spaces!' );

}



# Function priority
function ad_auris_wp_plugin_section3_text_field_2_render() { 

	$options = get_option( 'ad_auris_wp_plugin_settings' );
	?>
	<input type='text' name='ad_auris_wp_plugin_settings[ad_auris_wp_plugin_section3_text_field_2]' value='<?php 
	
	if ( isset( $options['ad_auris_wp_plugin_section3_text_field_2'] ) && !empty( $options['ad_auris_wp_plugin_section3_text_field_2'] )) {	
		echo $options['ad_auris_wp_plugin_section3_text_field_2']; 
	} else { echo __( '', 'ad_auris_wp_plugin' ); }
	
	?>' cols='' style='width:100%' >
	<?php
	
	echo __( 'Here You can set own priority for the content modifying function. It\'s useful when You use short codes or page builders. E.g. 99, 999, 9999, 99999, 999999... Only digits!' );

}





# Make clear data checkbox
function ad_auris_wp_plugin_section3_checkbox_field_1_render() { 

	$options = get_option( 'ad_auris_wp_plugin_settings' );
	?>
	<input type='checkbox' name='ad_auris_wp_plugin_settings[ad_auris_wp_plugin_section3_checkbox_field_1]' <?php if ( isset( $options['ad_auris_wp_plugin_section3_checkbox_field_1'] ) ) { checked( $options['ad_auris_wp_plugin_section3_checkbox_field_1'], 1 ); } ?> value='1'>
	
	<?php
	
	echo __( 'Remove all plugin data when uninstall this plugin', 'ad_auris_wp_plugin' );

}

# Section 2 text/description
function ad_auris_wp_plugin_settings_section_callback2() { 

	echo __( "Insert your RSS Key to auto narrate all of your content with a one time insert. To retrieve your RSS Key, please go to 'Auto-narrate with RSS' in the Settings page in your Ad Auris Dashboard and copy the item labelled 'RSS Key'.", 'ad_auris_wp_plugin' );
	
}

# Section 3 text/description
function ad_auris_wp_plugin_settings_section_callback3() { 

	echo __( 'Other plugin settings', 'ad_auris_wp_plugin' );
	
}


# Save
function ad_auris_wp_plugin_options_page() { 

	?>
	<form action='options.php' method='post'>
		
		<h2>Ad Auris</h2>
		
		<?php
				
		settings_fields( 'ad_auris WP: Add custom content (pluginPage)' );
		do_settings_sections( 'ad_auris WP: Add custom content (pluginPage)' );
		submit_button();
		?>
		
	</form>
	<!-- Dashboard Button  -->
	<button class="btn btn-success" onclick=" window.open('https://dashboard.ad-auris.com','_blank')">Go to my Ad Auris Dashboard</button>

	<?php

		
}


# Uninstall plugin

register_uninstall_hook( __FILE__, 'ad_auris_wp_plugin_uninstall' );
#register_deactivation_hook( __FILE__, 'ad_auris_wp_plugin_uninstall' );

function ad_auris_wp_plugin_uninstall() {

	$options = get_option( 'ad_auris_wp_plugin_settings' );

	if ( ( isset( $options['ad_auris_wp_plugin_section3_checkbox_field_1'] ) ) && ( !empty( $options['ad_auris_wp_plugin_section3_checkbox_field_1'] ) ) ) {
		
		# Clear at uninstall
		$option_to_delete = 'ad_auris_wp_plugin_settings';
		delete_option( $option_to_delete );
	}
	
}


