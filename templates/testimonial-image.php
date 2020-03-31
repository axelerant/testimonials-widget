<?php
global $tw_template_args;

$testimonial = $tw_template_args['testimonial'];
$link_before = Axl_Testimonials_Widget_Premium::get_template_part( 'link', 'before' );
$link_after  = Axl_Testimonials_Widget_Premium::get_template_part( 'link', 'after' );
?>
<span class="image"><?php echo $link_before . $testimonial['testimonial_image'] . $link_after; ?></span>
