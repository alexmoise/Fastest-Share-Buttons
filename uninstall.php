<?php
/**
 * Uninstall of the Fastest Share Buttons for WordPress.
 * Version: 1.0.7
 */

if ( ! defined( 'ABSPATH' ) ) { exit(0); }
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { die; }

if ( get_option( 'mofsb_delete_options_uninstall' ) ) {
	$mofsb_options_to_remove = array(
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
		'mofsb_button_google',
		'mofsb_button_tumblr',
		'mofsb_button_email',
		'mofsb_x_handle',
		'mofsb_style_button_size',
		'mofsb_style_icon_size',
		'mofsb_style_shrink_width',
		'mofsb_style_shrink_amount',
		'mofsb_style_padding_top_static',
		'mofsb_style_padding_bottom_static',
		'mofsb_style_floating_side',
		'mofsb_style_floating_height',
		'mofsb_style_floating_mobile',
		'mofsb_style_floating_stretch',
		'mofsb_enqueue_important_styles',
		'mofsb_enqueue_overqualify_styles',
		'mofsb_enqueue_overreset_styles',
		'mofsb_delete_options_uninstall',
	);

	foreach ( $mofsb_options_to_remove as $mofsb_option_to_remove ) {
		delete_option( $mofsb_option_to_remove );
	}
}
?>
