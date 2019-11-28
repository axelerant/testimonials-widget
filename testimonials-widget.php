<?php
/**
 * Plugin Name: Testimonials Widget
 * Plugin URI: http://wordpress.org/plugins/testimonials-widget/
 * Description: Easily add social proofing to your website with Testimonials Widget. List or slide reviews via functions, shortcodes, or widgets.
 * lets you socially randomly slide or list selected portfolios, quotes, reviews, or text with images or videos on your WordPress site.
 * Version: 3.5.1
 * Author: Axelerant
 * Author URI: https://www.axelerant.com/
 * License: GPLv2 or later
 * Text Domain: testimonials-widget
 * Domain Path: /languages
 */
/**
Testimonials Widget
Copyright (C) 2015 Axelerant

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

if ( ! defined( 'TW_AIHR_VERSION' ) ) {
	define( 'TW_AIHR_VERSION', '1.3.4' );
}

if ( ! defined( 'TW_BASE' ) ) {
	define( 'TW_BASE', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'TW_DIR' ) ) {
	define( 'TW_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'TW_DIR_INC' ) ) {
	define( 'TW_DIR_INC', TW_DIR . 'includes/' );
}

if ( ! defined( 'TW_DIR_LIB' ) ) {
	define( 'TW_DIR_LIB', TW_DIR_INC . 'libraries/' );
}

if ( ! defined( 'TW_NAME' ) ) {
	define( 'TW_NAME', 'Testimonials Widget' );
}

if ( ! defined( 'TW_PREMIUM_LINK' ) ) {
	define( 'TW_PREMIUM_LINK', '<a href="https://store.axelerant.com/downloads/best-wordpress-testimonials-plugin-testimonials-premium/">Buy Premium</a>' );
}

if ( ! defined( 'TW_VERSION' ) ) {
	define( 'TW_VERSION', '3.5.1' );
}

require_once TW_DIR_INC . 'requirements.php';

global $tw_activated;

$tw_activated = true;
if ( ! tw_requirements_check() ) {
	$tw_activated = false;

	return false;
}

require_once TW_DIR_INC . 'class-testimonials-widget.php';

add_action( 'plugins_loaded', 'testimonialswidget_init', 99 );


/**
 *
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
if ( ! function_exists( 'testimonialswidget_init' ) ) {
	function testimonialswidget_init() {
		if ( Axl_Testimonials_Widget::version_check() ) {
			global $Axl_Testimonials_Widget_Settings;
			if ( is_null( $Axl_Testimonials_Widget_Settings ) ) {
				$Axl_Testimonials_Widget_Settings = new Axl_Testimonials_Widget_Settings();
			}

			global $Axl_Testimonials_Widget;
			if ( is_null( $Axl_Testimonials_Widget ) ) {
				$Axl_Testimonials_Widget = new Axl_Testimonials_Widget();
			}
		}
	}
}


register_activation_hook( __FILE__, array( 'Axl_Testimonials_Widget', 'activation' ) );
register_deactivation_hook( __FILE__, array( 'Axl_Testimonials_Widget', 'deactivation' ) );
register_uninstall_hook( __FILE__, array( 'Axl_Testimonials_Widget', 'uninstall' ) );


/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
if ( ! function_exists( 'testimonials' ) ) {
	function testimonials( $atts = array() ) {
		global $Axl_Testimonials_Widget;

		return $Axl_Testimonials_Widget->testimonials( $atts );
	}
}


/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
if ( ! function_exists( 'testimonials_archives' ) ) {
	function testimonials_archives( $atts = array() ) {
		global $Axl_Testimonials_Widget;

		return $Axl_Testimonials_Widget->testimonials_archives( $atts );
	}
}


/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
if ( ! function_exists( 'testimonials_categories' ) ) {
	function testimonials_categories( $atts = array() ) {
		global $Axl_Testimonials_Widget;

		return $Axl_Testimonials_Widget->testimonials_categories( $atts );
	}
}


/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
if ( ! function_exists( 'testimonials_recent' ) ) {
	function testimonials_recent( $atts = array() ) {
		global $Axl_Testimonials_Widget;

		return $Axl_Testimonials_Widget->testimonials_recent( $atts );
	}
}


/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
if ( ! function_exists( 'testimonials_slider' ) ) {
	function testimonials_slider( $atts = array(), $widget_number = null ) {
		global $Axl_Testimonials_Widget;

		return $Axl_Testimonials_Widget->testimonials_slider( $atts, $widget_number );
	}
}


/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
if ( ! function_exists( 'testimonials_tag_cloud' ) ) {
	function testimonials_tag_cloud( $atts = array() ) {
		global $Axl_Testimonials_Widget;

		return $Axl_Testimonials_Widget->testimonials_tag_cloud( $atts );
	}
}


/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
if ( ! function_exists( 'testimonials_examples' ) ) {
	function testimonials_examples( $atts = array() ) {
		global $Axl_Testimonials_Widget;

		return $Axl_Testimonials_Widget->testimonials_examples( $atts );
	}
}


/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
if ( ! function_exists( 'testimonials_options' ) ) {
	function testimonials_options( $atts = array() ) {
		global $Axl_Testimonials_Widget;

		return $Axl_Testimonials_Widget->testimonials_options( $atts );
	}
}


if ( ! function_exists( 'testimonialswidget_list' ) ) {
	function testimonialswidget_list( $atts = array() ) {
		return testimonials( $atts );
	}
}


if ( ! function_exists( 'testimonialswidget_widget' ) ) {
	function testimonialswidget_widget( $atts = array() ) {
		return testimonials_slider( $atts );
	}
}

?>
