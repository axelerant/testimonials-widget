<?php
global $tw_template_args;

$atts = $tw_template_args['atts'];
$id   = Axl_Testimonials_Widget_Premium::ID;

switch ( $atts['type'] ) {
	case 'testimonials_links':
		$class = 'links';
		break;

	default:
		$class = '';
		break;
}
?>
<div class="<?php echo $id . ' ' . $class; ?>">
