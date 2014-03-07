<?php
/**
 * Plugin Name: Testimonials by Aihrus
 * Plugin URI: http://wordpress.org/plugins/testimonials-widget/
 * Description: Testimonials by Aihrus lets you randomly slide or list selected portfolios, quotes, reviews, or text with images or videos on your WordPress site.
 * Version: 2.18.3
 * Author: Michael Cannon
 * Author URI: http://aihr.us/resume/
 * License: GPLv2 or later
 * Text Domain: testimonials-widget
 * Domain Path: /languages
 */


/**
 * Copyright 2014 Michael Cannon (email: mc@aihr.us)
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! defined( 'TW_AIHR_VERSION' ) )
	define( 'TW_AIHR_VERSION', '1.0.4RC2' );

if ( ! defined( 'TW_BASE' ) )
	define( 'TW_BASE', plugin_basename( __FILE__ ) );

if ( ! defined( 'TW_DIR' ) )
	define( 'TW_DIR', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'TW_DIR_INC' ) )
	define( 'TW_DIR_INC', TW_DIR . 'includes/' );

if ( ! defined( 'TW_DIR_LIB' ) )
	define( 'TW_DIR_LIB', TW_DIR_INC . 'libraries/' );

if ( ! defined( 'TW_NAME' ) )
	define( 'TW_NAME', 'Testimonials by Aihrus' );

if ( ! defined( 'TW_PREMIUM_LINK' ) )
	define( 'TW_PREMIUM_LINK', '<a href="http://aihr.us/downloads/testimonials-widget-premium-wordpress-plugin/">Buy Premium</a>' );

if ( ! defined( 'TW_VERSION' ) )
	define( 'TW_VERSION', '2.18.3' );

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
		if ( Testimonials_Widget::version_check() ) {
			global $Testimonials_Widget_Settings;
			if ( is_null( $Testimonials_Widget_Settings ) )
				$Testimonials_Widget_Settings = new Testimonials_Widget_Settings();

			global $Testimonials_Widget;
			if ( is_null( $Testimonials_Widget ) )
				$Testimonials_Widget = new Testimonials_Widget();
		}
	}
}


register_activation_hook( __FILE__, array( 'Testimonials_Widget', 'activation' ) );
register_deactivation_hook( __FILE__, array( 'Testimonials_Widget', 'deactivation' ) );
register_uninstall_hook( __FILE__, array( 'Testimonials_Widget', 'uninstall' ) );


if ( ! function_exists( 'testimonialswidget_list' ) ) {
	function testimonialswidget_list( $atts = array() ) {
		global $Testimonials_Widget;

		return $Testimonials_Widget->testimonialswidget_list( $atts );
	}
}


if ( ! function_exists( 'testimonialswidget_widget' ) ) {
	function testimonialswidget_widget( $atts = array(), $widget_number = null ) {
		global $Testimonials_Widget;

		return $Testimonials_Widget->testimonialswidget_widget( $atts, $widget_number );
	}
}

?>
