<?php
global $tw_template_args;

$atts          = $tw_template_args['atts'];
$testimonial   = $tw_template_args['testimonial'];
$widget_number = $tw_template_args['widget_number'];

$char_limit    	= $atts['char_limit'];
$content_more  	= apply_filters( 'tw_content_more', esc_html__( '…', 'testimonials-widget' ) );
$content_more  .= Axl_Testimonials_Widget::$tag_close_quote;
$do_content    	= ! $atts['hide_content'] && ! empty( $testimonial['testimonial_content'] );
$do_title		= ! $atts['hide_source_title'] && ! empty( $testimonial['testimonial_source'] );
$use_quote_tag 	= $atts['use_quote_tag'];

if ( $do_content ) {
	$content = $testimonial['testimonial_content'];
	$content = Axl_Testimonials_Widget::format_content( $content, $widget_number, $atts );
	if ( $char_limit ) {
		$content = Axl_Testimonials_Widget::testimonials_truncate( $content, $char_limit, $content_more );
		$content = force_balance_tags( $content );
	}

	$content = apply_filters( 'tw_content', $content, $widget_number, $testimonial, $atts );
	$content = make_clickable( $content );

	//Prepand Title to content if "Hide Title Above Content?" is active from Testimonials Widget settings.
	if ( $do_title && ! is_single() ) {
		$testimonial_title 		= $testimonial['testimonial_source'];
		$testimonial_title_html	= '<span class="list-title">' . $testimonial_title . '</span><br/>';
		$content 				= $testimonial_title_html . $content;
	}

	if ( ! $use_quote_tag ) {
		?>
		<blockquote><?php echo $content; ?></blockquote>
		<?php
	} else {
		?>
		<q><?php echo $content; ?></q>
		<?php
	}
}
?>
