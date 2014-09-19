<?php
global $at_template_args;

extract( $at_template_args );

echo '<div class="tagcloud">';

$args = array(
	'taxonomy' => $atts['taxonomy'],
);
wp_tag_cloud( apply_filters( 'tw_tag_cloud_args', $args ) );

echo "</div>\n";
?>
