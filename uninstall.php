<?php
/**
 * Uninstall of the Fastest Share Buttons for WordPress.
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) 			{exit(0);}
if ( ! defined('WP_UNINSTALL_PLUGIN')) 	{die;}

// checking if removal is requested
if ( get_option( 'mofsb_delete_options_uninstall' ) ) {

//defining options to remove as variables ..
$mofsb_option_remove_display_first_settings_notice		= 'mofsb_display_first_settings_notice';

$mofsb_option_remove_display_floating_posts 			= 'mofsb_display_floating_posts';
$mofsb_option_remove_display_floating_pages 			= 'mofsb_display_floating_pages';
$mofsb_option_remove_display_floating_archives 			= 'mofsb_display_floating_archives';
$mofsb_option_remove_display_floating_front 			= 'mofsb_display_floating_front';
$mofsb_option_remove_display_floating_blog 				= 'mofsb_display_floating_blog';

$mofsb_option_remove_display_before_content_posts 		= 'mofsb_display_before_content_posts';
$mofsb_option_remove_display_before_content_pages 		= 'mofsb_display_before_content_pages';
$mofsb_option_remove_display_before_content_archives 	= 'mofsb_display_before_content_archives';
$mofsb_option_remove_display_before_content_front 		= 'mofsb_display_before_content_front';
$mofsb_option_remove_display_before_content_blog 		= 'mofsb_display_before_content_blog';

$mofsb_option_remove_display_after_content_posts 		= 'mofsb_display_after_content_posts';
$mofsb_option_remove_display_after_content_pages 		= 'mofsb_display_after_content_pages';
$mofsb_option_remove_display_after_content_archives 	= 'mofsb_display_after_content_archives';
$mofsb_option_remove_display_after_content_front 		= 'mofsb_display_after_content_front';
$mofsb_option_remove_display_after_content_blog 		= 'mofsb_display_after_content_blog';

$mofsb_option_remove_button_facebook 					= 'mofsb_button_facebook';
$mofsb_option_remove_button_twitter 					= 'mofsb_button_twitter';
$mofsb_option_remove_button_pinterest 					= 'mofsb_button_pinterest';
$mofsb_option_remove_button_google 						= 'mofsb_button_google';
$mofsb_option_remove_button_tumblr 						= 'mofsb_button_tumblr';
$mofsb_option_remove_button_email 						= 'mofsb_button_email';

$mofsb_option_remove_style_button_size 					= 'mofsb_style_button_size';
$mofsb_option_remove_style_icon_size 					= 'mofsb_style_icon_size';

$mofsb_option_remove_style_shrink_width 				= 'mofsb_style_shrink_width';
$mofsb_option_remove_style_shrink_amount 				= 'mofsb_style_shrink_amount';

$mofsb_option_remove_tyle_padding_top_static 			= 'mofsb_style_padding_top_static';
$mofsb_option_remove_style_padding_bottom_static 		= 'mofsb_style_padding_bottom_static';

$mofsb_option_remove_style_floating_side 				= 'mofsb_style_floating_side';
$mofsb_option_remove_style_floating_height 				= 'mofsb_style_floating_height';
$mofsb_option_remove_style_floating_mobile 				= 'mofsb_style_floating_mobile';

$mofsb_option_remove_style_floating_stretch 			= 'mofsb_style_floating_stretch';

$mofsb_option_remove_enqueue_important_styles 			= 'mofsb_enqueue_important_styles';
$mofsb_option_remove_enqueue_overqualify_styles 		= 'mofsb_enqueue_overqualify_styles';
$mofsb_option_remove_enqueue_overreset_styles 			= 'mofsb_enqueue_overreset_styles';

$mofsb_option_remove_delete_options_uninstall 			= 'mofsb_delete_options_uninstall';

// removing the options ..
delete_option(	$mofsb_option_remove_display_first_settings_notice 	);

delete_option( $mofsb_option_remove_display_floating_posts );
delete_option( $mofsb_option_remove_display_floating_pages );
delete_option( $mofsb_option_remove_display_floating_archives );
delete_option( $mofsb_option_remove_display_floating_front );
delete_option( $mofsb_option_remove_display_floating_blog );

delete_option( $mofsb_option_remove_display_before_content_posts );
delete_option( $mofsb_option_remove_display_before_content_pages );
delete_option( $mofsb_option_remove_display_before_content_archives );
delete_option( $mofsb_option_remove_display_before_content_front );
delete_option( $mofsb_option_remove_display_before_content_blog );

delete_option( $mofsb_option_remove_display_after_content_posts );
delete_option( $mofsb_option_remove_display_after_content_pages );
delete_option( $mofsb_option_remove_display_after_content_archives );
delete_option( $mofsb_option_remove_display_after_content_front );
delete_option( $mofsb_option_remove_display_after_content_blog );

delete_option( $mofsb_option_remove_button_facebook );
delete_option( $mofsb_option_remove_button_twitter );
delete_option( $mofsb_option_remove_button_pinterest );
delete_option( $mofsb_option_remove_button_google );
delete_option( $mofsb_option_remove_button_tumblr );
delete_option( $mofsb_option_remove_button_email );

delete_option( $mofsb_option_remove_style_button_size );
delete_option( $mofsb_option_remove_style_icon_size );

delete_option( $mofsb_option_remove_style_shrink_width );
delete_option( $mofsb_option_remove_style_shrink_amount );

delete_option( $mofsb_option_remove_tyle_padding_top_static );
delete_option( $mofsb_option_remove_style_padding_bottom_static );

delete_option( $mofsb_option_remove_style_floating_side );
delete_option( $mofsb_option_remove_style_floating_height );
delete_option( $mofsb_option_remove_style_floating_mobile );

delete_option( $mofsb_option_remove_style_floating_stretch );

delete_option( $mofsb_option_remove_enqueue_important_styles  );
delete_option( $mofsb_option_remove_enqueue_overqualify_styles  );
delete_option( $mofsb_option_remove_enqueue_overreset_styles  );

delete_option( $mofsb_option_remove_delete_options_uninstall );
}
?>