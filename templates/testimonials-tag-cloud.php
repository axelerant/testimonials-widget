<?php
global $tw_template_args;

$atts = $tw_template_args['atts'];

echo '<div class="tagcloud">';

$args = array(
	'taxonomy' => $atts['taxonomy'],
);
wp_tag_cloud( apply_filters( 'tw_tag_cloud_args', $args ) );

echo "</div>\n";
?>
