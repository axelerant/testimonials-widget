<?php
global $tw_template_args;

$testimonial = $tw_template_args['testimonial'];
$author      = ! empty( $testimonial['testimonial_author'] ) ? $testimonial['testimonial_author'] : $testimonial['testimonial_source'];
?>
<span class="author"><?php echo $author; ?></span>
