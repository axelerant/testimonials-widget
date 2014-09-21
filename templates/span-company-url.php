<?php
global $tw_template_args;

extract( $tw_template_args );
?>
<span class="company"><a href="<?php echo $testimonial['testimonial_url']; ?>" rel="nofollow"><?php echo $testimonial['testimonial_company']; ?></a></span>
