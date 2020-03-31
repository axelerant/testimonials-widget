<?php
global $tw_template_args;

$atts        = $tw_template_args['atts'];
$testimonial = $tw_template_args['testimonial'];

$do_company  = ! $atts['hide_company'] && ! empty( $testimonial['testimonial_company'] );
$do_email    = ! $atts['hide_email'] && ! empty( $testimonial['testimonial_email'] ) && is_email( $testimonial['testimonial_email'] );
$do_location = ! $atts['hide_location'] && ! empty( $testimonial['testimonial_location'] );
$do_source   = ! $atts['hide_source'] && ! empty( $testimonial['testimonial_source'] );
$do_title    = ! $atts['hide_title'] && ! empty( $testimonial['testimonial_title'] );
$do_url      = ! $atts['hide_url'] && ! empty( $testimonial['testimonial_url'] );

$use_quote_tag = $atts['use_quote_tag'];

$cite     = '';
$done_url = false;

if ( $do_source && $do_email ) {
	$cite .= Axl_Testimonials_Widget::get_template_part( 'span', 'author-email' );
} elseif ( $do_source && ! $do_company && $do_url ) {
	$done_url = true;

	$cite .= Axl_Testimonials_Widget::get_template_part( 'span', 'author-url' );
} elseif ( $do_source ) {
	$cite .= Axl_Testimonials_Widget::get_template_part( 'span', 'author' );
} elseif ( $do_email ) {
	$cite .= Axl_Testimonials_Widget::get_template_part( 'span', 'email' );
}

if ( $do_title && $cite ) {
	$cite .= Axl_Testimonials_Widget::get_template_part( 'span', 'join-title' );
}

if ( $do_title ) {
	$cite .= Axl_Testimonials_Widget::get_template_part( 'span', 'job-title' );
}

if ( ( $do_company || ( $do_url && ! $done_url ) ) && $cite ) {
	$cite .= Axl_Testimonials_Widget::get_template_part( 'span', 'join-company' );
}

if ( $do_company && $do_url ) {
	$cite .= Axl_Testimonials_Widget::get_template_part( 'span', 'company-url' );
} elseif ( $do_company ) {
	$cite .= Axl_Testimonials_Widget::get_template_part( 'span', 'company' );
} elseif ( $do_url && ! $done_url ) {
	$cite .= Axl_Testimonials_Widget::get_template_part( 'span', 'url' );
}

if ( $do_location && $cite ) {
	$cite .= Axl_Testimonials_Widget::get_template_part( 'span', 'join-location' );
}

if ( $do_location ) {
	$cite .= Axl_Testimonials_Widget::get_template_part( 'span', 'location' );
}

if ( ! empty( $cite ) ) {
	$cite = preg_replace( "#\r|\n#", '', $cite );
}

$cite = apply_filters( 'tw_cite_html', $cite, $testimonial, $atts );

if ( empty( $cite ) ) {
	return;
}

if ( ! $use_quote_tag ) {
	?>
	<div class="credit"><?php echo $cite; ?></div>
	<?php
} else {
	?>
	<cite><?php echo $cite; ?></cite>
	<?php
}
?>
