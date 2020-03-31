<?php
global $tw_template_args;

$atts = $tw_template_args['atts'];

$number    = $atts['number'];
$show_date = $atts['show_date'];

$args = array(
	'posts_per_page' => $number,
	'no_found_rows' => true,
	'post_status' => 'publish',
	'ignore_sticky_posts' => true,
	'post_type' => Axl_Testimonials_Widget::PT,
);
$args = apply_filters( 'tw_recent_testimonials_args', $args );

$r = new WP_Query( $args );
if ( $r->have_posts() ) {
	echo '<ul>';
	while ( $r->have_posts() ) {
		$r->the_post();
		echo '<li>';

		$title = get_the_title() ? get_the_title() : get_the_ID();
		echo '<a href="' . get_permalink() . '">' . $title . '</a>';

		if ( $show_date ) {
			echo ' <span class="post-date">' . get_the_date() . '</span>';
		}

		echo '</li>';
	}

	echo '</ul>';
}

// Reset the global $the_post as this query will have stomped on it
wp_reset_postdata();
?>
