<?php
global $at_template_args;

extract( $at_template_args );

$class = 'testimonials-widget-testimonial';
if ( is_single() && empty( $widget_number ) ) {
	$class .= ' single';
} elseif ( $is_list ) {
	$class .= ' list';
} else {
	// widget display
	if ( $atts['use_bxslider'] ) {
		$refresh_interval = $atts['refresh_interval'];
		if ( ! $is_first && ! empty( $refresh_interval ) && ! in_array( $atts['transition_mode'], array( 'horizontal', 'vertical' ) ) ) {
			$class .= ' display-none';
		}
	} else {
		if ( $is_first ) {
			$class .= ' active';
		} else {
			$class .= ' display-none';
		}
	}
}

if ( $atts['keep_whitespace'] ) {
	$class .= ' whitespace';
}

$post_id = $testimonial['post_id'];
if ( ! empty( $post_id ) ) {
	$class = join( ' ', get_post_class( $class, $post_id ) );
} else {
	$class = 'testimonials-widget type-testimonials-widget status-publish hentry ' . $class;
}

if ( $atts['remove_hentry'] ) {
	$class = str_replace( ' hentry', '', $class );
}

$class = apply_filters( 'testimonials_widget_get_testimonial_html_class', $class, $testimonial, $atts, $is_list, $is_first, $widget_number );
?>

<div class="<?php echo $class; ?>">
<!-- <?php echo Testimonials_Widget::ID; ?>:<?php echo $post_id; ?>: -->
