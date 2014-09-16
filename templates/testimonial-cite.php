<?php
global $at_template_args;

extract( $at_template_args );
extract( $testimonial );

$do_company    = ! $atts['hide_company'] && ! empty( $testimonial_company );
$do_email      = ! $atts['hide_email'] && ! empty( $testimonial_email ) && is_email( $testimonial_email );
$do_location   = ! $atts['hide_location'] && ! empty( $testimonial_location );
$do_source     = ! $atts['hide_source'] && ! empty( $testimonial_source );
$do_title      = ! $atts['hide_title'] && ! empty( $testimonial_title );
$do_url        = ! $atts['hide_url'] && ! empty( $testimonial_url );
$use_quote_tag = $atts['use_quote_tag'];

$cite     = '';
$done_url = false;

if ( $do_source && $do_email ) {
	$cite .= '<span class="author">';
	$cite .= '<a href="mailto:' . $testimonial_email . '">';
	if ( empty( $testimonial_author ) ) {
		$cite .= $testimonial_source;
	} else {
		$cite .= $testimonial_author;
	}

	$cite .= '</a>';
	$cite .= '</span>';
} elseif ( $do_source && ! $do_company && $do_url ) {
	$done_url = true;

	$cite .= '<span class="author">';
	$cite .= '<a href="' . $testimonial_url . '" rel="nofollow">';
	if ( empty( $testimonial_author ) ) {
		$cite .= $testimonial_source;
	} else {
		$cite .= $testimonial_author;
	}

	$cite .= '</a>';
	$cite .= '</span>';
} elseif ( $do_source ) {
	$cite .= '<span class="author">';
	if ( empty( $testimonial_author ) ) {
		$cite .= $testimonial_source;
	} else {
		$cite .= $testimonial_author;
	}

	$cite .= '</span>';
} elseif ( $do_email ) {
	$cite .= '<span class="email">';
	$cite .= make_clickable( $testimonial_email );
	$cite .= '</span>';
}

if ( $do_title && $cite ) {
	$cite .= '<span class="join-title"></span>';
}

if ( $do_title ) {
	$cite .= '<span class="job-title">';
	$cite .= $testimonial_title;
	$cite .= '</span>';
}

if ( ( $do_company || ( $do_url && ! $done_url ) ) && $cite ) {
	$cite .= '<span class="join"></span>';
}

if ( $do_company && $do_url ) {
	$cite .= '<span class="company">';
	$cite .= '<a href="' . $testimonial_url . '" rel="nofollow">';
	$cite .= $testimonial_company;
	$cite .= '</a>';
	$cite .= '</span>';
} elseif ( $do_company ) {
	$cite .= '<span class="company">';
	$cite .= $testimonial_company;
	$cite .= '</span>';
} elseif ( $do_url && ! $done_url ) {
	$cite .= '<span class="url">';
	$cite .= make_clickable( $testimonial_url );
	$cite .= '</span>';
}

if ( $do_location && $cite ) {
	$cite .= '<span class="join-location"></span>';
}

if ( $do_location ) {
	$cite .= '<span class="location">';
	$cite .= $testimonial_location;
	$cite .= '</span>';
}

$cite = apply_filters( 'testimonials_widget_cite_html', $cite, $testimonial, $atts );

if ( empty( $cite ) ) {
	return;
}

if ( ! $use_quote_tag ) {
	?>

	<div class="credit"><?php echo $cite; ?></div>

	<?php
} else {
	?>

	<cite><?php echo $cite; ?></cite>

	<?php
}
?>
