<?php
/**
 * Plugin Name: Fastest Share Buttons
 * Plugin URI: https://moise.pro/fastest-share-buttons-for-wordpress/
 * GitHub Plugin URI: https://github.com/alexmoise/Fastest-Share-Buttons
 * Description: An extremely fast and mobile friendly social share plugin - no JS, no external API, with light SVG icons, cache compatible and highly customizable with 20+ options.
 * Version: 1.0.6
 * Author: Alex Moise
 * Author URI: https://moise.pro
 */

if ( ! defined( 'ABSPATH' ) ) {	exit(0);}

// Adding admin options
include( plugin_dir_path( __FILE__ ) . 'mofsb-options.php' );

// Enqueue the styles
add_action( 'wp_enqueue_scripts', 'mofsb_enqueue_styles', 99999 );
function mofsb_enqueue_styles() {
	// Are !important styles requested?
	if ( get_option( 'mofsb_enqueue_important_styles' ) ) {
		// set the !important variable ...
		$mofsb_styles_important = '!important';
		// ... and enqueue the !important static styles
		wp_enqueue_style( 'mofsb-styles', plugins_url( 'mofsb-static-important.css', __FILE__ ) );
	} else {
		// Just enqueue the static styles
		wp_enqueue_style( 'mofsb-styles', plugins_url( 'mofsb-static.css', __FILE__ ) );
	}

	// Set element-specific selectors for overqualifying the styles
	if ( get_option( 'mofsb_enqueue_overqualify_styles' ) ) {
		$mofsb_styles_div_overqualifier = 'div';
	}

	// Bottom floating stretch or square buttons?
	if ( get_option( 'mofsb_style_floating_stretch' ) ) {
		// counting buttons number (for stretched bottom bar)
		$mofsb_buttons_number = (get_option( 'mofsb_button_facebook' ))+(get_option( 'mofsb_button_twitter' ))+(get_option( 'mofsb_button_pinterest' ))+(get_option( 'mofsb_button_google' ))+(get_option( 'mofsb_button_tumblr' ))+(get_option( 'mofsb_button_email' ));
		// calculating each button width as percent (for stretched bottom bar)
		$mofsb_buttons_percent = 100 / $mofsb_buttons_number;
		$mofsb_floating_buttons_width = $mofsb_buttons_percent . '%';
		$mofsb_shrink_floating_buttons_width = $mofsb_floating_buttons_width;
		} else {
		$mofsb_floating_buttons_width = get_option( 'mofsb_style_button_size' ) . 'px';
		$mofsb_shrink_floating_buttons_width = get_option( 'mofsb_style_button_size' ) * ( get_option('mofsb_style_shrink_amount')*0.01 ) . 'px';
	}

	// Adding dynamic styles
	$mofsb_dynamic_css = "
		@media (min-width: ".(get_option( 'mofsb_style_floating_mobile' )+1)."px) {
			".$mofsb_styles_div_overqualifier.".mofsb-wrapper-floating { position: fixed".$mofsb_styles_important."; top: ".get_option( 'mofsb_style_floating_height' )."%".$mofsb_styles_important."; ".get_option( 'mofsb_style_floating_side' ).": 0px".$mofsb_styles_important.";}
			".$mofsb_styles_div_overqualifier.".mofsb-wrapper-floating .mofsb-button {display: block".$mofsb_styles_important.";}
		}
		@media (max-width: ".(get_option( 'mofsb_style_floating_mobile' ))."px) {
			".$mofsb_styles_div_overqualifier.".mofsb-wrapper-floating { position: fixed".$mofsb_styles_important."; bottom: 0px".$mofsb_styles_important."; left: 0px".$mofsb_styles_important."; width: 100%".$mofsb_styles_important."; text-align: center".$mofsb_styles_important."; }
			".$mofsb_styles_div_overqualifier.".mofsb-wrapper-floating .mofsb-button {display: inline-block".$mofsb_styles_important.";}
		}
		@media (min-width: ".(get_option( 'mofsb_style_shrink_width' )+1)."px) {
			".$mofsb_styles_div_overqualifier.".mofsb-wrapper-static .mofsb-button { width: ".get_option( 'mofsb_style_button_size' )."px".$mofsb_styles_important."; height: ".get_option( 'mofsb_style_button_size' )."px".$mofsb_styles_important."; }
			".$mofsb_styles_div_overqualifier.".mofsb-wrapper-static .mofsb-button img { width: ".get_option( 'mofsb_style_icon_size' )."%".$mofsb_styles_important."; height: ".get_option( 'mofsb_style_icon_size' )."%".$mofsb_styles_important.";}
		}
		@media (max-width: ".(get_option( 'mofsb_style_shrink_width' ))."px) {
			".$mofsb_styles_div_overqualifier.".mofsb-wrapper-static .mofsb-button { width: ".(get_option( 'mofsb_style_button_size' )*( get_option('mofsb_style_shrink_amount')*0.01 ))."px".$mofsb_styles_important."; height: ".(get_option( 'mofsb_style_button_size' )*( get_option('mofsb_style_shrink_amount')*0.01 ))."px".$mofsb_styles_important."; }
			".$mofsb_styles_div_overqualifier.".mofsb-wrapper-static .mofsb-button img { width: ".(get_option( 'mofsb_style_icon_size' )*( get_option('mofsb_style_shrink_amount')*0.01 ))."%".$mofsb_styles_important."; height: ".(get_option( 'mofsb_style_icon_size' )*( get_option('mofsb_style_shrink_amount')*0.01 ))."%".$mofsb_styles_important.";}
		}
		.mofsb-wrapper-static { padding-bottom: ".(get_option( 'mofsb_style_padding_bottom_static' ))."px".$mofsb_styles_important."; padding-top: ".(get_option( 'mofsb_style_padding_top_static' ))."px".$mofsb_styles_important."; }
		@media (min-width: ".(get_option( 'mofsb_style_floating_mobile' )+1)."px) {
			".$mofsb_styles_div_overqualifier.".mofsb-wrapper-floating .mofsb-button { width: ".get_option( 'mofsb_style_button_size' )."px".$mofsb_styles_important."; height: ".get_option( 'mofsb_style_button_size' )."px".$mofsb_styles_important."; }
			".$mofsb_styles_div_overqualifier.".mofsb-wrapper-floating .mofsb-button img { width: ".get_option( 'mofsb_style_icon_size' )."%".$mofsb_styles_important."; height: ".get_option( 'mofsb_style_icon_size' )."%".$mofsb_styles_important.";}
		}
		@media (max-width: ".get_option( 'mofsb_style_floating_mobile' )."px) and (min-width: ".(get_option( 'mofsb_style_shrink_width' )+1)."px) {
			".$mofsb_styles_div_overqualifier.".mofsb-wrapper-floating .mofsb-button { width: ".$mofsb_floating_buttons_width."".$mofsb_styles_important."; height: ".(get_option( 'mofsb_style_button_size' ))."px".$mofsb_styles_important."; }
			".$mofsb_styles_div_overqualifier.".mofsb-wrapper-floating .mofsb-button img { width: ".get_option( 'mofsb_style_icon_size' )."%".$mofsb_styles_important."; height: ".get_option( 'mofsb_style_icon_size' )."%".$mofsb_styles_important.";}
		}
		@media (max-width: ".(get_option( 'mofsb_style_shrink_width' ))."px) {
			".$mofsb_styles_div_overqualifier.".mofsb-wrapper-floating .mofsb-button { width: ".$mofsb_shrink_floating_buttons_width."".$mofsb_styles_important."; height: ".(get_option( 'mofsb_style_button_size' )*( get_option('mofsb_style_shrink_amount')*0.01 ))."px".$mofsb_styles_important."; }
			".$mofsb_styles_div_overqualifier.".mofsb-wrapper-floating .mofsb-button img { width: ".(get_option( 'mofsb_style_icon_size' )*( get_option('mofsb_style_shrink_amount')*0.01 ))."%".$mofsb_styles_important."; height: ".(get_option( 'mofsb_style_icon_size' )*( get_option('mofsb_style_shrink_amount')*0.01 ))."%".$mofsb_styles_important.";}
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

// Extract some post details
function mofsb_post_details($post_id) {
	$mofsb_post_array['id'] 				= get_the_ID();
	$mofsb_post_array['blog_name']			= get_bloginfo( 'name' );
	$mofsb_post_array['title'] 				= get_the_title( $post_id );
	$mofsb_post_array['link'] 				= get_permalink();
	$mofsb_post_array['feat_image'] 		= wp_get_attachment_url( get_post_thumbnail_id($post_id) );
	$mofsb_post_array['http_feat_image']	= "http://" . parse_url ($mofsb_post_array['feat_image'], PHP_URL_HOST) . parse_url ($mofsb_post_array['feat_image'], PHP_URL_PATH);
	if (strlen(get_the_content( $post_id )) >=80) {$mofsb_post_len = 80;} else {$mofsb_post_len = strlen(get_the_content( $post_id ));}
	$mofsb_post_array['content_80'] 		= str_replace("\r\n", " ", substr( wp_strip_all_tags( get_the_content( $post_id ) ), 0, strpos(get_the_content( $post_id ), ' ', $mofsb_post_len)) );
	return $mofsb_post_array;
}

// Define the HTML code
function mofsb_html ( $mofsb_display_type ) { //only takes "static", else displays floating
	//loading the post details extracting function
	$mofsb_post_details_for_html = mofsb_post_details(get_the_ID());
	// unset it first
	unset($mofsb_html_code);
	// START the HTML code
	if ($mofsb_display_type == 'static') {
		$mofsb_html_code = "<div class=\"mofsb-wrapper mofsb-wrapper-reset mofsb-wrapper-static\">";
	} else {
		$mofsb_html_code = "<div class=\"mofsb-wrapper mofsb-wrapper-reset mofsb-wrapper-floating\">";
	}
	// ADD Facebook button
	if ( get_option( 'mofsb_button_facebook' ) ) {
		$share_link_facebook = "<a href=\"http://www.facebook.com/sharer.php?u=".$mofsb_post_details_for_html['link']."\" class=\"mofsb-button mofsb-button-facebook\" rel=\"nofollow\" target=\"_blank\" onClick=\"window.open(this.href,'_blank', 'width=700, height=400');return false;\"><img src=\"". plugin_dir_url( __FILE__ ) . 'icon/icon-facebook-white.svg'."\"></a>";
		$mofsb_html_code .= $share_link_facebook;
	}
	// ADD Twitter button
	if ( get_option( 'mofsb_button_twitter' ) ) {
		$share_link_twitter = "<a href=\"https://twitter.com/intent/tweet?url=".$mofsb_post_details_for_html['link']."&text=".urlencode($mofsb_post_details_for_html['content_80'])." ... more:&via=".$mofsb_post_details_for_html['blog_name']."\" class=\"mofsb-button mofsb-button-twitter\" rel=\"nofollow\" target=\"_blank\" onClick=\"window.open(this.href,'_blank', 'width=700, height=400');return false;\"><img src=\"". plugin_dir_url( __FILE__ ) . 'icon/icon-twitter-white.svg'."\"></a>";
		$mofsb_html_code .= $share_link_twitter;
	}
	// ADD Pinterest button
	if ( get_option( 'mofsb_button_pinterest' ) ) {
		$share_link_pinterest = "<a href=\"http://pinterest.com/pin/create/bookmarklet/?is_video=false&url=".$mofsb_post_details_for_html['link']."&media=".$mofsb_post_details_for_html['feat_image']."&description=".$mofsb_post_details_for_html['title']."\" class=\"mofsb-button mofsb-button-pinterest\" rel=\"nofollow\" target=\"_blank\" onClick=\"window.open(this.href,'_blank', 'width=700, height=400');return false;\"><img src=\"". plugin_dir_url( __FILE__ ) . 'icon/icon-pinterest-white.svg'."\"></a>";
		$mofsb_html_code .= $share_link_pinterest;
	}
	// ADD Google button
	if ( get_option( 'mofsb_button_google' ) ) {
		$share_link_google = "<a href=\"https://plus.google.com/share?url=".$mofsb_post_details_for_html['link']."\" class=\"mofsb-button mofsb-button-google\" rel=\"nofollow\" target=\"_blank\" onClick=\"window.open(this.href,'_blank', 'width=700, height=400');return false;\"><img src=\"". plugin_dir_url( __FILE__ ) . 'icon/icon-google-white.svg'."\"></a>";
		$mofsb_html_code .= $share_link_google;
	}
	// ADD Tumblr button
	if ( get_option( 'mofsb_button_tumblr' ) ) {
		$share_link_tumblr = "<a href=\"https://www.tumblr.com/widgets/share/tool?posttype=photo&content=".urlencode($mofsb_post_details_for_html['http_feat_image'])."&show-via=".urlencode($mofsb_post_details_for_html['link'])."&title=".urlencode($mofsb_post_details_for_html['title'])."&caption=".urlencode($mofsb_post_details_for_html['title'])."&canonicalUrl=".urlencode($mofsb_post_details_for_html['link'])."\" class=\"mofsb-button mofsb-button-tumblr\" rel=\"nofollow\" target=\"_blank\" onClick=\"window.open(this.href,'_blank', 'width=700, height=400');return false;\"><img src=\"". plugin_dir_url( __FILE__ ) . 'icon/icon-tumblr-white.svg'."\"></a>";
		$mofsb_html_code .= $share_link_tumblr;
	}
	// ADD Email button
	if ( get_option( 'mofsb_button_email' ) ) {
		$share_link_email = "<a href=\"mailto:?subject=".$mofsb_post_details_for_html['title']."&body=Please check this out: ".$mofsb_post_details_for_html['link']."\" class=\"mofsb-button mofsb-button-email\" rel=\"nofollow\" ><img src=\"". plugin_dir_url( __FILE__ ) . 'icon/icon-email-white.svg'."\"></a>";
		$mofsb_html_code .= $share_link_email;
	}
	// FINISH the HTML code
	$mofsb_html_code .= "</div>";
	return $mofsb_html_code;
}

// Inserting the mosfb buttons STATIC code in the $content
add_action( 'the_content', 'mofsb_insert_in_content' );
function mofsb_insert_in_content( $content ) {
	// Getting the HTML code in the variable
	$mosfb_static_code = mofsb_html('static');
	//output the mofsb HTML code - BEFORE tehe content
	if ( get_option( 'mofsb_display_before_content_posts' )		and is_single()		) { $content =  $mosfb_static_code . $content; }
	if ( get_option( 'mofsb_display_before_content_pages' )		and is_page()		) { $content =  $mosfb_static_code . $content; }
	if ( get_option( 'mofsb_display_before_content_archives' )	and is_archive()	) { $content =  $mosfb_static_code . $content; }
	if ( get_option( 'mofsb_display_before_content_front' )		and is_front_page()	) { $content =  $mosfb_static_code . $content; }
	if ( get_option( 'mofsb_display_before_content_blog' )		and is_home()		) { $content =  $mosfb_static_code . $content; }
	// output the mofsb HTML code - AFTER tehe content
	if ( get_option( 'mofsb_display_after_content_posts' )		and is_single()		) { $content =  $content . $mosfb_static_code; }
	if ( get_option( 'mofsb_display_after_content_pages' )		and is_page()		) { $content =  $content . $mosfb_static_code; }
	if ( get_option( 'mofsb_display_after_content_archives' )	and is_archive()	) { $content =  $content . $mosfb_static_code; }
	if ( get_option( 'mofsb_display_after_content_front' )		and is_front_page()	) { $content =  $content . $mosfb_static_code; }
	if ( get_option( 'mofsb_display_after_content_blog' )		and is_home()		) { $content =  $content . $mosfb_static_code; }
	// returning ...
	return $content;
}

// Inserting the mosfb buttons FLOATING code in the WP_FOOTER
add_action( 'wp_footer', 'mofsb_insert_floating' );
function mofsb_insert_floating() {
	//output the mofsb HTML code
	if ( get_option( 'mofsb_display_floating_posts' ) 		and is_single()		) { echo mofsb_html('floating'); }
	if ( get_option( 'mofsb_display_floating_pages' ) 		and is_page()		) { echo mofsb_html('floating'); }
	if ( get_option( 'mofsb_display_floating_archives' ) 	and is_archive()	) { echo mofsb_html('floating'); }
	if ( get_option( 'mofsb_display_floating_front' ) 		and is_front_page()	) { echo mofsb_html('floating'); }
	if ( get_option( 'mofsb_display_floating_blog' ) 		and is_home()		) { echo mofsb_html('floating'); }
}

// Inserting the shortcode
add_shortcode( 'mofsb', 'mofsb_insert_shortcode' );
function mofsb_insert_shortcode(){
	if ( ( is_single() or is_page() ) and ( !is_front_page() or !is_archive() or !is_home()  ) ){
		$mosfb_shortcode = mofsb_html('static');
		return $mosfb_shortcode;
	}
}
?>