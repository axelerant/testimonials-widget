<?php
global $tw_template_args;

$testimonial = $tw_template_args['testimonial'];

$email = $testimonial['testimonial_email'];
$email = make_clickable( $email );
?>
<span class="email"><?php echo $email; ?></span>
