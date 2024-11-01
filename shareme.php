<?php
/**
 * Plugin Name:       ShareMe Simple Social Share Plugin
 * Plugin URI:        https://wordpress.org/plugins/shareme
 * Description:       ShareMe Plugin for Social Share, Grow your followers, increase your social shares!
 * Version:           1.2
 * Author:            Shipon Karmakar
 * Author URI:        https://shiponkarmakar.com
 * Text Domain:       shareme
 * Domain Path:       /languages/
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * 
 * @package           ShareMe
 * @author            Shipon Karmakar
 * @copyright         2023 ShiponKarmakar
 * @license           GPL-2.0-or-later
 */

 // If this file is called firectily, abort!!
defined( 'ABSPATH' ) or ( 'Hey, What are you doing here? you silly human!' );


define( 'SHAREME_URL', trailingslashit( plugins_url('/', __FILE__) ) );
define( 'SHAREME_PATH', trailingslashit( plugin_dir_path(__FILE__) ) );


// i18n Text Domain
if ( ! function_exists( 'shareme_i18n' ) ){
	function shareme_i18n() {
		load_plugin_textdomain( 'eladdon', false, dirname( plugin_basename( __FILE__ ) ). '/languages' );
	}
	add_action( 'init', 'shareme_i18n' );
}


/**
 * Scripts and styles
*/
if ( ! function_exists( 'shareme_scripts_styles' ) ){
	function shareme_scripts_styles() {
		wp_register_style( 'shareme-style', SHAREME_URL . 'assets/css/style.css' );
	
		wp_enqueue_style('shareme-style');
	}
	add_action( 'wp_enqueue_scripts', 'shareme_scripts_styles' );
}

// DashIcon Load
if ( ! function_exists( 'shareme_load_dashicons' ) ){
	function shareme_load_dashicons(){
		wp_enqueue_style('dashicons');
	}
	add_action('wp_enqueue_scripts', 'shareme_load_dashicons');
}

// Share Button
if ( ! function_exists( 'shareme_social_sharing_buttons' ) ){
	function shareme_social_sharing_buttons( $content ) {
		global $post;
		if( is_single() ){
		
			// Get current page URL 
			$sharemeURL = urlencode( get_permalink() );
	 
			// Get current page title
			$sharemeTitle = htmlspecialchars( urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8' );
			$sharemeTitle = str_replace( ' ', '%20', get_the_title());
			
			// Get Post Thumbnail for pinterest
			$sharemeThumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
	 
			// Construct sharing URL without using any script
			$twitterURL = 'https://twitter.com/intent/tweet?text='.$sharemeTitle.'&amp;url='.$sharemeURL.'&amp;via=shareme';
			$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$sharemeURL;
			$linkedInURL = 'https://www.linkedin.com/shareArticle?mini=true&url='.$sharemeURL.'&amp;title='.$sharemeTitle;
			$pinterestURL = 'https://pinterest.com/pin/create/button/?url='.$sharemeURL.'&amp;media='.$sharemeThumbnail[0].'&amp;description='.$sharemeTitle;
			$whatsappURL = 'https://api.whatsapp.com/send?text='.$sharemeTitle."  ".$sharemeURL;
			$email = 'mailto:?subject=' . $sharemeTitle . '&body=Check out this site Link: '. $sharemeURL;

	

			// Add sharing button at the end of page/page content
			$content .= '<div class="shareme-social-icon">';
	
			$content .= '<h5>'. esc_html__( 'SHARE NOW', 'shareme' ) .'</h5>';
	
			$content .= '<a title="Facebook" class="shareme-link shareme-facebook" href="'. esc_url( $facebookURL ) .'" target="_blank" rel="nofollow"><span class="dashicons dashicons-facebook-alt"></span></a>';
	
			$content .= '<a title="Twitter" class="shareme-link shareme-twitter" href="'. esc_url( $twitterURL ) .'" target="_blank" rel="nofollow"><span class="dashicons dashicons-twitter"></span></a>';
	
			$content .= '<a title="Linkedin" class="shareme-link shareme-linkedin" href="'. esc_url( $linkedInURL ) .'" target="_blank" rel="nofollow"><span class="dashicons dashicons-linkedin"></span></a>';
	
			$content .= '<a title="Pinterest" class="shareme-link shareme-pinterest" href="'. esc_url( $pinterestURL ) .'" data-pin-custom="true" target="_blank" rel="nofollow"><span class="dashicons dashicons-pinterest"></span></a>';

			$content .= '<a title="WhatsApp" class="shareme-link shareme-whatsapp" href="'. esc_url( $whatsappURL ) .'" data-pin-custom="true" target="_blank" rel="nofollow"><span class="dashicons dashicons-whatsapp"></span></a>';

			$content .= '<a title="Email" class="shareme-link shareme-email" href="'. esc_url( $email ) .'" data-pin-custom="true" target="_blank" rel="nofollow"><span class="dashicons dashicons-email-alt"></span></a>';
	
			$content .= '</div>';
	
			return $content;
		}else{
			// if not a post/page then don't include sharing button
			return $content;
		}
	};
	add_filter( 'the_content', 'shareme_social_sharing_buttons');
}

add_shortcode( 'show_shareme_social_buttons', 'shareme_social_sharing_buttons' );