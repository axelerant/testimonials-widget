<?php
global $tw_template_args;

extract( $tw_template_args );

$id = Testimonials_Widget::ID;
if ( is_null( $widget_number ) ) {
	$class = $id;
	if ( $is_list ) {
		$class .= ' listing';
	}
} else {
	$id_base = $id . $widget_number;
	$class   = $id . ' ' . $id_base;
}
?>

<div class="<?php echo $class; ?>">
