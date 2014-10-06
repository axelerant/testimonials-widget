<?php
global $tw_template_args;

$testimonial = $tw_template_args['testimonial'];

$email  = $testimonial['testimonial_email'];
$source = empty( $testimonial['testimonial_author'] ) ? $testimonial['testimonial_source'] : $testimonial['testimonial_author'];
?>
<span class="author"><a href="mailto:<?php echo $email; ?>"><?php echo $source; ?></a></span>
