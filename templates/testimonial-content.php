<?php
global $at_template_args;

extract( $at_template_args );

$char_limit    = $atts['char_limit'];
$content_more  = apply_filters( 'testimonials_widget_content_more', esc_html__( '…', 'testimonials-widget' ) );
$content_more .= Testimonials_Widget::$tag_close_quote;
$do_content    = ! $atts['hide_content'] && ! empty( $testimonial['testimonial_content'] );
$use_quote_tag = $atts['use_quote_tag'];

if ( $do_content ) {
	$content = $testimonial['testimonial_content'];
	$content = Testimonials_Widget::format_content( $content, $widget_number, $atts );
	if ( $char_limit ) {
		$content = Testimonials_Widget::testimonials_truncate( $content, $char_limit, $content_more );
		$content = force_balance_tags( $content );
	}

	$content = apply_filters( 'testimonials_widget_content', $content, $widget_number, $testimonial, $atts );
	$content = make_clickable( $content );

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
