<?php
global $tw_template_args;

$atts = $tw_template_args['atts'];

$c = ! empty( $atts['count'] ) ? '1' : '0';
$d = ! empty( $atts['dropdown'] ) ? '1' : '0';

add_filter( 'getarchives_where', array( Testimonials_Widget, 'getarchives_where' ), 10, 2 );
add_filter( 'get_archives_link', array( Testimonials_Widget, 'get_archives_link' ), 10, 1 );

if ( $d ) {
	?>
	<select name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'>
		<option value=""><?php echo esc_attr( __( 'Select Month', 'testimonials-widget' ) ); ?></option>
		<?php
		$args = array(
			'type' => 'monthly',
			'format' => 'option',
			'show_post_count' => $c,
		);
		wp_get_archives( apply_filters( 'tw_archives_dropdown_args', $args ) );
		?>
	</select>
	<?php
} else {
	?>
	<ul>
	<?php
		$args = array(
			'type' => 'monthly',
			'show_post_count' => $c,
		);
		wp_get_archives( apply_filters( 'tw_archives_args', $args ) );
	?>
	</ul>
	<?php
}

remove_filter( 'get_archives_link', array( Testimonials_Widget, 'get_archives_link' ), 10, 1 );
remove_filter( 'getarchives_where', array( Testimonials_Widget, 'getarchives_where' ), 10, 2 );
?>
