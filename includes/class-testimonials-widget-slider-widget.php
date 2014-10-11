<?php
/**
Testimonials Widget
Copyright (C) 2014  Michael Cannon

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

require_once AIHR_DIR_INC . 'class-aihrus-widget.php';

if ( class_exists( 'Testimonials_Widget_Slider_Widget' ) ) {
	return;
}


class Testimonials_Widget_Slider_Widget extends Aihrus_Widget {
	// should be tw_slider_widget, but for backwards compliance left alone
	const ID = 'testimonials_widget';


	public function __construct() {
		$classname   = __CLASS__;
		$description = esc_html__( 'Displays rotating testimonials or statically.', 'testimonials-widget' );
		$id_base     = self::ID;
		$title       = esc_html__( 'Testimonials Slider', 'testimonials-widget' );

		parent::__construct( $classname, $description, $id_base, $title );
	}


	public static function get_testimonials_css() {
		Testimonials_Widget::get_testimonials_css();
	}


	public static function get_testimonials_scripts() {
		Testimonials_Widget::get_testimonials_scripts();
	}


	public static function get_defaults() {
		return Testimonials_Widget::get_defaults();
	}


	public static function get_content( $instance = null, $widget_number = null ) {
		return Testimonials_Widget::testimonials_slider( $instance, $widget_number );
	}


	public static function form_instance( $instance ) {
		$do_number = true;
		if ( empty( $instance ) ) {
			$do_number = false;

			$instance['char_limit'] = 500;
			$instance['random']     = 1;
		} elseif ( ! empty( $instance['resetted'] ) ) {
			if ( empty( $instance['char_limit'] ) ) {
				$instance['char_limit'] = 500;
			}

			if ( empty( $instance['random'] ) ) {
				$instance['random'] = 1;
			}
		}

		$instance['do_number'] = $do_number;

		return $instance;
	}


	public static function form_parts( $instance = null, $number = null ) {
		$form_parts = Testimonials_Widget_Settings::get_settings();

		if ( ! empty( $instance['do_number'] ) ) {
			$std = ' .' . Testimonials_Widget::ID . $number;

			$form_parts['css_class'] = array(
				'section' => 'widget',
				'type' => 'readonly',
				'title' => esc_html__( 'CSS Class', 'testimonials-widget' ),
				'desc' => esc_html__( 'This widget\'s unique CSS class for styling', 'testimonials-widget' ),
				'std' => $std,
				'widget' => 1,
			);
		}

		$form_parts = apply_filters( 'tw_slider_widget_options', $form_parts );

		return $form_parts;
	}


	public static function get_suggest( $id, $suggest_id ) {
		return Testimonials_Widget_Settings::get_suggest( $id, $suggest_id );
	}


}


?>
