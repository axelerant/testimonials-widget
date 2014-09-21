<?php
global $tw_template_args;

extract( $tw_template_args );
?>
<span class="url"><?php echo make_clickable( $testimonial['testimonial_url'] ); ?></span>
