<?php
global $tw_template_args;

$testimonial = $tw_template_args['testimonial'];

$url = $testimonial['testimonial_url'];
$url = make_clickable( $url );
?>
<span class="url"><?php echo $url; ?></span>
