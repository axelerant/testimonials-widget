<?php
global $tw_template_args;

$testimonial = $tw_template_args['testimonial'];

$source = empty( $testimonial['testimonial_author'] ) ? $testimonial['testimonial_source'] : $testimonial['testimonial_author'];
?>
<span class="author"><a href="mailto:<?php _e( $testimonial['testimonial_email'] ); ?>"><?php _e( $source ); ?></a></span>
