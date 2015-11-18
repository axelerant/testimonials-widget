<?php
global $tw_template_args;

$is_list       = $tw_template_args['is_list'];
$widget_number = $tw_template_args['widget_number'];

$id = Axl_Testimonials_Widget::ID;
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
