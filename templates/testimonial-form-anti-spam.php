<p>
<?php
$msg_as_human = esc_html__( 'If you\'re a real person, you\'ve been caught in anti-spam check. Please go back to the previous page and check your entries. Then resubmit your testimonial.', 'testimonials-widget-premium' );
$msg_as_human = sprintf( '<span class="required emphasize">%s</span>', $msg_as_human );

echo $msg_as_human;
?>
</p>
