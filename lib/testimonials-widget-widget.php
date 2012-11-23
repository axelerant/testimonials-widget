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
					if ( ! empty( $title_link ) && 0 === preg_match( "#https?://#", $title_link ) ) {
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
		$instance					= $old_instance;

		$instance['category']		= ( empty( $new_instance['category'] ) || preg_match( '#^[\w-]+(,[\w-]+)*$#', $new_instance['category'] ) ) ? $new_instance['category'] : $instance['category'];
		$instance['char_limit']		= ( empty( $new_instance['char_limit'] ) || ( is_numeric( $new_instance['char_limit'] ) && 0 <= $new_instance['char_limit'] ) ) ? $new_instance['char_limit'] : $instance['char_limit'];
		$instance['hide_company']	= ( 'true' == $new_instance['hide_company'] ) ? 'true' : '';
		$instance['hide_email']		= ( 'true' == $new_instance['hide_email'] ) ? 'true' : '';
		$instance['hide_gravatar']	= ( 'true' == $new_instance['hide_gravatar'] ) ? 'true' : '';
		$instance['hide_image']		= ( 'true' == $new_instance['hide_image'] ) ? 'true' : '';
		$instance['hide_not_found']	= ( 'true' == $new_instance['hide_not_found'] ) ? 'true' : '';
		$instance['hide_source']	= ( 'true' == $new_instance['hide_source'] ) ? 'true' : '';
		$instance['hide_title']		= ( 'true' == $new_instance['hide_title'] ) ? 'true' : '';
		$instance['hide_url']		= ( 'true' == $new_instance['hide_url'] ) ? 'true' : '';
		$instance['ids']			= ( empty( $new_instance['ids'] ) || preg_match( '#^\d+(,\d+)*$#', $new_instance['ids'] ) ) ? $new_instance['ids'] : $instance['ids'];
		$instance['limit']			= ( empty( $new_instance['limit'] ) || ( is_numeric( $new_instance['limit'] ) && 0 < $new_instance['limit'] ) ) ? $new_instance['limit'] : $instance['limit'];;
		$instance['max_height']		= ( empty( $new_instance['max_height'] ) || ( is_numeric( $new_instance['max_height'] ) && 0 <= $new_instance['max_height'] ) ) ? $new_instance['max_height'] : $instance['max_height'];;
		$instance['meta_key']		= ( preg_match( '#^[\w-,]+$#', $new_instance['meta_key'] ) ) ? $new_instance['meta_key'] : $instance['meta_key'];
		$instance['min_height']		= ( empty( $new_instance['min_height'] ) || ( is_numeric( $new_instance['min_height'] ) && 0 <= $new_instance['min_height'] ) ) ? $new_instance['min_height'] : $instance['min_height'];;
		$instance['order']			= ( preg_match( '#^desc|asc$#i', $new_instance['order'] ) ) ? $new_instance['order'] : $instance['order'];
		$instance['orderby']		= ( preg_match( '#^\w+$#', $new_instance['orderby'] ) ) ? $new_instance['orderby'] : $instance['orderby'];
		$instance['random']			= ( 'true' == $new_instance['random'] ) ? 'true' : '';
		$instance['refresh_interval']	= ( empty( $new_instance['refresh_interval'] ) || ( is_numeric( $new_instance['refresh_interval'] ) && 0 <= $new_instance['refresh_interval'] ) ) ? $new_instance['refresh_interval'] : $instance['refresh_interval'];
		$instance['tags']			= ( empty( $new_instance['tags'] ) || preg_match( '#^[\w-]+(,[\w-]+)*$#', $new_instance['tags'] ) ) ? $new_instance['tags'] : $instance['tags'];
		$instance['tags_all']		= ( 'true' == $new_instance['tags_all'] ) ? 'true' : '';
		$instance['target']			= ( empty( $new_instance['target'] ) || preg_match( '#^\w+$#', $new_instance['target'] ) ) ? $new_instance['target'] : $instance['target'];
		$instance['title']			= wp_kses_data( $new_instance['title'] );
		$instance['title_link']		= wp_kses_data( $new_instance['title_link'] );
		$instance['widget_text']	= wp_kses_post( $new_instance['widget_text'] );

		$instance					= apply_filters( 'testimonials_widget_options_update', $instance, $new_instance );

		return $instance;
	}

	public function form( $instance ) {
		if ( empty( $instance ) ) {
			$defaults				= Testimonials_Widget::get_defaults();

			if ( empty( $defaults['char_limit']	) )
				$defaults['char_limit']	= 500;

			if ( empty( $defaults['random']	) )
				$defaults['random']		= 'true';

			$instance				= wp_parse_args( array(), $defaults );
		}

		$form_parts				= array();

		$form_parts['title']	= '<p><label for="' . $this->get_field_id( 'title' ) . '">' . __( 'Title', 'testimonials-widget' ) . '</label><input class="widefat" type="text" id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '" value="' . htmlspecialchars($instance['title'], ENT_QUOTES) . '" /></p>';

		$form_parts['title_link']	= '<p><label for="' . $this->get_field_id( 'title_link' ) . '">' . __( 'Title Link', 'testimonials-widget' ) . '</label><input class="widefat" type="text" id="' . $this->get_field_id( 'title_link' ) . '" name="' . $this->get_field_name( 'title_link' ) . '" value="' . htmlspecialchars($instance['title_link'], ENT_QUOTES) . '" /><br/><span class="setting-description"><small>' . __( 'URL or Post ID to link widget title to', 'testimonials-widget' ) . '</small></span></p>';

		$form_parts['category']	= '<p><label for="' . $this->get_field_id( 'category' ) . '">' . __( 'Category filter', 'testimonials-widget' ) . '</label><input class="widefat" type="text" id="' . $this->get_field_id( 'category' ) . '" name="' . $this->get_field_name( 'category' ) . '" value="' . htmlspecialchars($instance['category'], ENT_QUOTES) . '" /><br/><span class="setting-description"><small>' . __( 'Comma separated category slug-names', 'testimonials-widget' ) . '</small></span></p>';

		$form_parts['tags']		= '<p><label for="' . $this->get_field_id( 'tags' ) . '">' . __( 'Tags filter', 'testimonials-widget' ) . '</label><input class="widefat" type="text" id="' . $this->get_field_id( 'tags' ) . '" name="' . $this->get_field_name( 'tags' ) . '" value="' . htmlspecialchars($instance['tags'], ENT_QUOTES) . '" /><br/><span class="setting-description"><small>' . __( 'Comma separated tag slug-names', 'testimonials-widget' ) . '</small></span></p>';

		$form_parts['tags_all']	= '<p><input type="checkbox" id="' . $this->get_field_id( 'tags_all' ) . '" name="' . $this->get_field_name( 'tags_all' ) . '" value="true"' . checked( $instance['tags_all'], 'true', false ) . ' /> <label for="' . $this->get_field_id( 'tags_all' ) . '">' . __( 'Require all tags', 'testimonials-widget' ) . '</label><br/><span class="setting-description"><small>' . __( 'Select only testimonials with all of the given tags', 'testimonials-widget' ) . '</small></span></p>';

		$form_parts['adv_key']	= "<p style=\"text-align:left;\"><small><a id=\"" . $this->get_field_id( 'adv_key' ) . "\" style=\"cursor:pointer;\" onclick=\"jQuery( 'div#" . $this->get_field_id( 'adv_opts' ) . "' ) . slideToggle();\">" . __( 'Advanced Options', 'testimonials-widget' ) . " &raquo;</a></small></p>";

		$form_parts['adv_opts']	= '<div id="' . $this->get_field_id( 'adv_opts' ) . '" style="display:none">';

		$form_parts['hide_gravatar']	= '<p><input type="checkbox" id="' . $this->get_field_id( 'hide_gravatar' ) . '" name="' . $this->get_field_name( 'hide_gravatar' ) . '" value="true"' . checked( $instance['hide_gravatar'], 'true', false ) . ' /> <label for="' . $this->get_field_id( 'hide_gravatar' ) . '">' . __( 'Hide gravatar?', 'testimonials-widget' ) . '</label></p>';

		$form_parts['hide_image']	= '<p><input type="checkbox" id="' . $this->get_field_id( 'hide_image' ) . '" name="' . $this->get_field_name( 'hide_image' ) . '" value="true"' . checked( $instance['hide_image'], 'true', false ) . ' /> <label for="' . $this->get_field_id( 'hide_image' ) . '">' . __( 'Hide image?', 'testimonials-widget' ) . '</label></p>';

		$form_parts['hide_not_found']	= '<p><input type="checkbox" id="' . $this->get_field_id( 'hide_not_found' ) . '" name="' . $this->get_field_name( 'hide_not_found' ) . '" value="true"' . checked( $instance['hide_not_found'], 'true', false ) . ' /> <label for="' . $this->get_field_id( 'hide_not_found' ) . '">' . __( 'Hide testimonials not found?', 'testimonials-widget' ) . '</label></p>';

		$form_parts['hide_title']	= '<p><input type="checkbox" id="' . $this->get_field_id( 'hide_title' ) . '" name="' . $this->get_field_name( 'hide_title' ) . '" value="true"' . checked( $instance['hide_title'], 'true', false ) . ' /> <label for="' . $this->get_field_id( 'hide_title' ) . '">' . __( 'Hide title?', 'testimonials-widget' ) . '</label></p>';

		$form_parts['hide_source']	= '<p><input type="checkbox" id="' . $this->get_field_id( 'hide_source' ) . '" name="' . $this->get_field_name( 'hide_source' ) . '" value="true"' . checked( $instance['hide_source'], 'true', false ) . ' /> <label for="' . $this->get_field_id( 'hide_source' ) . '">' . __( 'Hide source?', 'testimonials-widget' ) . '</label></p>';

		$form_parts['hide_email']	= '<p><input type="checkbox" id="' . $this->get_field_id( 'hide_email' ) . '" name="' . $this->get_field_name( 'hide_email' ) . '" value="true"' . checked( $instance['hide_email'], 'true', false ) . ' /> <label for="' . $this->get_field_id( 'hide_email' ) . '">' . __( 'Hide email?', 'testimonials-widget' ) . '</label></p>';

		$form_parts['hide_company']	= '<p><input type="checkbox" id="' . $this->get_field_id( 'hide_company' ) . '" name="' . $this->get_field_name( 'hide_company' ) . '" value="true"' . checked( $instance['hide_company'], 'true', false ) . ' /> <label for="' . $this->get_field_id( 'hide_company' ) . '">' . __( 'Hide company?', 'testimonials-widget' ) . '</label></p>';

		$form_parts['hide_url']	= '<p><input type="checkbox" id="' . $this->get_field_id( 'hide_url' ) . '" name="' . $this->get_field_name( 'hide_url' ) . '" value="true"' . checked( $instance['hide_url'], 'true', false ) . ' /> <label for="' . $this->get_field_id( 'hide_url' ) . '">' . __( 'Hide URL?', 'testimonials-widget' ) . '</label></p>';

		$form_parts['target']	= '<p><label for="' . $this->get_field_id( 'target' ) . '">' . __( 'URL Target', 'testimonials-widget' ) . '</label><input size="15" type="text" id="' . $this->get_field_id( 'target' ) . '" name="' . $this->get_field_name( 'target' ) . '" value="' . htmlspecialchars($instance['target'], ENT_QUOTES) . '" /><br/><span class="setting-description"><small>' . __( 'Leave blank if none', 'testimonials-widget' ) . '</small></span></p>';

		$form_parts['char_limit']	= '<p><label for="' . $this->get_field_id( 'char_limit' ) . '">' . __( 'Character limit', 'testimonials-widget' ) . '</label><input size="4" type="text" id="' . $this->get_field_id( 'char_limit' ) . '" name="' . $this->get_field_name( 'char_limit' ) . '" value="' . htmlspecialchars($instance['char_limit'], ENT_QUOTES) . '" /><br/><span class="setting-description"><small>' . __( 'Number of characters to limit testimonial views to', 'testimonials-widget' ) . '</small></span></p>';

		$form_parts['ids']		= '<p><label for="' . $this->get_field_id( 'ids' ) . '">' . __( 'IDs filter', 'testimonials-widget' ) . '</label><input class="widefat" type="text" id="' . $this->get_field_id( 'ids' ) . '" name="' . $this->get_field_name( 'ids' ) . '" value="' . htmlspecialchars($instance['ids'], ENT_QUOTES) . '" /><br/><span class="setting-description"><small>' . __( 'Comma separated IDs', 'testimonials-widget' ) . '</small></span></p>';

		$form_parts['limit']	= '<p><label for="' . $this->get_field_id( 'limit' ) . '">' . __( 'Limit', 'testimonials-widget' ) . '</label><input size="4" type="text" id="' . $this->get_field_id( 'limit' ) . '" name="' . $this->get_field_name( 'limit' ) . '" value="' . htmlspecialchars($instance['limit'], ENT_QUOTES) . '" /><br/><span class="setting-description"><small>' . __( 'Number of testimonials to rotate through', 'testimonials-widget' ) . '</small></span></p>';

		$form_parts['min_height']	= '<p><label for="' . $this->get_field_id( 'min_height' ) . '">' . __('Minimum Height', 'testimonials-widget') . '</label><input size="4" type="text" id="' . $this->get_field_id( 'min_height' ) . '" name="' . $this->get_field_name( 'min_height' ) . '" value="' . htmlspecialchars($instance['min_height'], ENT_QUOTES) . '" /><br/><span class="setting-description"><small>' . __('Set for minimum display height', 'testimonials-widget') . '</small></span></p>';

		$form_parts['max_height']	= '<p><label for="' . $this->get_field_id( 'max_height' ) . '">' . __('Maximum Height', 'testimonials-widget') . '</label><input size="4" type="text" id="' . $this->get_field_id( 'max_height' ) . '" name="' . $this->get_field_name( 'max_height' ) . '" value="' . htmlspecialchars($instance['max_height'], ENT_QUOTES) . '" /><br/><span class="setting-description"><small>' . __('Set for maximum display height', 'testimonials-widget') . '</small></span></p>';

		$orderby_select			= '<select id="' . $this->get_field_id( 'orderby' ) . '" name="' . $this->get_field_name( 'orderby' ) . '">';
		$orderby_options		= array(
			'ID'				=> __( 'Testimonial ID', 'testimonials-widget' ),
			'author'			=> __( 'Author', 'testimonials-widget' ),
			'title'				=> __( 'Source', 'testimonials-widget' ),
			'date'				=> __( 'Date', 'testimonials-widget' ),
		);

		foreach ( $orderby_options as $option => $title ) {
			$orderby_select		.= '<option value="' . $option . '" ';
			$orderby_select		.= selected( $instance['orderby'], $option, false );
			$orderby_select		.= '>';
			$orderby_select		.= $title;
			$orderby_select		.= '</option>';
		}

		$orderby_select			.= '</select>';

		$form_parts['orderby']	= '<p><label for="' . $this->get_field_id( 'orderby' ) . '">' . __( 'ORDER BY', 'testimonials-widget' ) . '</label>' . $orderby_select . '<br/><span class="setting-description"><small>' . __( 'Used when Random order is disabled', 'testimonials-widget' ) . '</small></span></p>';

		$meta_key_select		= '<select id="' . $this->get_field_id( 'meta_key' ) . '" name="' . $this->get_field_name( 'meta_key' ) . '">';
		$meta_key_options		= array(
			''								=> __( 'None' , 'testimonials-widget'),
			'testimonials-widget-title' 	=> __( 'Title' , 'testimonials-widget'),
			'testimonials-widget-email' 	=> __( 'Email' , 'testimonials-widget'),
			'testimonials-widget-company' 	=> __( 'Company' , 'testimonials-widget'),
			'testimonials-widget-url' 		=> __( 'URL' , 'testimonials-widget'),
		);

		foreach ( $meta_key_options as $option => $title ) {
			$meta_key_select	.= '<option value="' . $option . '" ';
			$meta_key_select	.= selected( $instance['meta_key'], $option, false );
			$meta_key_select	.= '>';
			$meta_key_select	.= $title;
			$meta_key_select	.= '</option>';
		}

		$meta_key_select		.= '</select>';

		$form_parts['meta_key']	= '<p><label for="' . $this->get_field_id( 'meta_key' ) . '">' . __( 'Sort by meta key', 'testimonials-widget' ) . '</label>' . $meta_key_select . '<br/><span class="setting-description"><small>' . __( 'Used when Random order is disabled and sorting by a testimonials meta key is needed. Overrides ORDER BY', 'testimonials-widget' ) . '</small></span></p>';

		$order_select			= '<select id="' . $this->get_field_id( 'order' ) . '" name="' . $this->get_field_name( 'order' ) . '">';
		$order_options			= array(
			'DESC'				=> __( 'Descending', 'testimonials-widget' ),
			'ASC'				=> __( 'Ascending', 'testimonials-widget' ),
		);

		foreach ( $order_options as $option => $title ) {
			$order_select		.= '<option value="' . $option . '" ';
			$order_select		.= selected( $instance['order'], $option, false );
			$order_select		.= '>';
			$order_select		.= $title;
			$order_select		.= '</option>';
		}

		$order_select			.= '</select>';

		$form_parts['order']	= '<p><label for="' . $this->get_field_id( 'order' ) . '">' . __( 'ORDER BY Order', 'testimonials-widget' ) . '</label>' . $order_select . '</p>';

		$form_parts['random']	= '<p><input type="checkbox" id="' . $this->get_field_id( 'random' ) . '" name="' . $this->get_field_name( 'random' ) . '" value="true"' . checked( $instance['random'], 'true', false ) . ' /> <label for="' . $this->get_field_id( 'random' ) . '">' . __( 'Random order', 'testimonials-widget' ) . '</label><br/><span class="setting-description"><small>' . __( 'Unchecking this will rotate testimonials per ORDER BY and ORDER BY Order', 'testimonials-widget' ) . '</small></span></p>';

		$form_parts['refresh_interval']	= '<p><label for="' . $this->get_field_id( 'refresh_interval' ) . '">' . __( 'Rotation Speed', 'testimonials-widget' ) . '</label><input size="4" type="text" id="' . $this->get_field_id( 'refresh_interval' ) . '" name="' . $this->get_field_name( 'refresh_interval' ) . '" value="' . htmlspecialchars($instance['refresh_interval'], ENT_QUOTES) . '" /><br/><span class="setting-description"><small>' . __( 'Seconds between testimonial rotations or 0 for no refresh', 'testimonials-widget' ) . '</small></span></p>';

		$form_parts['widget_text']	= '<p><label for="' . $this->get_field_id( 'widget_text' ) . '">' . __( 'Widget Bottom Text', 'testimonials-widget' ) . '</label><br/><span class="setting-description"><small>' . __( 'Custom text or HTML for bottom of widgets', 'testimonials-widget' ) . '</small></span><textarea class="widefat" type="text" id="' . $this->get_field_id( 'widget_text' ) . '" name="' . $this->get_field_name( 'widget_text' ) . '" rows="8">' . htmlspecialchars($instance['widget_text'], ENT_QUOTES) . '</textarea></p>';

		$form_parts['end_div']	= '</div>';

		$form_parts				= apply_filters( 'testimonials_widget_options_form', $form_parts, $this, $instance );

		echo implode( '', $form_parts );
	}
}

?>
