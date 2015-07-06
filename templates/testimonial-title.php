<?php
global $tw_template_args;

$atts          = $tw_template_args['atts'];
$use_quote_tag = $atts['use_quote_tag'];

$testimonial = $tw_template_args['testimonial'];
$title = $testimonial['testimonial_source'];

if ( ! $use_quote_tag ) {
	?>
	<blockquote><span class="list-title"><?php echo $title; ?></span>
	<?php
} else {
	?>
	<q><span class="list-title"><?php echo $title; ?></span></q>
	<?php
}
?>
