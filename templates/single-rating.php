<?php
global $tw_template_args;

$atts        = $tw_template_args['atts'];
$testimonial = $tw_template_args['testimonial'];

$ratings = Axl_Testimonials_Widget_Premium::get_ratings_div( $testimonial['post_id'], $atts['widget_number'] );
?>
<div class="testimonials-widget-premium-ratings">
	<?php echo $ratings; ?>
</div>
