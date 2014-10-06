<?php
global $tw_template_args;

$testimonial = $tw_template_args['testimonial'];

$email  = $testimonial['testimonial_email'];
$source = empty( $testimonial['testimonial_author'] ) ? $testimonial['testimonial_source'] : $testimonial['testimonial_author'];
?>
<span class="author"><a href="mailto:<?php _e( $email ); ?>"><?php _e( $source ); ?></a></span>
