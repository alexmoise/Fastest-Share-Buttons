<?php
/**
 * Plugin Name: Fastest Share Buttons
 * Plugin URI: https://moise.pro/fastest-share-buttons-for-wordpress/
 * GitHub Plugin URI: https://github.com/alexmoise/Fastest-Share-Buttons
 * Description: An extremely fast and mobile friendly social share plugin - no JS, no external API, with light SVG icons, cache compatible and highly customizable with 20+ options.
 * Version: 1.0.7
 * Author: Alex Moise
 * Author URI: https://moise.pro
 */

if ( ! defined( 'ABSPATH' ) ) { exit(0); }

// Adding admin options
include( plugin_dir_path( __FILE__ ) . 'mofsb-options.php' );

function mofsb_get_share_networks() {
	return array(
		'facebook' => array(
			'option_name'		=> 'mofsb_button_facebook',
			'button_class'		=> 'facebook',
			'icon'				=> 'icon-facebook-white.svg',
			'label'				=> 'Facebook',
			'opens_new_window'	=> true,
		),
		'twitter' => array(
			'option_name'		=> 'mofsb_button_twitter',
			'button_class'		=> 'twitter',
			'icon'				=> 'icon-twitter-white.svg',
			'label'				=> 'X (Twitter)',
			'opens_new_window'	=> true,
		),
		'pinterest' => array(
			'option_name'		=> 'mofsb_button_pinterest',
			'button_class'		=> 'pinterest',
			'icon'				=> 'icon-pinterest-white.svg',
			'label'				=> 'Pinterest',
			'opens_new_window'	=> true,
		),
		'tumblr' => array(
			'option_name'		=> 'mofsb_button_tumblr',
			'button_class'		=> 'tumblr',
			'icon'				=> 'icon-tumblr-white.svg',
			'label'				=> 'Tumblr',
			'opens_new_window'	=> true,
		),
		'email' => array(
			'option_name'		=> 'mofsb_button_email',
			'button_class'		=> 'email',
			'icon'				=> 'icon-email-white.svg',
			'label'				=> 'Email',
			'opens_new_window'	=> false,
		),
	);
}

function mofsb_count_enabled_networks() {
	$mofsb_enabled_networks = 0;

	foreach ( mofsb_get_share_networks() as $mofsb_network ) {
		if ( get_option( $mofsb_network['option_name'] ) ) {
			$mofsb_enabled_networks++;
		}
	}

	return $mofsb_enabled_networks;
}

// Enqueue the styles
add_action( 'wp_enqueue_scripts', 'mofsb_enqueue_styles', 99999 );
function mofsb_enqueue_styles() {
	$mofsb_styles_important = '';
	$mofsb_styles_div_overqualifier = '';

	if ( get_option( 'mofsb_enqueue_important_styles' ) ) {
		$mofsb_styles_important = '!important';
		wp_enqueue_style( 'mofsb-styles', plugins_url( 'mofsb-static-important.css', __FILE__ ) );
	} else {
		wp_enqueue_style( 'mofsb-styles', plugins_url( 'mofsb-static.css', __FILE__ ) );
	}

	if ( get_option( 'mofsb_enqueue_overqualify_styles' ) ) {
		$mofsb_styles_div_overqualifier = 'div';
	}

	$mofsb_button_size = max( 18, min( 128, absint( get_option( 'mofsb_style_button_size', '48' ) ) ) );
	$mofsb_icon_size = max( 1, min( 100, absint( get_option( 'mofsb_style_icon_size', '60' ) ) ) );
	$mofsb_shrink_width = max( 100, min( 800, absint( get_option( 'mofsb_style_shrink_width', '400' ) ) ) );
	$mofsb_shrink_amount = max( 10, min( 100, absint( get_option( 'mofsb_style_shrink_amount', '75' ) ) ) );
	$mofsb_padding_top = max( 0, min( 100, absint( get_option( 'mofsb_style_padding_top_static', '0' ) ) ) );
	$mofsb_padding_bottom = max( 0, min( 100, absint( get_option( 'mofsb_style_padding_bottom_static', '0' ) ) ) );
	$mofsb_floating_side = get_option( 'mofsb_style_floating_side', 'left' );
	$mofsb_floating_height = max( 1, min( 100, absint( get_option( 'mofsb_style_floating_height', '25' ) ) ) );
	$mofsb_floating_mobile = max( 100, min( 1024, absint( get_option( 'mofsb_style_floating_mobile', '600' ) ) ) );

	if ( 'right' !== $mofsb_floating_side ) {
		$mofsb_floating_side = 'left';
	}

	if ( get_option( 'mofsb_style_floating_stretch' ) ) {
		$mofsb_buttons_number = max( 1, mofsb_count_enabled_networks() );
		$mofsb_buttons_percent = 100 / $mofsb_buttons_number;
		$mofsb_floating_buttons_width = $mofsb_buttons_percent . '%';
		$mofsb_shrink_floating_buttons_width = $mofsb_floating_buttons_width;
	} else {
		$mofsb_floating_buttons_width = $mofsb_button_size . 'px';
		$mofsb_shrink_floating_buttons_width = ( $mofsb_button_size * ( $mofsb_shrink_amount * 0.01 ) ) . 'px';
	}

	$mofsb_dynamic_css = "
		@media (min-width: " . ( $mofsb_floating_mobile + 1 ) . "px) {
			" . $mofsb_styles_div_overqualifier . ".mofsb-wrapper-floating { position: fixed" . $mofsb_styles_important . "; top: " . $mofsb_floating_height . "%" . $mofsb_styles_important . "; " . $mofsb_floating_side . ": 0px" . $mofsb_styles_important . ";}
			" . $mofsb_styles_div_overqualifier . ".mofsb-wrapper-floating .mofsb-button {display: block" . $mofsb_styles_important . ";}
		}
		@media (max-width: " . $mofsb_floating_mobile . "px) {
			" . $mofsb_styles_div_overqualifier . ".mofsb-wrapper-floating { position: fixed" . $mofsb_styles_important . "; bottom: 0px" . $mofsb_styles_important . "; left: 0px" . $mofsb_styles_important . "; width: 100%" . $mofsb_styles_important . "; text-align: center" . $mofsb_styles_important . "; }
			" . $mofsb_styles_div_overqualifier . ".mofsb-wrapper-floating .mofsb-button {display: inline-block" . $mofsb_styles_important . ";}
		}
		@media (min-width: " . ( $mofsb_shrink_width + 1 ) . "px) {
			" . $mofsb_styles_div_overqualifier . ".mofsb-wrapper-static .mofsb-button { width: " . $mofsb_button_size . "px" . $mofsb_styles_important . "; height: " . $mofsb_button_size . "px" . $mofsb_styles_important . "; }
			" . $mofsb_styles_div_overqualifier . ".mofsb-wrapper-static .mofsb-button img { width: " . $mofsb_icon_size . "%" . $mofsb_styles_important . "; height: " . $mofsb_icon_size . "%" . $mofsb_styles_important . ";}
		}
		@media (max-width: " . $mofsb_shrink_width . "px) {
			" . $mofsb_styles_div_overqualifier . ".mofsb-wrapper-static .mofsb-button { width: " . ( $mofsb_button_size * ( $mofsb_shrink_amount * 0.01 ) ) . "px" . $mofsb_styles_important . "; height: " . ( $mofsb_button_size * ( $mofsb_shrink_amount * 0.01 ) ) . "px" . $mofsb_styles_important . "; }
			" . $mofsb_styles_div_overqualifier . ".mofsb-wrapper-static .mofsb-button img { width: " . ( $mofsb_icon_size * ( $mofsb_shrink_amount * 0.01 ) ) . "%" . $mofsb_styles_important . "; height: " . ( $mofsb_icon_size * ( $mofsb_shrink_amount * 0.01 ) ) . "%" . $mofsb_styles_important . ";}
		}
		.mofsb-wrapper-static { padding-bottom: " . $mofsb_padding_bottom . "px" . $mofsb_styles_important . "; padding-top: " . $mofsb_padding_top . "px" . $mofsb_styles_important . "; }
		@media (min-width: " . ( $mofsb_floating_mobile + 1 ) . "px) {
			" . $mofsb_styles_div_overqualifier . ".mofsb-wrapper-floating .mofsb-button { width: " . $mofsb_button_size . "px" . $mofsb_styles_important . "; height: " . $mofsb_button_size . "px" . $mofsb_styles_important . "; }
			" . $mofsb_styles_div_overqualifier . ".mofsb-wrapper-floating .mofsb-button img { width: " . $mofsb_icon_size . "%" . $mofsb_styles_important . "; height: " . $mofsb_icon_size . "%" . $mofsb_styles_important . ";}
		}
		@media (max-width: " . $mofsb_floating_mobile . "px) and (min-width: " . ( $mofsb_shrink_width + 1 ) . "px) {
			" . $mofsb_styles_div_overqualifier . ".mofsb-wrapper-floating .mofsb-button { width: " . $mofsb_floating_buttons_width . "" . $mofsb_styles_important . "; height: " . $mofsb_button_size . "px" . $mofsb_styles_important . "; }
			" . $mofsb_styles_div_overqualifier . ".mofsb-wrapper-floating .mofsb-button img { width: " . $mofsb_icon_size . "%" . $mofsb_styles_important . "; height: " . $mofsb_icon_size . "%" . $mofsb_styles_important . ";}
		}
		@media (max-width: " . $mofsb_shrink_width . "px) {
			" . $mofsb_styles_div_overqualifier . ".mofsb-wrapper-floating .mofsb-button { width: " . $mofsb_shrink_floating_buttons_width . "" . $mofsb_styles_important . "; height: " . ( $mofsb_button_size * ( $mofsb_shrink_amount * 0.01 ) ) . "px" . $mofsb_styles_important . "; }
			" . $mofsb_styles_div_overqualifier . ".mofsb-wrapper-floating .mofsb-button img { width: " . ( $mofsb_icon_size * ( $mofsb_shrink_amount * 0.01 ) ) . "%" . $mofsb_styles_important . "; height: " . ( $mofsb_icon_size * ( $mofsb_shrink_amount * 0.01 ) ) . "%" . $mofsb_styles_important . ";}
		}
	";

	wp_add_inline_style( 'mofsb-styles', $mofsb_dynamic_css );
}

// Enqueue the RESET styles
add_action( 'wp_enqueue_scripts', 'mofsb_enqueue_reset_styles', 99998 );
function mofsb_enqueue_reset_styles() {
	if ( get_option( 'mofsb_enqueue_overreset_styles' ) ) {
		wp_enqueue_style( 'mofsb-reset-styles', plugins_url( 'mofsb-reset.css', __FILE__ ) );
	}
}

function mofsb_normalize_share_text( $mofsb_text ) {
	$mofsb_text = wp_strip_all_tags( strip_shortcodes( $mofsb_text ) );
	$mofsb_text = preg_replace( '/\s+/', ' ', $mofsb_text );

	return trim( $mofsb_text );
}

// Extract some post details
function mofsb_post_details( $post_id ) {
	$mofsb_content = mofsb_normalize_share_text( get_post_field( 'post_content', $post_id ) );
	$mofsb_summary = trim( wp_trim_words( $mofsb_content, 18, '...' ) );
	$mofsb_title = get_the_title( $post_id );

	if ( '' === $mofsb_summary ) {
		$mofsb_summary = $mofsb_title;
	}

	return array(
		'id'				=> $post_id,
		'site_name'			=> get_bloginfo( 'name' ),
		'title'				=> $mofsb_title,
		'link'				=> get_permalink( $post_id ),
		'featured_image'	=> wp_get_attachment_url( get_post_thumbnail_id( $post_id ) ),
		'summary'			=> $mofsb_summary,
	);
}

function mofsb_build_query( $mofsb_parameters ) {
	$mofsb_filtered_parameters = array();

	foreach ( $mofsb_parameters as $mofsb_key => $mofsb_value ) {
		if ( '' === $mofsb_value || null === $mofsb_value ) {
			continue;
		}

		$mofsb_filtered_parameters[ $mofsb_key ] = $mofsb_value;
	}

	if ( empty( $mofsb_filtered_parameters ) ) {
		return '';
	}

	if ( defined( 'PHP_QUERY_RFC3986' ) ) {
		return http_build_query( $mofsb_filtered_parameters, '', '&', PHP_QUERY_RFC3986 );
	}

	return http_build_query( $mofsb_filtered_parameters, '', '&' );
}

function mofsb_get_share_url( $mofsb_network_name, $mofsb_post_details ) {
	switch ( $mofsb_network_name ) {
		case 'facebook':
			return 'https://www.facebook.com/sharer/sharer.php?' . mofsb_build_query( array(
				'u' => $mofsb_post_details['link'],
			) );

		case 'twitter':
			$mofsb_query_arguments = array(
				'url'	=> $mofsb_post_details['link'],
				'text'	=> $mofsb_post_details['summary'],
			);

			$mofsb_x_handle = mofsb_sanitize_x_handle( get_option( 'mofsb_x_handle', '' ) );

			if ( '' !== $mofsb_x_handle ) {
				$mofsb_query_arguments['via'] = $mofsb_x_handle;
			}

			return 'https://twitter.com/intent/tweet?' . mofsb_build_query( $mofsb_query_arguments );

		case 'pinterest':
			$mofsb_query_arguments = array(
				'url'			=> $mofsb_post_details['link'],
				'description'	=> $mofsb_post_details['title'],
			);

			if ( ! empty( $mofsb_post_details['featured_image'] ) ) {
				$mofsb_query_arguments['media'] = $mofsb_post_details['featured_image'];
			}

			return 'https://pinterest.com/pin/create/button/?' . mofsb_build_query( $mofsb_query_arguments );

		case 'tumblr':
			$mofsb_query_arguments = array(
				'canonicalUrl'	=> $mofsb_post_details['link'],
				'title'			=> $mofsb_post_details['title'],
				'caption'		=> $mofsb_post_details['summary'],
			);

			if ( ! empty( $mofsb_post_details['featured_image'] ) ) {
				$mofsb_query_arguments['posttype'] = 'photo';
				$mofsb_query_arguments['content'] = $mofsb_post_details['featured_image'];
			}

			return 'https://www.tumblr.com/widgets/share/tool?' . mofsb_build_query( $mofsb_query_arguments );

		case 'email':
			return 'mailto:?' . mofsb_build_query( array(
				'subject'	=> $mofsb_post_details['title'],
				'body'		=> 'Please check this out: ' . $mofsb_post_details['link'],
			) );
	}

	return '';
}

function mofsb_get_share_button_html( $mofsb_network_name, $mofsb_network, $mofsb_post_details ) {
	$mofsb_share_url = mofsb_get_share_url( $mofsb_network_name, $mofsb_post_details );

	if ( '' === $mofsb_share_url ) {
		return '';
	}

	if ( ! empty( $mofsb_network['opens_new_window'] ) ) {
		$mofsb_attributes = ' target="_blank" rel="nofollow noopener noreferrer" onClick="window.open(this.href,\'_blank\', \'width=700, height=400\');return false;"';
	} else {
		$mofsb_attributes = ' rel="nofollow"';
	}

	return '<a href="' . esc_url( $mofsb_share_url ) . '" class="mofsb-button mofsb-button-' . esc_attr( $mofsb_network['button_class'] ) . '"' . $mofsb_attributes . '><img src="' . esc_url( plugin_dir_url( __FILE__ ) . 'icon/' . $mofsb_network['icon'] ) . '" alt="' . esc_attr( $mofsb_network['label'] ) . '"></a>';
}

// Define the HTML code
function mofsb_html( $mofsb_display_type ) { //only takes "static", else displays floating
	$mofsb_post_details_for_html = mofsb_post_details( get_the_ID() );

	if ( 'static' === $mofsb_display_type ) {
		$mofsb_html_code = '<div class="mofsb-wrapper mofsb-wrapper-reset mofsb-wrapper-static">';
	} else {
		$mofsb_html_code = '<div class="mofsb-wrapper mofsb-wrapper-reset mofsb-wrapper-floating">';
	}

	foreach ( mofsb_get_share_networks() as $mofsb_network_name => $mofsb_network ) {
		if ( ! get_option( $mofsb_network['option_name'] ) ) {
			continue;
		}

		$mofsb_html_code .= mofsb_get_share_button_html( $mofsb_network_name, $mofsb_network, $mofsb_post_details_for_html );
	}

	$mofsb_html_code .= '</div>';

	return $mofsb_html_code;
}

// Inserting the mosfb buttons STATIC code in the $content
add_action( 'the_content', 'mofsb_insert_in_content' );
function mofsb_insert_in_content( $content ) {
	$mosfb_static_code = mofsb_html( 'static' );

	if ( get_option( 'mofsb_display_before_content_posts' ) and is_single() ) { $content = $mosfb_static_code . $content; }
	if ( get_option( 'mofsb_display_before_content_pages' ) and is_page() ) { $content = $mosfb_static_code . $content; }
	if ( get_option( 'mofsb_display_before_content_archives' ) and is_archive() ) { $content = $mosfb_static_code . $content; }
	if ( get_option( 'mofsb_display_before_content_front' ) and is_front_page() ) { $content = $mosfb_static_code . $content; }
	if ( get_option( 'mofsb_display_before_content_blog' ) and is_home() ) { $content = $mosfb_static_code . $content; }

	if ( get_option( 'mofsb_display_after_content_posts' ) and is_single() ) { $content = $content . $mosfb_static_code; }
	if ( get_option( 'mofsb_display_after_content_pages' ) and is_page() ) { $content = $content . $mosfb_static_code; }
	if ( get_option( 'mofsb_display_after_content_archives' ) and is_archive() ) { $content = $content . $mosfb_static_code; }
	if ( get_option( 'mofsb_display_after_content_front' ) and is_front_page() ) { $content = $content . $mosfb_static_code; }
	if ( get_option( 'mofsb_display_after_content_blog' ) and is_home() ) { $content = $content . $mosfb_static_code; }

	return $content;
}

// Inserting the mosfb buttons FLOATING code in the WP_FOOTER
add_action( 'wp_footer', 'mofsb_insert_floating' );
function mofsb_insert_floating() {
	if ( get_option( 'mofsb_display_floating_posts' ) and is_single() ) { echo mofsb_html( 'floating' ); }
	if ( get_option( 'mofsb_display_floating_pages' ) and is_page() ) { echo mofsb_html( 'floating' ); }
	if ( get_option( 'mofsb_display_floating_archives' ) and is_archive() ) { echo mofsb_html( 'floating' ); }
	if ( get_option( 'mofsb_display_floating_front' ) and is_front_page() ) { echo mofsb_html( 'floating' ); }
	if ( get_option( 'mofsb_display_floating_blog' ) and is_home() ) { echo mofsb_html( 'floating' ); }
}

// Inserting the shortcode
add_shortcode( 'mofsb', 'mofsb_insert_shortcode' );
function mofsb_insert_shortcode() {
	if ( ( is_single() or is_page() ) and ( ! is_front_page() or ! is_archive() or ! is_home() ) ) {
		$mosfb_shortcode = mofsb_html( 'static' );
		return $mosfb_shortcode;
	}
}
?>
