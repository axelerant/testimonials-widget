<?php
/*
	Copyright 2013 Michael Cannon (email: mc@aihr.us)

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

class Testimonials_Widget_Widget extends WP_Widget {
	const ID = 'testimonials_widget';

	public function __construct() {
		// Widget settings
		$widget_ops = array(
			'classname' => 'Testimonials_Widget_Widget',
			'description' => esc_html__( 'Display testimonials with multiple selection and display options', 'testimonials-widget' )
		);

		// Widget control settings
		$control_ops = array(
			'id_base' => self::ID,
		);

		// Create the widget
		$this->WP_Widget(
			self::ID,
			esc_html__( 'Testimonials Widget', 'testimonials-widget' ),
			$widget_ops,
			$control_ops
		);
	}


	public function get_testimonials_css() {
		Testimonials_Widget::get_testimonials_css();
	}


	public function get_testimonials_scripts() {
		Testimonials_Widget::get_testimonials_scripts();
	}


	public function widget( $args, $instance ) {
		global $before_widget, $before_title, $after_title, $after_widget;

		$args = wp_parse_args( $args, Testimonials_Widget::get_defaults() );
		extract( $args );

		// Our variables from the widget settings
		$title = apply_filters( 'widget_title', $instance['title'], null );

		$testimonials = Testimonials_Widget::testimonialswidget_widget( $instance, $this->number );

		// Before widget (defined by themes)
		echo $before_widget;

		// Display the widget title if one was input (before and after defined by themes)
		if ( ! empty( $title ) ) {
			if ( ! empty( $instance['title_link'] ) ) {
				// revise title with title_link link creation
				$title_link = $instance['title_link'];

				if ( preg_match( '#^\d+$#', $title_link ) ) {
					$new_title  = '<a href="';
					$new_title .= get_permalink( $title_link );
					$new_title .= '" title="';
					$new_title .= get_the_title( $title_link );
					$new_title .= '">';
					$new_title .= $title;
					$new_title .= '</a>';

					$title = $new_title;
				} else {
					$do_http = true;

					if ( 0 === strpos( $title_link, '/' ) )
						$do_http = false;

					if ( $do_http && 0 === preg_match( '#https?://#', $title_link ) ) {
						$title_link = 'http://' . $title_link;
					}

					$new_title  = '<a href="';
					$new_title .= $title_link;
					$new_title .= '" title="';
					$new_title .= $title;
					$new_title .= '"';

					$new_title .= '>';
					$new_title .= $title;
					$new_title .= '</a>';

					$title = $new_title;

					if ( ! empty( $instance['target'] ) )
						$title = links_add_target( $title, $instance['target'] );
				}
			}

			echo $before_title . $title . $after_title;
		}

		// Display Widget
		echo $testimonials;

		// After widget (defined by themes)
		echo $after_widget;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */


	public function update( $new_instance, $old_instance ) {
		$instance = Testimonials_Widget_Settings::validate_settings( $new_instance );

		return $instance;
	}


	public function form( $instance ) {
		$defaults  = Testimonials_Widget::get_defaults();
		$do_number = true;

		if ( empty( $instance ) ) {
			$do_number = false;

			if ( empty( $defaults['char_limit'] ) )
				$defaults['char_limit'] = 500;

			if ( empty( $defaults['random'] ) )
				$defaults['random'] = 1;

			$instance = array();
		}

		$instance   = wp_parse_args( $instance, $defaults );
		$form_parts = Testimonials_Widget_Settings::get_settings();

		if ( $do_number ) {
			$number = $this->number;
			$std    = ' .' . Testimonials_Widget::ID . $number;

			$form_parts['css_class'] = array(
				'section' => 'widget',
				'type' => 'readonly',
				'title' => esc_html__( 'CSS Class', 'testimonials-widget' ),
				'desc' => esc_html__( 'This widget\'s unique CSS class for styling', 'testimonials-widget' ),
				'std' => $std,
				'widget' => 1,
			);
		}

		$form_parts = self::widget_options( $form_parts );

		foreach ( $form_parts as $key => $part ) {
			$part[ 'id' ] = $key;
			$this->display_setting( $part, $instance );
		}
	}


	public static function widget_options( $options ) {
		foreach ( $options as $id => $parts ) {
			// remove non-widget parts
			if ( empty( $parts['widget'] ) )
				unset( $options[ $id ] );
		}

		$options = apply_filters( 'testimonials_widget_widget_options', $options );

		return $options;
	}


	public function display_setting( $args = array(), $options ) {
		extract( $args );

		$do_return = false;
		switch ( $type ) {
		case 'heading':
			if ( ! empty( $desc ) )
				echo '<h3>' . $desc . '</h3>';

			$do_return = true;
			break;

		case 'expand_begin':
			if ( ! empty( $desc ) )
				echo '<h3>' . $desc . '</h3>';

			echo '<a id="' . $this->get_field_id( $id ) . '" style="cursor:pointer;" onclick="jQuery( \'div#' . $this->get_field_id( $id ) . '\' ) . slideToggle();">' . esc_html__( 'Expand/Collapse', 'testimonials-widget' ) . ' &raquo;</a>';
			echo '<div id="' . $this->get_field_id( $id ) . '" style="display:none">';

			$do_return = true;
			break;

		case 'expand_end':
			echo '</div>';

			$do_return = true;
			break;

		default:
			break;
		}

		if ( $do_return )
			return;

		if ( ! isset( $options[$id] ) && $type != 'checkbox' )
			$options[$id] = $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id] = 0;

		$field_class = '';
		if ( ! empty( $class ) )
			$field_class = ' ' . $class;

		echo '<p>';

		switch ( $type ) {
		case 'checkbox':
			echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $this->get_field_id( $id ) . '" name="' . $this->get_field_name( $id ) . '" value="1" ' . checked( $options[$id], 1, false ) . ' /> ';

			echo '<label for="' . $this->get_field_id( $id ) . '">' . $title . '</label>';
			break;

		case 'select':
			echo '<label for="' . $this->get_field_id( $id ) . '">' . $title . '</label>';
			echo '<select id="' . $this->get_field_id( $id ) . '"class="select' . $field_class . '" name="' . $this->get_field_name( $id ) . '">';

			foreach ( $choices as $value => $label )
				echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';

			echo '</select>';
			break;

		case 'radio':
			$i             = 0;
			$count_options = count( $options ) - 1;

			foreach ( $choices as $value => $label ) {
				echo '<input class="radio' . $field_class . '" type="radio" name="' . $this->get_field_name( $id ) . '" id="' . $this->get_field_name( $id . $i ) . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $this->get_field_name( $id . $i ) . '">' . $label . '</label>';
				if ( $i < $count_options )
					echo '<br />';
				$i++;
			}

			echo '<label for="' . $this->get_field_id( $id ) . '">' . $title . '</label>';
			break;

		case 'textarea':
			echo '<label for="' . $this->get_field_id( $id ) . '">' . $title . '</label>';

			echo '<textarea class="widefat' . $field_class . '" id="' . $this->get_field_id( $id ) . '" name="' . $this->get_field_name( $id ) . '" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';
			break;

		case 'password':
			echo '<label for="' . $this->get_field_id( $id ) . '">' . $title . '</label>';

			echo '<input class="widefat' . $field_class . '" type="password" id="' . $this->get_field_id( $id ) . '" name="' . $this->get_field_name( $id ) . '" value="' . esc_attr( $options[$id] ) . '" />';
			break;

		case 'readonly':
			echo '<label for="' . $this->get_field_id( $id ) . '">' . $title . '</label>';

			echo '<input class="widefat' . $field_class . '" type="text" id="' . $this->get_field_id( $id ) . '" name="' . $this->get_field_name( $id ) . '" value="' . esc_attr( $options[$id] ) . '" readonly="readonly" />';
			break;

		case 'text':
			echo '<label for="' . $this->get_field_id( $id ) . '">' . $title . '</label>';

			echo '<input class="widefat' . $field_class . '" type="text" id="' . $this->get_field_id( $id ) . '" name="' . $this->get_field_name( $id ) . '" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
			break;

		default:
			break;
		}

		if ( ! empty( $desc ) )
			echo '<br /><span class="setting-description"><small>' . $desc . '</small></span>';

		echo '</p>';
	}


}


?>
