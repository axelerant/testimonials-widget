<?php
global $tw_template_args;

$atts        = $tw_template_args['atts'];
$testimonial = $tw_template_args['testimonial'];
$post_id     = $testimonial['post_id'];

$do_company  = ! $atts['hide_company'] && ! empty( $testimonial['testimonial_company'] );
$do_image    = ! $atts['hide_image'] && ! empty( $testimonial['testimonial_image'] );
$do_location = ! $atts['hide_location'] && ! empty( $testimonial['testimonial_location'] );
$do_source   = ! $atts['hide_source'] && ! empty( $testimonial['testimonial_source'] );
$do_title    = ! $atts['hide_title'] && ! empty( $testimonial['testimonial_title'] );

$link = '';
if ( $do_source ) {
	$link .= Axl_Testimonials_Widget_Premium::get_template_part( 'span', 'author' );
}

if ( $do_title && ! empty( $link ) ) {
	$link .= Axl_Testimonials_Widget_Premium::get_template_part( 'span', 'join-title' );
}

if ( $do_title ) {
	$link .= Axl_Testimonials_Widget_Premium::get_template_part( 'span', 'job-title' );
}

if ( $do_company && ! empty( $link ) ) {
	$link .= Axl_Testimonials_Widget_Premium::get_template_part( 'span', 'join-company' );
}

if ( $do_company ) {
	$link .= Axl_Testimonials_Widget_Premium::get_template_part( 'span', 'company' );
}

if ( $do_location && ! empty( $link ) ) {
	$link .= Axl_Testimonials_Widget_Premium::get_template_part( 'span', 'join-location' );
}

if ( $do_location ) {
	$link .= Axl_Testimonials_Widget_Premium::get_template_part( 'span', 'location' );
}

if ( ! empty( $link ) ) {
	$link = preg_replace( "#\r|\n#", '', $link );
}

if ( empty( $link ) ) {
	return;
}

$image = '';
if ( $do_image ) {
	$image = Axl_Testimonials_Widget_Premium::get_template_part( 'testimonial', 'image' );
}

$link_before = Axl_Testimonials_Widget_Premium::get_template_part( 'link', 'before' );
$link_after  = Axl_Testimonials_Widget_Premium::get_template_part( 'link', 'after' );
?>
<!-- <?php echo Axl_Testimonials_Widget::ID; ?>:<?php echo $post_id; ?>: -->
<li class="testimonials-widget-testimonial"><?php echo $image . $link_before . $link . $link_after; ?></li>
