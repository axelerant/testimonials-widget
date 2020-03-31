<?php
global $tw_template_args;

$atts         = $tw_template_args['atts'];
$char_limit   = $atts['char_limit'];
$content      = $tw_template_args['content'];
$content_bare = strip_tags( $content );

$link_before = Axl_Testimonials_Widget_Premium::get_template_part( 'link', 'before' );
$link_after  = Axl_Testimonials_Widget_Premium::get_template_part( 'link', 'after' );

$ellipsis = '';
if ( ! empty( $char_limit ) && strlen( $content_bare ) > $char_limit ) {
	$ellipsis = apply_filters( 'twp_more_ellipsis', esc_html__( '…', 'testimonials-widget-premium' ) );
}

$close_quote    = Axl_Testimonials_Widget::$tag_close_quote;
$read_more_text = apply_filters( 'twp_more_text', esc_html__( 'Read more', 'testimonials-widget-premium' ) );

?>
<?php echo $ellipsis . $close_quote . ' ' . $link_before . $read_more_text . $link_after; ?>
