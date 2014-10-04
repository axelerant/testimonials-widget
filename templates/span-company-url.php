<?php
global $tw_template_args;

$testimonial = $tw_template_args['testimonial'];
?>
<span class="company"><a href="<?php _e( $testimonial['testimonial_url'] ); ?>" rel="nofollow"><?php _e( $testimonial['testimonial_company'] ); ?></a></span>
