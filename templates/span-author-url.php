<?php
global $tw_template_args;

$testimonial = $tw_template_args['testimonial'];

$author = empty( $testimonial['testimonial_author'] ) ? $testimonial['testimonial_source'] : $testimonial['testimonial_author'];
?>
<span class="author"><a href="<?php echo $testimonial['testimonial_url']; ?>" rel="nofollow"><?php echo $author; ?></a></span>
