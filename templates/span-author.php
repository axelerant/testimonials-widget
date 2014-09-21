<?php
global $tw_template_args;

extract( $tw_template_args );
?>
<span class="author"><?php echo empty( $testimonial['testimonial_author'] ) ? $testimonial['testimonial_source'] : $testimonial['testimonial_author']; ?></span>
