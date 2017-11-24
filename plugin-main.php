<?php 
/*
Plugin Name: Maintenance Mode plugin Wordpress 
Plugin URI: http://www.techjahid.com/maintenance-mode
Description: This plugin will help you enable maintenance mode in your site
Author URI: http://techjahid.com
Version: 1.0
*/

/* Adding Latest jQuery from Wordpress */
function currently_under_maintenance_wp_latest_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'currently_under_maintenance_wp_latest_jquery');

?>

<?php 

/*Some Set-up*/
define('Maintenance_mode_WP', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );

/* Including all files */
function maintenance_mode_files() {;
    wp_enqueue_style('bootstrap-css', Maintenance_mode_WP.'bootstrap.min.css');
}
add_action( 'wp_enqueue_scripts', 'maintenance_mode_files' );

 ?>



<?php

function add_maintenance_mode_options_framwrork()  
{  
	add_options_page('Maintenance Mode Options', 'Custom Maintenance Mode Options', 'manage_options', 'maintenance-mode-settings','maintenance_mode_options_framwrork');  
}  
add_action('admin_menu', 'add_maintenance_mode_options_framwrork');

// Default options values
$maintenance_mode_options = array(
	'maintenance_message' => '<h1>Notice :</h1>',
	'message_color' => '#80d6a3',
	'message_margin_top_and_bottom' => '40px',
	'message_margin_left_and_right' => '220px',
	'image_url' => 'https://goo.gl/uXDfFt',
);

if ( is_admin() ) : // Load only if we are viewing an admin page

function maintenance_mode_register_settings() {
	// Register settings and call sanitation functions
	register_setting( 'maintenance_mode_p_options', 'maintenance_mode_options', 'maintenance_mode_validate_options' );
}

add_action( 'admin_init', 'maintenance_mode_register_settings' );


// Function to generate options page
function maintenance_mode_options_framwrork() { 
	

	global $maintenance_mode_options;

	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false; // This checks whether the form has just been submitted. ?>

	<div class="wrap">

	
	<h2>Maintenance Mode plugin Options</h2>

	<?php if ( false !== $_REQUEST['updated'] ) : ?>
	<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
	<?php endif; // If the form has just been submitted, this shows the notification ?>

	<form method="post" action="options.php">

	<?php $settings = get_option( 'maintenance_mode_options', $maintenance_mode_options ); /*Second parameter can be deleted*/?>

	
	<?php settings_fields( 'maintenance_mode_p_options' );
	/* This function outputs some hidden fields required by the form,
	including a nonce, a unique number used to ensure the form has been submitted from the admin page
	and not somewhere else, very important for security */ ?>

	<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=xwp7756oceiv36c2ienktarnwqqbi7e3gq4rzg1pzn31f9w8"></script>
	<script>tinymce.init({ selector:'textarea' });</script>

	<!-- Latest compiled and minified CSS -->



	<table class="form-table"><!-- Grab a hot cup of coffee, yes we're using tables! -->
	
	
		<tr valign="top">
			<th scope="row"><label for="maintenance_message">Type your message here</label></th>
			<td>
				<textarea id="maintenance_message" name="maintenance_mode_options[maintenance_message]">

				<?php echo stripslashes($settings['maintenance_message']); ?>
					

				</textarea><p class="description">Keep it blank for no message</p>

			

			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="cursor_color">Put your image link here</label></th>
			<td>
					<input type="text" style="width:500px" name="maintenance_mode_options[image_url]" value="<?php echo stripslashes($settings['image_url']); ?>" /><p class="description">Keep it blank for no image(default image link <a href="https://goo.gl/uXDfFt">https://goo.gl/uXDfFt</a>)</p>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="cursor_color">Put your margin top/bottom values </label></th>
			<td>
				<input  type="text" name="maintenance_mode_options[message_margin_top_and_bottom]" value="<?php echo stripslashes($settings['message_margin_top_and_bottom']); ?>"  /><p class="description">Increase the value for margin-top and decrease the value for margin-bottom</p>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="cursor_color">Put your margin left/right values </label></th>
			<td>
				<input  type="text" name="maintenance_mode_options[message_margin_left_and_right]" value="<?php echo stripslashes($settings['message_margin_left_and_right']); ?>"  /><p class="description">Increase the value for margin-right and decrease the value for margin-left</p>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="cursor_color">ScrollBar color</label></th>
			<td>
				<input id="cursor_color" type="color" name="maintenance_mode_options[message_color]" value="<?php echo stripslashes($settings['message_color']); ?>" class="my-color-field" /><p class="description">Select scrollbar color here. You can also add html HEX color code.</p>
			</td>
		</tr>

			
	</table>

	<p class="submit"><input type="submit" class="button-primary" value="Save Options" /></p>

	</form>

	</div>

	<?php
}


function maintenance_mode_validate_options( $input ) { /*the name input can be anything*/
	// We strip all tags from the text field, to avoid vulnerablilties like XSS

	$input['maintenance_message'] = wp_filter_post_kses( $input['maintenance_message'] );
	$input['message_color'] = wp_filter_post_kses( $input['message_color'] );
	$input['image_url'] = wp_filter_post_kses( $input['image_url'] );
	$input['message_margin_top_and_bottom'] = wp_filter_post_kses( $input['message_margin_top_and_bottom'] );
	$input['message_margin_left_and_right'] = wp_filter_post_kses( $input['message_margin_left_and_right'] );
	
				
	return $input;
}

endif;  // EndIf is_admin()

function maintenance_mode_active() {?>

<?php global $maintenance_mode_options; $maintenance_mode_settings = get_option( 'maintenance_mode_options', $maintenance_mode_options ); ?>

		<?php 
			if($maintenance_mode_settings['image_url']!=''){
				$maintenance_mode_options['image_url'] = '';
				$maintenance_mode_options['maintenance_message']='';
			}
			if($maintenance_mode_settings['maintenance_message']!=''){
				$maintenance_mode_options['maintenance_message'] = '';
				$maintenance_mode_options['image_url']='';
			}
			if($maintenance_mode_settings['message_color']!=''){
				$maintenance_mode_options['message_color'] = '';
			}
			if($maintenance_mode_settings['message_margin_top_and_bottom']!=''){
				$maintenance_mode_options['message_margin_top_and_bottom'] = '';
			}
			if($maintenance_mode_settings['message_margin_left_and_right']!=''){
				$maintenance_mode_options['message_margin_left_and_right'] = '';
			}

			if($maintenance_mode_settings['image_url'] == '' && $maintenance_mode_settings['maintenance_message'] == ''){
				$maintenance_mode_options['image_url'] = '';
				$maintenance_mode_options['maintenance_message'] = '';
			}



		 ?>	

	
		 
		<script type="text/javascript">
                    jQuery(function() {
                    	jQuery( "body" ).replaceWith(`<div class='image_show'><style>body, html {height: 100%;margin: 0;}html{background: url(<?php if($maintenance_mode_options['image_url']!=''){echo $maintenance_mode_options['image_url'];}else{echo $maintenance_mode_settings['image_url'];} ?>);height: 100%;  background-position: center;background-repeat: no-repeat;background-size: cover;background-size:100% auto;  }</style></div><center style='color: <?php if($maintenance_mode_options['message_color']!=''){echo $maintenance_mode_options['message_color'];}else{echo $maintenance_mode_settings['message_color'];} ?>;margin:<?php if($maintenance_mode_options['message_margin_top_and_bottom']!=''){echo $maintenance_mode_options['message_margin_top_and_bottom'];}else{echo $maintenance_mode_settings['message_margin_top_and_bottom'];} ?> <?php if($maintenance_mode_options['message_margin_left_and_right']!=''){echo $maintenance_mode_options['message_margin_left_and_right'];}else{echo $maintenance_mode_settings['message_margin_left_and_right'];} ?>  0px 0px;'><?php if($maintenance_mode_options['maintenance_message']!=''){echo $maintenance_mode_options['maintenance_message'];}else{echo $maintenance_mode_settings['maintenance_message'];} ?></center>` )
                    });
                </script> 



<?php
}
add_action('wp_head', 'maintenance_mode_active');