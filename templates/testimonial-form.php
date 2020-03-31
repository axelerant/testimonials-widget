<?php
global $tw_template_args;

$atts      = $tw_template_args['atts'];
$validated = $tw_template_args['validated'];

$input  = isset( $validated['input'] ) ? $validated['input'] : null;
$input  = wp_parse_args( $input, $atts );
$errors = isset( $validated['errors'] ) ? $validated['errors'] : null;

if ( ! empty( $errors ) ) {
	Axl_Testimonials_Widget_Premium_Form::$errors = $errors;
}

$content       = '';
$disallow_edit = false;
$options       = Axl_Testimonials_Widget_Premium_Form::$form_options;

if ( ! empty( $atts['post_author'] ) ) {
	$options['post_author']['std'] = $atts['post_author'];
}

if ( ! empty( $atts['post_category'] ) ) {
	$options['post_category']['std'] = $atts['post_category'];
}

$post_id = ! empty( $input['ID'] ) ? $input['ID'] : null;

$content .= '<div class="' . Axl_Testimonials_Widget_Premium_Form::ID . '">';
$content .= "\n";
if ( empty( $atts['hide_form_header'] ) ) {
	if ( ! empty( $atts['form_header'] ) ) {
		$heading = $atts['form_header'];
	} else {
		$heading = empty( $post_id ) ? esc_html__( 'Add a Testimonial', 'testimonials-widget-premium' ) : esc_html__( 'Edit Testimonial', 'testimonials-widget-premium' );
	}

	$heading = apply_filters( 'twp_form_heading', $heading, $post_id );

	$content .= '<h2>' . $heading . '</h2>';
	$content .= "\n";
}

if ( ! empty( $post_id ) ) {
	$content .= '<p class="moderation">';
	$content .= esc_html__( 'Thank you for your testimonial. It will be published after being approved.', 'testimonials-widget-premium' );

	$disallow_edit = $atts['disallow_edit'];
	if ( ! $disallow_edit ) {
		$content .= esc_html__( ' You may edit it further below.', 'testimonials-widget-premium' );
	}

	$content .= '</p>';
	$content .= "\n";

	if ( is_admin_bar_showing() ) {
		$content .= '<p class="preview ' . $post_id . '">';
		$content .= esc_html__( 'Preview: ', 'testimonials-widget-premium' );
		$content .= '<a href="' . get_permalink( $post_id ) . '" title="' . get_the_title( $post_id ) . '" target="_preview">' . get_the_title( $post_id ) . '</a>';
		$content .= '</p>';
		$content .= "\n";
	}
}

if ( ! $disallow_edit ) {
	$content .= '<form name="' . Axl_Testimonials_Widget_Premium_Form::ID . '" id="' . Axl_Testimonials_Widget_Premium_Form::$form_base . '" method="post" enctype="multipart/form-data">';
	$content .= "\n";

	$testmode = false;
	if ( ! empty( $_REQUEST['testmode'] ) ) {
		$testmode = true;
	}

	$hide_featured_image_url = false;
	foreach ( $options as $id => $parts ) {
		if ( in_array( $parts['type'], array( 'hidden' ) ) ) {
			continue;
		}

		if ( isset( $atts[ 'hide_' . $id ] ) && Aihrus_Settings::is_true( $atts[ 'hide_' . $id ] ) ) {
			if ( 'featured_image' == $id  ) {
				$hide_featured_image_url = true;
			}

			unset( $options[ $id ] );
			continue;
		}

		if ( $hide_featured_image_url && 'featured_image_url' == $id  ) {
			unset( $options[ $id ] );
			continue;
		}

		// Don't show shortcode option names in form output
		$parts['no_code'] = true;

		$value = isset( $input[ $id ] ) ? $input[ $id ] : null;
		if ( ! in_array( $id, array( 'hpsc', 'hpsc_session' ) ) ) {
			if ( is_null( $value ) && $testmode && 'meta_rating' != $id ) {
				$value = $id . '_TEST';
			}

			if ( ! in_array( $id, array( 'meta_rating' ) ) ) {
				$content .= '<p class="' . $id . '">';
			}
		} else {
			if ( ! in_array( $id, array( 'meta_rating' ) ) ) {
				$content .= '<p class="hpsc">';
			}
		}

		if ( ! in_array( $parts['type'], array( 'checkbox', 'radio' ) ) ) {
			$content .= '<label for="' . $id . '">' . $parts['title'];
			if ( false !== stripos( $parts['validate'], 'required' ) ) {
				$content .= '<span class="required">*</span>';
			}

			$content .= '</label>';
		} elseif ( in_array( $parts['type'], array( 'checkbox', 'radio' ) ) ) {
			if ( false !== stripos( $parts['validate'], 'required' ) ) {
				$content .= '<span class="required">*</span>';
			}
		} elseif ( is_null( $input ) ) {
			$value = $parts['std'];
		}

		if ( ! empty( $errors[ $id ] ) ) {
			$content .= '<div class="error">' . $errors[ $id ] . '</div>';
			$content .= "\n";
		}

		$content .= Axl_Testimonials_Widget_Settings::display_setting( $parts, false, $value );

		if ( ! empty( $post_id ) && 'featured_image' == $id  ) {
			$image = get_the_post_thumbnail( $post_id, 'medium' );
			if ( ! empty( $image ) ) {
				$content .= '<p class="image">' . $image . '</p>';
				$content .= "\n";
			}
		}

		if ( ! in_array( $id, array( 'meta_rating' ) ) ) {
			$content .= '</p>';
		}

		$content .= "\n";

		unset( $options[ $id ] );
	}

	if ( empty( $post_id ) ) {
		$submit_text = esc_html__( 'Submit Testimonial', 'testimonials-widget-premium' );
	} else {
		$submit_text = esc_html__( 'Edit Testimonial', 'testimonials-widget-premium' );
	}

	$content .= '
		<p class="form-submit">
			<input name="Submit" type="submit" class="button-primary submit" value="' . $submit_text . '" />
		';

	$hide_reset_button = isset( $atts['hide_reset_button'] ) ? $atts['hide_reset_button'] : 0;
	if ( empty( $hide_reset_button ) ) {
		$content .= '
			<input name="Reset" type="reset" class="button-secondary reset" value="' . esc_html__( 'Reset', 'testimonials-widget-premium' ) . '" />
			';
	}

	$content .= '
		</p>
		';
	$content .= "\n";

	// hidden options
	if ( ! empty( $options ) ) {
		foreach ( $options as $id => $parts ) {
			$value = isset( $input[ $id ] ) ? $input[ $id ] : null;
			if ( in_array( $id, array( 'post_author', 'post_category' ) ) ) {
				$value = $parts['std'];
			}

			$content .= Axl_Testimonials_Widget_Settings::display_setting( $parts, false, $value );
		}
	}
	$content .= wp_nonce_field( 'add', 'Axl_Testimonials_Widget_Premium_Form', true, false );
	$content .= '</form>';
	$content .= "\n";
}

$content .= '</div>';
$content .= "\n";

if ( ! empty( $input['thank_you_page_link'] ) ) {
	$script = <<<EOD
<script type="text/javascript">
window.location.replace("{$input['thank_you_page_link']}");
</script>
EOD;

	$content .= $script;
}

echo $content;
?>
