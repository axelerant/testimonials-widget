<?php
global $tw_template_args;

$atts = $tw_template_args['atts'];

$c = ! empty( $atts['count'] ) ? '1' : '0';
$d = ! empty( $atts['dropdown'] ) ? '1' : '0';
$h = ! empty( $atts['hierarchical'] ) ? '1' : '0';

$cat_args = array(
	'orderby' => 'name',
	'show_count' => $c,
	'hierarchical' => $h,
);

$use_cpt_taxonomy = tw_get_option( 'use_cpt_taxonomy', false );
if ( $use_cpt_taxonomy ) {
	$cat_args['taxonomy'] = Axl_Testimonials_Widget::$cpt_category;
}

if ( $d ) {
	$cat_args['show_option_none'] = esc_html__( 'Select Testimonials Category', 'testimonials-widget' );

	wp_dropdown_categories( apply_filters( 'tw_categories_dropdown_args', $cat_args ) );
	?>
	<script type='text/javascript'>
	/* <![CDATA[ */
	var dropdown = document.getElementById("cat");
	function onCatChange() {
		if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
			location.href = "<?php echo home_url(); ?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
		}
	}
	dropdown.onchange = onCatChange;
	/* ]]> */
	</script>
	<?php
} else {
	?>
	<ul>
	<?php
		$cat_args['title_li'] = '';

		wp_list_categories( apply_filters( 'tw_categories_args', $cat_args ) );
	?>
	</ul>
	<?php
}

?>
