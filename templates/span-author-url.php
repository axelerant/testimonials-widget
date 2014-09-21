<?php
global $tw_template_args;

extract( $tw_template_args );
?>
<span class="author"><a href="<?php echo $testimonial['testimonial_url']; ?>" rel="nofollow"><?php echo empty( $testimonial['testimonial_author'] ) ? $testimonial['testimonial_source'] : $testimonial['testimonial_author']; ?></a></span>
