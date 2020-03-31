<?php
global $tw_template_args;

$testimonial = $tw_template_args['testimonial'];

if ( ! empty( $tw_template_args['atts'] ) ) {
	$atts               = $tw_template_args['atts'];
	$nofollow_read_more = $atts['nofollow_read_more'];
} else {
	$nofollow_read_more = tw_get_option( 'nofollow_read_more' );
}

if ( empty( $testimonial['testimonial_read_more_link'] ) ) {
	$permalink = get_permalink( $testimonial['post_id'] );
} else {
	$permalink = $testimonial['testimonial_read_more_link'];
}

$no_follow = '';
if ( $nofollow_read_more ) {
	$no_follow = 'rel="nofollow"';
}

$author = ! empty( $testimonial['testimonial_author'] ) ? $testimonial['testimonial_author'] : $testimonial['testimonial_source'];
?>
<a href="<?php echo $permalink;?>" title="<?php echo apply_filters( 'twp_link_title_text', esc_html__( 'Complete testimonial by ', 'testimonials-widget-premium' ) ); echo $author; ?>" <?php echo $no_follow; ?> class="more-link">
