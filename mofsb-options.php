<?php
/**
 * Settings Page of the Fastest Share Buttons for WordPress.
 * Version: 1.0.7
 */
if ( ! defined( 'ABSPATH' ) ) { exit(0); }

function mofsb_sanitize_checkbox( $mofsb_value ) {
	return empty( $mofsb_value ) ? 0 : 1;
}

function mofsb_sanitize_int_range( $mofsb_value, $mofsb_min, $mofsb_max, $mofsb_default ) {
	$mofsb_value = absint( $mofsb_value );

	if ( $mofsb_value < $mofsb_min || $mofsb_value > $mofsb_max ) {
		return $mofsb_default;
	}

	return $mofsb_value;
}

function mofsb_sanitize_button_size( $mofsb_value ) {
	return mofsb_sanitize_int_range( $mofsb_value, 18, 128, 48 );
}

function mofsb_sanitize_icon_size( $mofsb_value ) {
	return mofsb_sanitize_int_range( $mofsb_value, 1, 100, 60 );
}

function mofsb_sanitize_shrink_width( $mofsb_value ) {
	return mofsb_sanitize_int_range( $mofsb_value, 100, 800, 400 );
}

function mofsb_sanitize_shrink_amount( $mofsb_value ) {
	return mofsb_sanitize_int_range( $mofsb_value, 10, 100, 75 );
}

function mofsb_sanitize_padding( $mofsb_value ) {
	return mofsb_sanitize_int_range( $mofsb_value, 0, 100, 0 );
}

function mofsb_sanitize_floating_height( $mofsb_value ) {
	return mofsb_sanitize_int_range( $mofsb_value, 1, 100, 25 );
}

function mofsb_sanitize_floating_mobile( $mofsb_value ) {
	return mofsb_sanitize_int_range( $mofsb_value, 100, 1024, 600 );
}

function mofsb_sanitize_floating_side( $mofsb_value ) {
	return ( 'right' === $mofsb_value ) ? 'right' : 'left';
}

function mofsb_sanitize_x_handle( $mofsb_value ) {
	$mofsb_value = sanitize_text_field( $mofsb_value );
	$mofsb_value = ltrim( trim( $mofsb_value ), '@' );
	$mofsb_value = preg_replace( '/[^A-Za-z0-9_]/', '', $mofsb_value );

	return substr( $mofsb_value, 0, 15 );
}

// Show an admin notice inviting to MOFSB settings
add_action( 'admin_notices', 'mofsb_first_settings_notice' );
function mofsb_first_settings_notice() {
	if ( get_option( 'mofsb_display_first_settings_notice' ) != 1 ) {
		echo '<div class="notice-info notice"><p>Fastest Share Buttons for WordPress is installed and activated. Now go to <a href="/wp-admin/options-general.php?page=mofsb-options">Settings</a> and select a few <strong>display locations</strong> of your choice in order to have the buttons displayed. This notice will be automatically dismissed after first saving the Fastest Share Buttons options.</p></div>';
	}
}

// Create custom plugin settings menu
add_action( 'admin_menu', 'mofsb_create_menu' );
function mofsb_create_menu() {
	add_options_page( 'Fastest Share Buttons Settings page title', 'Fastest Share Buttons', 'manage_options', 'mofsb-options', 'mofsb_options_management' );
	add_action( 'admin_init', 'mofsb_register_settings' );
}

function mofsb_register_settings() {
	$mofsb_checkbox_options = array(
		'mofsb_display_first_settings_notice',
		'mofsb_display_floating_posts',
		'mofsb_display_floating_pages',
		'mofsb_display_floating_archives',
		'mofsb_display_floating_front',
		'mofsb_display_floating_blog',
		'mofsb_display_before_content_posts',
		'mofsb_display_before_content_pages',
		'mofsb_display_before_content_archives',
		'mofsb_display_before_content_front',
		'mofsb_display_before_content_blog',
		'mofsb_display_after_content_posts',
		'mofsb_display_after_content_pages',
		'mofsb_display_after_content_archives',
		'mofsb_display_after_content_front',
		'mofsb_display_after_content_blog',
		'mofsb_button_facebook',
		'mofsb_button_twitter',
		'mofsb_button_pinterest',
		'mofsb_button_tumblr',
		'mofsb_button_email',
		'mofsb_style_floating_stretch',
		'mofsb_enqueue_important_styles',
		'mofsb_enqueue_overqualify_styles',
		'mofsb_enqueue_overreset_styles',
		'mofsb_delete_options_uninstall',
	);

	foreach ( $mofsb_checkbox_options as $mofsb_option_name ) {
		register_setting( 'mofsb-settings-group', $mofsb_option_name, 'mofsb_sanitize_checkbox' );
	}

	register_setting( 'mofsb-settings-group', 'mofsb_style_button_size', 'mofsb_sanitize_button_size' );
	register_setting( 'mofsb-settings-group', 'mofsb_style_icon_size', 'mofsb_sanitize_icon_size' );
	register_setting( 'mofsb-settings-group', 'mofsb_style_shrink_width', 'mofsb_sanitize_shrink_width' );
	register_setting( 'mofsb-settings-group', 'mofsb_style_shrink_amount', 'mofsb_sanitize_shrink_amount' );
	register_setting( 'mofsb-settings-group', 'mofsb_style_padding_top_static', 'mofsb_sanitize_padding' );
	register_setting( 'mofsb-settings-group', 'mofsb_style_padding_bottom_static', 'mofsb_sanitize_padding' );
	register_setting( 'mofsb-settings-group', 'mofsb_style_floating_side', 'mofsb_sanitize_floating_side' );
	register_setting( 'mofsb-settings-group', 'mofsb_style_floating_height', 'mofsb_sanitize_floating_height' );
	register_setting( 'mofsb-settings-group', 'mofsb_style_floating_mobile', 'mofsb_sanitize_floating_mobile' );
	register_setting( 'mofsb-settings-group', 'mofsb_x_handle', 'mofsb_sanitize_x_handle' );
}

function mofsb_options_management() {
?>
<div class="wrap">
<h1>Fastest Share Buttons Settings</h1>

<form method="post" action="options.php">
	<?php settings_fields( 'mofsb-settings-group' ); ?>
	<?php do_settings_sections( 'mofsb-settings-group' ); ?>

	<?php submit_button(); ?>

	<input name="mofsb_display_first_settings_notice" type="hidden" value="1" />

	<h2>Fastest Share Buttons display locations</h2>
	<p><strong>Choose which share bar to display and where.</strong><br>
	Beware that "archives", "front page" and "blog" can behave differently depending on your "Front page displays", available in the "<a href="/wp-admin/options-reading.php">Reading</a>" section.</p>

	<table class="form-table">
		<tr valign="top">
			<th scope="row">Display floating share bar:</th>
			<td>on posts <input name="mofsb_display_floating_posts" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_display_floating_posts' ) ); ?> /></td>
			<td>on pages <input name="mofsb_display_floating_pages" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_display_floating_pages' ) ); ?> /></td>
			<td>on archives <input name="mofsb_display_floating_archives" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_display_floating_archives' ) ); ?> /></td>
			<td>on front page <input name="mofsb_display_floating_front" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_display_floating_front' ) ); ?> /></td>
			<td>on blog <input name="mofsb_display_floating_blog" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_display_floating_blog' ) ); ?> /></td>
		</tr>
		<tr valign="top">
			<th scope="row">Display fixed share bar, before the content:</th>
			<td>on posts <input name="mofsb_display_before_content_posts" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_display_before_content_posts' ) ); ?> /></td>
			<td>on pages <input name="mofsb_display_before_content_pages" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_display_before_content_pages' ) ); ?> /></td>
			<td>on archives <input name="mofsb_display_before_content_archives" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_display_before_content_archives' ) ); ?> /></td>
			<td>on front page <input name="mofsb_display_before_content_front" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_display_before_content_front' ) ); ?> /></td>
			<td>on blog <input name="mofsb_display_before_content_blog" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_display_before_content_blog' ) ); ?> /></td>
		</tr>
		<tr valign="top">
			<th scope="row">Display fixed share bar, after the content:</th>
			<td>on posts <input name="mofsb_display_after_content_posts" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_display_after_content_posts' ) ); ?> /></td>
			<td>on pages <input name="mofsb_display_after_content_pages" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_display_after_content_pages' ) ); ?> /></td>
			<td>on archives <input name="mofsb_display_after_content_archives" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_display_after_content_archives' ) ); ?> /></td>
			<td>on front page <input name="mofsb_display_after_content_front" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_display_after_content_front' ) ); ?> /></td>
			<td>on blog <input name="mofsb_display_after_content_blog" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_display_after_content_blog' ) ); ?> /></td>
		</tr>
		<tr valign="top">
			<th scope="row">Display using shortcode:</th>
			<td colspan="5">beside the options above use the <strong>[mofsb]</strong> shortcode anywhere inside content to display a horizontal, fixed share bar.</td>
		</tr>
	</table>

	<h2>Fastest Share Buttons selection</h2>
	<p><strong>Choose which buttons to display on the share bars.</strong><br>
	Buttons chosen here will display (or not) on all bars.</p>

	<table class="form-table">
		<tr valign="top">
			<th scope="row">Display Facebook button:</th>
			<td><input name="mofsb_button_facebook" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_button_facebook', '1' ) ); ?> /></td>
		</tr>
		<tr valign="top">
			<th scope="row">Display X (Twitter) button:</th>
			<td><input name="mofsb_button_twitter" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_button_twitter', '1' ) ); ?> /></td>
		</tr>
		<tr valign="top">
			<th scope="row">X handle (optional):</th>
			<td>
				<input name="mofsb_x_handle" type="text" maxlength="15" value="<?php echo esc_attr( get_option( 'mofsb_x_handle', '' ) ); ?>" />
				<span style="margin-left:10px;">(without the @, used for the X share attribution when available)</span>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Display Pinterest button:</th>
			<td><input name="mofsb_button_pinterest" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_button_pinterest', '1' ) ); ?> /></td>
		</tr>
		<tr valign="top">
			<th scope="row">Display Tumblr button:</th>
			<td><input name="mofsb_button_tumblr" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_button_tumblr', '1' ) ); ?> /></td>
		</tr>
		<tr valign="top">
			<th scope="row">Display Email button:</th>
			<td><input name="mofsb_button_email" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_button_email', '1' ) ); ?> /></td>
		</tr>
	</table>

	<h2>Fastest Share Buttons styling</h2>
	<p><strong>Choose the buttons sizes and positions.</strong><br>
	Choose buttons size and icon size inside the buttons; also chose spacing for static bars, side and position for floating bar and even a shrink amount for tiny little screens, so the buttons will not stack up on these.</p>

	<table class="form-table">
		<tr valign="top">
			<th scope="row">Button size (in px)</th>
			<td>
				<input name="mofsb_style_button_size" type="number" min="18" max="128" value="<?php echo esc_attr( get_option( 'mofsb_style_button_size', '48' ) ); ?>" />
				<span style="margin-left:10px;">(size of each button on the bars, both vertically and horizontally)</span>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Icon size (in %)</th>
			<td>
				<input name="mofsb_style_icon_size" type="number" min="1" max="100" value="<?php echo esc_attr( get_option( 'mofsb_style_icon_size', '60' ) ); ?>" />
				<span style="margin-left:10px;">(percent from button size)</span>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">At which screen width to shrink down the buttons: (in px)</th>
			<td>
				<input name="mofsb_style_shrink_width" type="number" min="100" max="800" value="<?php echo esc_attr( get_option( 'mofsb_style_shrink_width', '400' ) ); ?>" />
				<span style="margin-left:10px;">(below this screen width make buttons smaller)</span>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">How much to shrink down the buttons (in %)</th>
			<td>
				<input name="mofsb_style_shrink_amount" type="number" min="10" max="100" value="<?php echo esc_attr( get_option( 'mofsb_style_shrink_amount', '75' ) ); ?>" />
				<span style="margin-left:10px;">(100% actually disables this effect; usually 75% will do)</span>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Space above the static bar (in px)</th>
			<td>
				<input name="mofsb_style_padding_top_static" type="number" min="0" max="100" value="<?php echo esc_attr( get_option( 'mofsb_style_padding_top_static', '0' ) ); ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Space below the static bar (in px)</th>
			<td>
				<input name="mofsb_style_padding_bottom_static" type="number" min="0" max="100" value="<?php echo esc_attr( get_option( 'mofsb_style_padding_bottom_static', '0' ) ); ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">On which side to show the floating bar (left or right)</th>
			<td>
				<input name="mofsb_style_floating_side" type="radio" value="left" <?php checked( 'left', get_option( 'mofsb_style_floating_side', 'left' ) ); ?> /><span style="margin-right:10px;">Left</span>
				<input name="mofsb_style_floating_side" type="radio" value="right" <?php checked( 'right', get_option( 'mofsb_style_floating_side', 'left' ) ); ?> /><span style="margin-right:10px;">Right</span>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">How high on screen to float the floating bar (in %)</th>
			<td>
				<input name="mofsb_style_floating_height" type="number" min="1" max="100" value="<?php echo esc_attr( get_option( 'mofsb_style_floating_height', '25' ) ); ?>" />
				<span style="margin-left:10px;">(in % down from the screen top)</span>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">At which screen width to float the bar at bottom on mobiles (in px)</th>
			<td>
				<input name="mofsb_style_floating_mobile" type="number" min="100" max="1024" value="<?php echo esc_attr( get_option( 'mofsb_style_floating_mobile', '600' ) ); ?>" />
				<span style="margin-left:10px;">(stick it to bottom below this screen width)</span>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Stretch bar at bottom:</th>
			<td>
				<input name="mofsb_style_floating_stretch" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_style_floating_stretch', '1' ) ); ?> />
				<span style="margin-left:10px;">(stretch the bar when at bottom)</span>
			</td>
		</tr>
	</table>

	<h2>Compatibility management</h2>
	<p><strong>Try these only in case the buttons do not look or behave nicely.</strong><br>
	With these options there are better chances to keep theme and plugin styles from propagating over the Fastest Share Buttons styles, but they may break its very own styles as well. Play with them in case of problems and find your best combination. Use as little as possible.</p>

	<table class="form-table">
		<tr valign="top">
			<th scope="row">Make styles !important:</th>
			<td>
				<input name="mofsb_enqueue_important_styles" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_enqueue_important_styles', '0' ) ); ?> />
				<span style="margin-left:10px;">(add !important to style attributes)</span>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Styles overqualify:</th>
			<td>
				<input name="mofsb_enqueue_overqualify_styles" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_enqueue_overqualify_styles', '0' ) ); ?> />
				<span style="margin-left:10px;">(add element-specific selector to CSS styles)</span>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Solve styles known compatibility issues:</th>
			<td>
				<input name="mofsb_enqueue_overreset_styles" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_enqueue_overreset_styles', '0' ) ); ?> />
				<span style="margin-left:10px;">(load a CSS file with style attributes overriding known compatibility issues; check and report compatibility issues here.)</span>
			</td>
		</tr>
	</table>

	<h2>Options management</h2>
	<p><strong>Manage the Fastest Share Buttons for WordPress options in the database</strong><br>
	Checking the option below will remove the Fastest Share Buttons options from the database when uninstalling the plugin; else, they will stay there in case the plugin gets reinstalled later on.</p>

	<table class="form-table">
		<tr valign="top">
			<th scope="row">Delete options on uninstall:</th>
			<td>
				<input name="mofsb_delete_options_uninstall" type="checkbox" value="1" <?php checked( '1', get_option( 'mofsb_delete_options_uninstall', '0' ) ); ?> />
			</td>
		</tr>
	</table>

	<?php submit_button(); ?>

</form>
</div>
<?php } ?>
