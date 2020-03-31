<?php
/**
Testimonials Widget Premium
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

if ( class_exists( 'Axl_Testimonials_Widget_Premium_Form_Widget' ) ) {
	return;
}


class Axl_Testimonials_Widget_Premium_Form_Widget extends Aihrus_Widget {
	const ID = 'twp_form_widget';

	public function __construct() {
		// Widget settings
		$widget_ops = array(
			'classname' => 'Axl_Testimonials_Widget_Premium_Form_Widget',
			'description' => esc_html__( 'Display testimonials submission form', 'testimonials-widget-premium' ),
		);

		// Widget control settings
		$control_ops = array(
			'id_base' => self::ID,
		);

		// Create the widget
		WP_Widget::__construct(
			self::ID,
			esc_html__( 'Testimonials Form', 'testimonials-widget-premium' ),
			$widget_ops,
			$control_ops
		);
	}


	public function widget( $args, $instance ) {
		$args = wp_parse_args( $args, Axl_Testimonials_Widget::get_defaults() );
		extract( $args );

		// Before widget (defined by themes)
		echo $args['before_widget'];

		$title = apply_filters( 'widget_title', $instance['form_title'], null );
		if ( ! empty( $instance['form_title_link'] ) ) {
			$target = ! empty( $instance['form_target'] ) ? $instance['form_target'] : null;
			$title  = Aihrus_Common::create_link( $instance['form_title_link'], $title, $target );
		}

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		// Display Widget
		$form = Axl_Testimonials_Widget_Premium_Form::testimonials_form( $instance );

		echo $form;

		// After widget (defined by themes)
		echo $args['after_widget'];
	}


	public function form( $instance ) {
		$defaults   = Axl_Testimonials_Widget::get_defaults();
		$instance   = wp_parse_args( $instance, $defaults );
		$form_parts = Axl_Testimonials_Widget_Settings::get_settings();
		$form_parts = self::widget_options( $form_parts );

		foreach ( $form_parts as $key => $part ) {
			$part['id'] = $key;
			$this->display_setting( $part, $instance );
		}
	}


	public static function widget_options( $options ) {
		// remove non-widget parts
		foreach ( $options as $id => $parts ) {
			if ( 'form' != $parts['section'] || empty( $parts['widget'] ) ) {
				$options[ $id ]['widget'] = 0;
			}
		}

		return $options;
	}
}


?>
