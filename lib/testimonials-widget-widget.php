<?php

class Testimonials_Widget_Widget extends WP_Widget {
	public function Testimonials_Widget_Widget() {
		// Widget settings
		$widget_ops				= array(
			'classname'			=> 'Testimonials_Widget_Widget',
			'description'		=> __( 'Display testimonials with multiple selection and display options', 'testimonials-widget' )
		);

		// Widget control settings
		$control_ops			= array(
			'id_base'			=> 'testimonials_widget',
		);

		// Create the widget
		$this->WP_Widget(
			'testimonials_widget',
			__( 'Testimonials Widget', 'testimonials-widget' ),
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

		$args					= wp_parse_args( $args, Testimonials_Widget::get_defaults() );
		extract( $args );

		// Our variables from the widget settings
		$title					= apply_filters( 'widget_title', $instance['title'], null );

		$testimonials			= Testimonials_Widget::testimonialswidget_widget( $instance, $this->number );

		// Before widget (defined by themes)
		echo $before_widget;

		// Display the widget title if one was input (before and after defined by themes)
		if ( ! empty( $title ) ) {
			if ( ! empty( $instance['title_link'] ) ) {
				// revise title with title_link link creation
				$title_link		= $instance['title_link'];
			   
				if ( preg_match( '#^\d+$#', $title_link ) ) {
					$new_title	= '<a href="';
					$new_title	.= get_permalink( $title_link );
					$new_title	.= '" title="';
					$new_title	.= get_the_title( $title_link );
					$new_title	.= '">';
					$new_title	.= $title;
					$new_title	.= '</a>';

					$title		= $new_title;
				} else {
					if ( 0 === preg_match( "#https?://#", $title_link ) ) {
						$title_link	= 'http://' . $title_link;
					}

					$new_title	= '<a href="';
					$new_title	.= $title_link;
					$new_title	.= '" title="';
					$new_title	.= $title;
					$new_title	.= '"';

					if ( ! empty( $instance['target'] ) ) {
						$new_title	.= ' target="';
						$new_title	.= $instance['target'];
						$new_title	.= '" ';
					}

					$new_title	.= '>';
					$new_title	.= $title;
					$new_title	.= '</a>';

					$title		= $new_title;
				}
			}
			
			echo $before_title . $title . $after_title;
		}

		// Display Widget
		echo $testimonials;

		// After widget (defined by themes)
		echo $after_widget;
	}


	public function update( $new_instance, $old_instance ) {
		$instance				= Testimonials_Widget_Settings::validate_settings( $new_instance );

		return $instance;
	}

	public function form( $instance ) {
		$defaults				= Testimonials_Widget::get_defaults();
		$do_number				= true;

		if ( empty( $instance ) ) {
			$do_number		= false;

			if ( empty( $defaults['char_limit']	) )
				$defaults['char_limit']	= 500;

			if ( empty( $defaults['random']	) )
				$defaults['random']		= 1;

			$instance			= array();
		}

		$instance				= wp_parse_args( $instance, $defaults );
		$form_parts				= Testimonials_Widget_Settings::get_settings();

		if ( $do_number ) {
			$number				= $this->number;
			$std				= ' .' . Testimonials_Widget::id . $number;
			$form_parts['css_class']	= array(
				'section'		=> 'widget',
				'type'			=> 'readonly',
				'title'   		=> __( 'CSS Class', 'testimonials-widget' ),
				'desc'			=> __( 'This widget\'s unique CSS class for styling', 'testimonials-widget' ),
				'std'			=> $std,
			);
		}

		// remove non-widget parts
		unset( $form_parts['paging'] );
		unset( $form_parts['debug_mode'] );

		// fixme make reset work
		unset( $form_parts['reset_defaults'] );

		foreach ( $form_parts as $key => $part ) {
			$part[ 'id' ]		= $key;
			$this->display_setting( $part, $instance );
		}
	}


	public function display_setting( $args = array(), $options ) {
		extract( $args );

		$do_return				= false;
		switch ( $type ) {
			case 'heading':
				if ( ! empty( $desc ) )
					echo '<h3>' . $desc . '</h3>';

				$do_return		= true;
			break;

			case 'expand_begin':
				if ( ! empty( $desc ) )
					echo '<h3>' . $desc . '</h3>';

				echo '<a id="' . $this->get_field_id( $id ) . '" style="cursor:pointer;" onclick="jQuery( \'div#' . $this->get_field_id( $id ) . '\' ) . slideToggle();">' . __( 'Expand/Collapse', 'testimonials-widget' ) . ' &raquo;</a>';
				echo '<div id="' . $this->get_field_id( $id ) . '" style="display:none">';

				$do_return		= true;
				break;

			case 'expand_end':
				echo '</div>';

				$do_return		= true;
				break;

			default:
				break;
		}

		if ( $do_return )
			return;

		if ( ! isset( $options[$id] ) && $type != 'checkbox' )
			$options[$id]		= $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id]		= 0;

		$field_class			= '';
		if ( ! empty( $class ) )
			$field_class		= ' ' . $class;

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
				$i				= 0;
				$count_options	= count( $options ) - 1;
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
