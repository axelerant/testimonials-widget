<?php
global $tw_template_args;

extract( $tw_template_args );
?>
<span class="email"><?php echo make_clickable( $testimonial['testimonial_email'] ); ?></span>
