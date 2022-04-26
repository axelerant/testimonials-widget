<?php
/**
 * Plugin Name: Testimonials Widget Premium
 * Plugin URI: http://wordpress.org/plugins/testimonials-widget/
 * Description: WordPress' most fully featured testimonials plugin demonstrates social proof via forms, lists, ratings,  shortcodes, sliders, or widgets.
 * lets you socially randomly slide or list selected portfolios, quotes, reviews, or text with images or videos on your WordPress site.
 * Version: 4.0.3RC1
 * Author: Axelerant
 * Author URI: https://www.axelerant.com/
 * License: GPLv2 or later
 * Text Domain: testimonials-widget-premium
 * Domain Path: /languages
 */
/**
Testimonials Widget Premium
Copyright (C) 2022 Axelerant

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TWP_BASE', plugin_basename( __FILE__ ) );
define( 'TWP_DIR', plugin_dir_path( __FILE__ ) );
define( 'TWP_DIR_INC', TWP_DIR . 'includes/' );
define( 'TWP_DIR_LIB', TWP_DIR_INC . 'libraries/' );
define( 'TWP_NAME', 'Testimonials Widget Premium' );
define( 'TWP_PRODUCT_ID', '14714' );
define( 'TWP_REQ_BASE', 'testimonials-widget/testimonials-widget.php' );
define( 'TWP_REQ_NAME', 'Testimonials Widget' );
define( 'TWP_VERSION', '4.0.3RC1' );
if ( ! defined( 'TW_VERSION' ) ) {
	define( 'TW_VERSION', '4.0.3RC1' );
}
if ( ! defined( 'TW_AIHR_VERSION' ) ) {
	define( 'TW_AIHR_VERSION', '1.3.4' );
}

require_once TWP_DIR_LIB . TWP_REQ_BASE;
require_once TWP_DIR_INC . 'requirements.php';

if ( ! twp_requirements_check() ) {
	return false;
}

require_once TWP_DIR_INC . 'class-testimonials-widget-premium.php';


add_action( 'plugins_loaded', 'testimonialswidgetpremium_init', 20 );


/**
 *
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
function testimonialswidgetpremium_init() {
	global $TW_Premium_Licensing;
	if ( is_null( $TW_Premium_Licensing ) ) {
		$TW_Premium_Licensing = new Axl_Testimonials_Widget_Premium_Licensing();
	}

	if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
		require_once TWP_DIR_LIB . 'EDD_SL_Plugin_Updater.php';
	}

	$TW_Premium_Updater = new EDD_SL_Plugin_Updater(
		$TW_Premium_Licensing->store_url,
		__FILE__,
		array(
			'version' => TWP_VERSION,
			'license' => $TW_Premium_Licensing->get_license(),
			'item_name' => TWP_NAME,
			'author' => $TW_Premium_Licensing->author,
		)
	);

	if ( Axl_Testimonials_Widget_Premium::version_check() ) {
		global $TW_Premium;
		if ( is_null( $TW_Premium ) ) {
			$TW_Premium = new Axl_Testimonials_Widget_Premium();
		}

		global $TW_Premium_Cache;
		if ( is_null( $TW_Premium_Cache ) ) {
			$TW_Premium_Cache = new Axl_Testimonials_Widget_Premium_Cache();
		}

		global $TW_Premium_Form;
		if ( is_null( $TW_Premium_Form ) ) {
			$TW_Premium_Form = new Axl_Testimonials_Widget_Premium_Form();
		}

		do_action( 'twp_init' );
	}
}


register_activation_hook( __FILE__, array( 'Axl_Testimonials_Widget_Premium', 'activation' ) );
register_deactivation_hook( __FILE__, array( 'Axl_Testimonials_Widget_Premium', 'deactivation' ) );
register_uninstall_hook( __FILE__, array( 'Axl_Testimonials_Widget_Premium', 'uninstall' ) );


function testimonials_count( $atts = array() ) {
	global $TW_Premium;

	return $TW_Premium->testimonials_count( $atts );
}


function testimonials_links( $atts = array() ) {
	global $TW_Premium;

	return $TW_Premium->testimonials_links( $atts );
}


function testimonials_form( $atts = array() ) {
	global $TW_Premium_Form;

	return $TW_Premium_Form->testimonials_form( $atts );
}


function testimonialswidgetpremium_count( $atts = array() ) {
	return testimonials_count( $atts );
}


function testimonialswidgetpremium_form( $atts = array() ) {
	return testimonials_form( $atts );
}


function testimonialswidgetpremium_link_list( $atts = array() ) {
	return testimonials_links( $atts );
}


/**
 *
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function twp_ratings( $args = null ) {
	global $post;

	$id      = 'testimonials-widget-rating';
	$rating  = get_post_meta( $post->ID, $id, true );
	$ratings = Axl_Testimonials_Widget_Premium::get_ratings( $rating, $post->ID, $id );

	echo $ratings;
}


function twp_session_get( $key ) {
	return Axl_Testimonials_Widget_Premium::$session->get( $key );
}


function twp_session_set( $key, $value ) {
	Axl_Testimonials_Widget_Premium::$session->set( $key, $value );
}


?>
