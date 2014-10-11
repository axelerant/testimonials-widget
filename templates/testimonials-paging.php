<?php
global $tw_template_args;

$atts    = $tw_template_args['atts'];
$prepend = $tw_template_args['prepend'];

if ( is_home() || 1 === Testimonials_Widget::$max_num_pages ) {
	return;
}

$class = ( $prepend ) ? 'prepend' : 'append';
$paged = ( $atts['paged'] ) ? $atts['paged'] : 1;

if ( ! function_exists( 'wp_pagenavi' ) ) {
	$paging = '<div class="alignleft">';

	if ( 1 < $paged ) {
		$laquo   = apply_filters( 'tw_previous_posts_link_text', esc_html__( '&laquo;', 'testimonials-widget' ) );
		$paging .= get_previous_posts_link( $laquo, $paged );
	}

	$paging .= '</div>';
	$paging .= '<div class="alignright">';
	if ( $paged != Testimonials_Widget::$max_num_pages ) {
		$raquo   = apply_filters( 'tw_next_posts_link_text', esc_html__( '&raquo;', 'testimonials-widget' ) );
		$paging .= get_next_posts_link( $raquo, Testimonials_Widget::$max_num_pages );
	}

	$paging .= '</div>';
} else {
	$args = array(
		'echo' => false,
		'query' => Testimonials_Widget::$wp_query,
	);
	$args = apply_filters( 'tw_wp_pagenavi', $args );

	$paging = wp_pagenavi( $args );
}

?>
<div class="paging <?php echo $class; ?>"><?php echo $paging; ?></div>
