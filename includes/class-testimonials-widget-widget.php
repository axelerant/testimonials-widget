<?php
/*
	Copyright 2014 Michael Cannon (email: mc@aihr.us)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

require_once AIHR_DIR_INC . 'class-aihrus-widget.php';

if ( class_exists( 'Testimonials_Widget_Widget' ) )
	return;


class Testimonials_Widget_Widget extends Aihrus_Widget {
	const ID = 'testimonials_widget';

	public function __construct( $classname = null, $description = null, $id_base = null, $title = null ) {
		$classname   = 'Testimonials_Widget_Widget';
		$description = esc_html__( 'Display testimonials with multiple selection and display options', 'testimonials-widget' );
		$id_base     = self::ID;
		$title       = esc_html__( 'Testimonials', 'testimonials-widget' );

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


	public static function get_content( $instance, $widget_number ) {
		return Testimonials_Widget::testimonials_slider( $instance, $widget_number );
	}


	public static function validate_settings( $instance ) {
		return Testimonials_Widget_Settings::validate_settings( $instance );
	}


	public static function form_instance( $instance ) {
		$do_number = true;
		if ( empty( $instance ) ) {
			$do_number = false;

			$instance['char_limit']    = 500;
			$instance['random']        = 1;
			$instance['enable_schema'] = 0;
		} elseif ( ! empty( $instance['resetted'] ) ) {
			if ( empty( $instance['char_limit'] ) )
				$instance['char_limit'] = 500;

			if ( empty( $instance['random'] ) )
				$instance['random'] = 1;

			$instance['enable_schema'] = 0;
		}

		$instance['do_number'] = $do_number;

		return $instance;
	}


	public static function form_parts( $instance, $number ) {
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

		$form_parts = apply_filters( 'testimonials_widget_widget_options', $form_parts );

		return $form_parts;
	}


	public static function get_suggest( $id, $suggest_id ) {
		return Testimonials_Widget_Settings::get_suggest( $id, $suggest_id );
	}


}


?>
