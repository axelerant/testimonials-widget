<?php
global $tw_template_args;

$testimonial = $tw_template_args['testimonial'];

$source = empty( $testimonial['testimonial_author'] ) ? $testimonial['testimonial_source'] : $testimonial['testimonial_author'];
?>
<span class="author"><a href="<?php _e( $testimonial['testimonial_url'] ); ?>" rel="nofollow"><?php _e( $source ); ?></a></span>
