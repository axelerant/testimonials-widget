<?php
/**
Testimonials Widget Premium
Copyright (C) 2015 Axelerant

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

require_once TWP_DIR_INC . 'class-testimonials-widget-premium-antispam.php';

if ( class_exists( 'Axl_Testimonials_Widget_Premium_Form' ) ) {
	return;
}


class Axl_Testimonials_Widget_Premium_Form {
	const ID = 'testimonials-widget-premium-form';

	private static $mail_attachment;

	public static $default;
	public static $errors = false;
	public static $form_base;
	public static $form_options = array();


	public function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		add_shortcode( 'testimonialswidgetpremium_form', array( $this, 'testimonials_form' ) );
		add_shortcode( 'testimonials_form', array( $this, 'testimonials_form' ) );

		self::$default              = Axl_Testimonials_Widget_Settings::$default;
		self::$default['show_code'] = false;
	}


	public function admin_init() {
		if ( current_user_can( 'activate_plugins' ) ) {
			add_post_type_support( Axl_Testimonials_Widget::PT, 'custom-fields' );
		}

		if ( ! Axl_Testimonials_Widget::do_load() ) {
			if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
				return;
			}
		}

		add_filter( 'tw_sections', array( $this, 'sections' ) );
		add_filter( 'tw_settings', array( $this, 'settings' ) );
		add_filter( 'tw_widget_options', array( $this, 'widget_options' ) );
	}


	public function init() {
		add_action( 'tw_styles', array( $this, 'styles' ) );
		add_action( 'wp_footer', array( $this, 'script_form_error' ), 999 );
	}


	public function widgets_init() {
		require_once TWP_DIR_INC . 'class-testimonials-widget-premium-form-widget.php';

		register_widget( 'Axl_Testimonials_Widget_Premium_Form_Widget' );
	}


	public function widget_options( $options ) {
		foreach ( $options as $id => $parts ) {
			if ( 'form' == $parts['section'] ) {
				unset( $options[ $id ] );
			}
		}

		return $options;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function form_options() {
		self::$form_options['post_excerpt'] = array(
			'title' => esc_html__( 'Testimonial Summary', 'testimonials-widget-premium' ),
			'validate' => 'sanitize_text_field,stripslashes',
			'placeholder' => esc_html__( 'Awesome Effort!', 'testimonials-widget-premium' ),
		);

		self::$form_options['meta_rating'] = array(
			'title' => esc_html__( 'Rating', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Rating', 'testimonials-widget-premium' ),
			'type' => 'rating',
		);

		self::$form_options['post_content'] = array(
			'title' => esc_html__( 'Complete Testimonial', 'testimonials-widget-premium' ),
			'type' => 'textarea',
			'validate' => 'required,wp_kses_post,stripslashes',
			'placeholder' => esc_html__( 'I loved…', 'testimonials-widget-premium' ),
		);

		$current_user = wp_get_current_user();

		self::$form_options['post_title'] = array(
			'title' => esc_html__( 'Your Name', 'testimonials-widget-premium' ),
			'validate' => 'required,sanitize_text_field,stripslashes',
			'std' => ! empty( $current_user->data->display_name ) ? $current_user->data->display_name : null,
		);

		self::$form_options['meta_author'] = array(
			'title' => esc_html__( 'Author', 'testimonials-widget-premium' ),
			'validate' => 'sanitize_text_field,stripslashes',
		);

		self::$form_options['meta_title'] = array(
			'title' => esc_html__( 'Job Title', 'testimonials-widget-premium' ),
			'validate' => 'sanitize_text_field,stripslashes',
		);

		self::$form_options['meta_location'] = array(
			'title' => esc_html__( 'Location', 'testimonials-widget-premium' ),
			'validate' => 'sanitize_text_field,stripslashes',
		);

		self::$form_options['meta_company'] = array(
			'title' => esc_html__( 'Company', 'testimonials-widget-premium' ),
			'validate' => 'sanitize_text_field,stripslashes',
		);

		self::$form_options['meta_email'] = array(
			'title' => esc_html__( 'Email', 'testimonials-widget-premium' ),
			'validate' => 'sanitize_email',
			'std' => ! empty( $current_user->data->user_email ) ? $current_user->data->user_email : null,
		);

		self::$form_options['meta_url'] = array(
			'title' => esc_html__( 'Website', 'testimonials-widget-premium' ),
			'validate' => 'esc_url',
			'std' => ! empty( $current_user->data->user_url ) ? $current_user->data->user_url : null,
		);

		$item_reviewed = tw_get_option( 'item_reviewed' );

		self::$form_options['meta_item'] = array(
			'title' => esc_html__( 'Testimonial References…', 'testimonials-widget-premium' ),
			'validate' => 'sanitize_text_field,stripslashes',
			'std' => $item_reviewed,
		);

		$item_reviewed_url = tw_get_option( 'item_reviewed_url' );

		self::$form_options['meta_item_url'] = array(
			'title' => esc_html__( 'Item URL', 'testimonials-widget-premium' ),
			'validate' => 'esc_url',
			'std' => $item_reviewed_url,
		);

		self::$form_options['meta_read_more_link'] = array(
			'title' => esc_html__( 'Read More Link', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Alternative read more link destination.', 'testimonials-widget-premium' ),
			'validate' => 'esc_url',
		);

		self::$form_options['tags_input'] = array(
			'title' => esc_html__( 'Testimonial Tags', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Separate tags with commas.', 'testimonials-widget-premium' ),
			'validate' => 'strip_tags,stripslashes',
		);

		self::$form_options['featured_image'] = array(
			'type' => 'file',
			'title' => esc_html__( 'Photo by Upload', 'testimonials-widget-premium' ),
			'validate' => '',
		);

		self::$form_options['featured_image_url'] = array(
			'title' => esc_html__( 'Photo by URL', 'testimonials-widget-premium' ),
			'validate' => 'url',
		);

		$hide_are_you_human = tw_get_option( 'hide_are_you_human' );
		if ( ! $hide_are_you_human ) {
			$title  = esc_html__( 'What is the sum of %d and %d?', 'testimonials-widget-premium' );
			$digit1 = mt_rand( 1, 9 );
			$digit2 = mt_rand( 1, 9 );
			$title  = sprintf( $title, $digit1, $digit2 );
			$sum1   = $digit1 + $digit2;
			twp_session_set( 'hpsc_sum1', $sum1 );

			$sum2 = twp_session_get( 'hpsc_sum2' );
			if ( false === $sum2 || empty( $_POST ) ) {
				twp_session_set( 'hpsc_sum2', $sum1 );
			}

			self::$form_options['are_you_human'] = array(
				'title' => sprintf( '<span class="required emphasize">%s</span>', $title ),
				'validate' => 'absint',
			);
		}

		self::$form_options['ID'] = array(
			'type' => 'hidden',
			'validate' => 'absint',
		);

		self::$form_options['attach_id'] = array(
			'type' => 'hidden',
			'validate' => 'absint',
		);

		self::$form_options['post_type'] = array(
			'type' => 'hidden',
			'std' => Axl_Testimonials_Widget::PT,
		);

		self::$form_options['hpsc'] = array(
			'title' => esc_html__( 'Please leave blank', 'testimonials-widget-premium' ),
		);

		$hpsc_session                       = self::get_hpsc_session();
		self::$form_options['hpsc_session'] = array(
			'title' => esc_html__( 'Please don\'t modify', 'testimonials-widget-premium' ),
			'std' => $hpsc_session,
		);

		self::$form_options['post_author'] = array(
			'type' => 'hidden',
			'validate' => 'absint',
		);

		self::$form_options['post_category'] = array(
			'type' => 'hidden',
			'validate' => 'absint',
		);

		self::$form_options = apply_filters( 'twp_form_options', self::$form_options );

		foreach ( self::$form_options as $id => $parts ) {
			$parts['id']               = $id;
			self::$form_options[ $id ] = wp_parse_args( $parts, self::$default );
		}
	}


	public static function get_hpsc_session( $refresh = false ) {
		$hpsc_session = ! $refresh ? twp_session_get( 'hpsc_session' ) : false;
		if ( false === $hpsc_session ) {
			$hpsc_session = uniqid();
			twp_session_set( 'hpsc_session', $hpsc_session );
		}

		return $hpsc_session;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.LongVariable)
	 * @SuppressWarnings(PHPMD.Superglobals)
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function show_form( $atts, $validated = null ) {
		global $tw_template_args;

		$tw_template_args = compact( 'atts', 'validated' );

		$content = Axl_Testimonials_Widget_Premium::get_template_part( 'testimonial', 'form' );
		$content = str_replace( Axl_Testimonials_Widget_Settings::ID, self::$form_base, $content );

		return $content;
	}


	public static function script_form_error() {
		if ( ! self::$errors ) {
			return;
		}

		$errors    = self::$errors;
		$keys      = array_keys( $errors );
		$error     = $keys[0];
		$form_base = self::$form_base;

		$script = <<<EOD
<script type="text/javascript">
jQuery(document).ready( function() {
	{$form_base}_error = jQuery('#{$error}').focus();
});
</script>
EOD;
		echo $script;
	}


	public static function set_instance() {
		$instance        = Axl_Testimonials_Widget::add_instance();
		self::$form_base = Axl_Testimonials_Widget_Premium::SLUG . $instance;

		return self::$form_base;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.ExitExpression)
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function testimonials_form( $atts ) {
		nocache_headers();

		$instance = self::set_instance();

		if ( empty( self::$form_options ) ) {
			self::form_options();
		}

		$atts = wp_parse_args( $atts, Axl_Testimonials_Widget::get_defaults() );
		$atts = Axl_Testimonials_Widget_Settings::validate_settings( $atts );

		$atts['type'] = self::ID;
		$content      = null;
		$validated    = null;
		$valid_submit = true;

		if ( ! empty( $_POST[ self::$form_base ] ) && wp_verify_nonce( $_POST[ __CLASS__ ], 'add' ) ) {
			$hide_are_you_human = tw_get_option( 'hide_are_you_human' );

			$input = $_POST[ self::$form_base ];
			$input = wp_parse_args( $input, $atts );

			if ( ! empty( $input['attach_id'] ) ) {
				$input['featured_image'] = $input['attach_id'];
			}

			if ( ! empty( $_FILES ) ) {
				$file_arr = $_FILES[ self::$form_base ];
				if ( ! empty( $file_arr['name']['featured_image'] ) ) {
					$file = $file_arr['name']['featured_image'];

					$input['featured_image'] = $file;
				}
			} elseif ( ! empty( $input['featured_image_url'] ) ) {
				$input['featured_image'] = $input['featured_image_url'];
			}

			$validated = Axl_Testimonials_Widget_Settings::validate_settings( $input, self::$form_options, true );

			$twp_as       = new Axl_Testimonials_Widget_Premium_Antispam( $validated['input'] );
			$valid_submit = $twp_as->is_valid();
			if ( ! $valid_submit && empty( $validated['errors'] ) ) {
				$msg_as = Axl_Testimonials_Widget_Premium::get_template_part( 'testimonial', 'form-thank-you' );

				if ( ! $hide_are_you_human ) {
					$msg_as .= Axl_Testimonials_Widget_Premium::get_template_part( 'testimonial', 'form-anti-spam' );
				}

				$content = $msg_as;

				if ( current_user_can( 'manage_options' ) ) {
					$text  = '<h2>';
					$text .= esc_html__( 'Antispam Debug Help Text', 'testimonials-widget-premium' );
					$text .= '</h2>';
					$text .= '<h4>';
					$text .= esc_html__( 'This section is shown only to website managers', 'testimonials-widget-premium' );
					$text .= '</h4>';
					$text .= '<p>';
					$text .= self::text_debug_antispam();
					$text .= '</p>';

					$content .= $text;
				}
			} else {
				$validated['input']['hpsc_session'] = self::get_hpsc_session( true );
			}

			if ( $valid_submit && empty( $validated['errors'] ) ) {
				$validated['input'] = self::save_form( $validated['input'] );

				$thank_you_page = tw_get_option( 'thank_you_page' );
				if ( ! empty( $thank_you_page ) ) {
					$thank_you_link = $thank_you_page;
					if ( preg_match( '#^\d+$#', $thank_you_link ) ) {
						$link = get_permalink( $thank_you_link );
					} else {
						$do_http = true;
						if ( 0 === strpos( $thank_you_link, '/' ) ) {
							$do_http = false;
						}

						if ( $do_http && 0 === preg_match( '#https?://#', $thank_you_link ) ) {
							$thank_you_link = 'http://' . $thank_you_link;
						}

						$link = $thank_you_link;
					}

					$validated['input']['thank_you_page_link'] = $link;
				}
			}

			if ( ! $hide_are_you_human ) {
				unset( $validated['input']['are_you_human'] );
			}
		}

		if ( empty( $content ) ) {
			$content = self::show_form( $atts, $validated );
		}

		Axl_Testimonials_Widget::call_scripts_styles( array(), $atts, $instance );

		return $content;
	}


	public static function text_debug_antispam() {
		$text = __(
			'<p>Debugging why testimonial submissions don\'t work are tricky. There\'s at least 9 of the following reasons to work through.</p>
			<ul>
			<li>The testimonials submission page is cached</li>
			<li>Akismet – Comment spam</li>
			<li>BBCode is Spam – Review the testimonial contents for BBCode links</li>
			<li>Validate Submission IP – Validity check for used IP address</li>
			<li>Use Anti-spam Regular Expressions – Predefined and custom patterns by plugin hook</li>
			<li>Check Local Spam Database – Already marked as spam? Yes? No?</li>
			<li>Check Public Anti-spam Database – Matching the IP address with Tornevall</li>
			<li>Block Testimonials From Specific Countries – Filtering the requests depending on country</li>
			<li>Allow Testimonials Only In Certain Language – Detection and approval in specified language</li>
			</ul>
			<p>The quick fixes for testimonial submissions not working are caching and location related. Try these first.</p>
			<ol>
			<li>Make sure that the testimonials submission page isn\'t cached</li>
			<li>Deactivate the "Validate Submission IP", "Check Public Anti-spam Database", and "Block Testimonials From Specific Countries" tests one at a time and test testimonial form submissions again.</li>
			</ol>
			<p>If you\'re still having trouble, then you\'ll need to invoke <code>testmode</code>.</p>
			<p>Call your testimonials submission page with <code>?testmode=1</code> appended to the URL like <code><a href="%1$s">%2$s</a></code>.</p>
			<p>After the form submission, review the debug information to the reference below to determine which anti-spam measures are kicking in.</p>
			<p><strong>Anti-spam reference</strong></p>
			<ul>
			<li><code>session_okay</code>, if <code>true</code> is present, then sessions work</li>
			<li><code>valid_agent</code>, is a valid <code>$_SERVER[\'HTTP_USER_AGENT\']</code> present?</li>
			<li><code>valid_hpsc</code>, was a honey-pot spam check code was provided?</li>
			<li><code>valid_referer</code>, was submission was sent from your website?</li>
			<li><code>valid_hpsc_session</code>, was honey-pot spam check code was correct?</li>
			<li><code>valid_human</code>, was math based check was correct?</li>
			<li><code>Akismet</code>
			<ul>
			<li>"", as in blank, <strong>no</strong> spam was detected</li>
			<li>`isCommentSpam`, is comment spam</li>
			</ul></li>
			<li><code>Antispam_Bee</code>, provides the anti-spam check that was detected
			<ul>
			<li>"", as in blank, <strong>no</strong> spam was detected</li>
			<li><code>empty</code>, content or IP address was blank</li>
			<li><code>bbcode</code>, BBCode was detected</li>
			<li><code>server</code>, IP address was faked</li>
			<li><code>regexp</code>, regular expression spam was detected</li>
			<li><code>localdb</code>, similar entries have been makred as spam locally</li>
			<li><code>dnsbl</code>, submission IP is blacklisted</li>
			<li><code>country</code>, submission country is a spammer</li>
			<li><code>lang</code>, submission content was not in the allowed language</li>
			</ul></li>
			</ul>
			<p>Depending upon the submission result, you might need to adjust the form\'s anti-spam settings via <a href="%3$s">Testimonials &gt; Settings</a>, <em>Form</em> tab, <em>Anti-Spam Options</em> section.</p>
			<p>If you\'re still having trouble, please screenshot every step of your testimonial submission and result, and then email them to <a href="mailto:support@axelerant.com">support@axelerant.com</a>.</p>',
			'testimonials-widget-premium'
		);

		$url           = get_permalink() . '?testmode=1';
		$settings_link = get_admin_url() . 'edit.php?post_type=' . Axl_Testimonials_Widget::PT . '&page=' . Axl_Testimonials_Widget_Settings::ID;
		$text          = sprintf( $text, $url, $url, $settings_link );

		return $text;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function save_form( $input ) {
		$post_author   = ! empty( $input['post_author'] ) ? $input['post_author'] : tw_get_option( 'post_author' );
		$post_category = ! empty( $input['post_category'] ) ? $input['post_category'] : tw_get_option( 'post_category' );
		if ( ! preg_match( '#^\d+$#', $post_category ) ) {
			$post_category = self::get_cat_id( $post_category );
		}

		$post_category = array( $post_category );

		$data = array(
			'ID' => $input['ID'],
			'post_author' => $post_author,
			'post_category' => $post_category,
			'post_content' => $input['post_content'],
			'post_excerpt' => $input['post_excerpt'],
			'post_status' => tw_get_option( 'post_status' ),
			'post_title' => $input['post_title'],
			'post_type' => $input['post_type'],
			'tags_input' => $input['tags_input'],
		);

		$insert = false;
		if ( empty( $input['ID'] ) ) {
			$post_id     = wp_insert_post( $data );
			$input['ID'] = $post_id;
			$insert      = true;
		} else {
			$post_id = wp_update_post( $data );
			Axl_Testimonials_Widget_Premium_Cache::clear_cache( $post_id );
		}

		update_post_meta( $post_id, 'testimonials-widget-author', $input['meta_author'] );
		update_post_meta( $post_id, 'testimonials-widget-company', $input['meta_company'] );
		update_post_meta( $post_id, 'testimonials-widget-email', $input['meta_email'] );
		update_post_meta( $post_id, 'testimonials-widget-item', $input['meta_item'] );
		update_post_meta( $post_id, 'testimonials-widget-item-url', $input['meta_item_url'] );
		update_post_meta( $post_id, 'testimonials-widget-location', $input['meta_location'] );
		update_post_meta( $post_id, 'testimonials-widget-rating', $input['meta_rating'] ? $input['meta_rating'] : Axl_Testimonials_Widget_Premium::$rating_none );
		update_post_meta( $post_id, 'testimonials-widget-read-more-link', $input['meta_read_more_link'] );
		update_post_meta( $post_id, 'testimonials-widget-title', $input['meta_title'] );
		update_post_meta( $post_id, 'testimonials-widget-url', $input['meta_url'] );

		do_action( 'twp_form_save', $post_id, $input );

		// @codingStandardsIgnoreStart
		update_post_meta( $post_id, 'testimonials-widget-premium-form-request', print_r( $_REQUEST, true ) );
		update_post_meta( $post_id, 'testimonials-widget-premium-form-server', print_r( $_SERVER, true ) );
		// @codingStandardsIgnoreEnd

		if ( ! empty( $_FILES ) ) {
			$file_arr = $_FILES[ self::$form_base ];
			$file     = $file_arr['name']['featured_image'];
		}

		$use_url = false;
		if ( empty( $file ) && ! empty( $input['featured_image_url'] ) ) {
			$file    = $input['featured_image_url'];
			$use_url = true;
			unset( $input['featured_image_url'] );
		}

		if ( ! empty( $file ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';

			// delete prior attachment
			if ( $input['attach_id'] ) {
				$attach_id = $input['attach_id'];
				wp_delete_attachment( $attach_id, true );
				unset( $input['attach_id'] );
			}

			if ( ! $use_url ) {
				$tmp_file = $file_arr['tmp_name']['featured_image'];
			} else {
				$tmp_file = $file;
			}

			$file_contents = file_get_contents( $tmp_file );
			$file_move     = wp_upload_bits( $file, null, $file_contents );
			$filename      = $file_move['file'];

			self::$mail_attachment = $filename;

			$metadata    = wp_read_image_metadata( $filename );
			$title       = ! empty( $metadata['title'] ) ? $metadata['title'] : sanitize_title_with_dashes( $file );
			$caption     = ! empty( $metadata['caption'] ) ? $metadata['caption'] : '';
			$wp_filetype = wp_check_filetype( $file, null );
			$attachment  = array(
				'post_excerpt' => $caption,
				'post_mime_type' => $wp_filetype['type'],
				'post_status' => 'inherit',
				'post_title' => $title,
			);
			$attach_id   = wp_insert_attachment( $attachment, $filename, $post_id );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
			wp_update_attachment_metadata( $attach_id, $attach_data );
			update_post_meta( $post_id, '_thumbnail_id', $attach_id );

			update_post_meta( $post_id, 'SERVER', $_SERVER );
			update_post_meta( $post_id, 'REQUEST', $_REQUEST );

			$input['attach_id'] = $attach_id;
		}

		if ( $insert ) {
			self::send_mail_notification( $post_id );
		}

		return $input;
	}


	// @ref antispam-bee/antispam_bee.php
	public static function send_mail_notification( $post_id ) {
		$atts = Axl_Testimonials_Widget::get_defaults();

		if ( empty( $atts['send_mail_notification'] ) ) {
			return $post_id;
		}

		$testimonial = get_post( $post_id );
		if ( empty( $testimonial ) ) {
			return $post_id;
		}

		$meta      = get_post_custom( $post_id );
		$blog_name = get_bloginfo( 'name' );
		$subject   = sprintf(
			'%s: %s',
			$blog_name,
			esc_html__( 'New testimonial submission', 'testimonials-widget-premium' )
		);

		$body  = '';
		$body .= sprintf(
			"%s %s\r\n\r\n",
			esc_html__( 'New testimonial submission on', 'testimonials-widget-premium' ),
			$blog_name
		);

		$body .= sprintf(
			"%s %s\r\n",
			esc_html__( 'Author', 'testimonials-widget-premium' ),
			strip_tags( $testimonial->post_title )
		);

		if ( ! empty( $meta['testimonials-widget-author'][0] ) ) {
			$body .= sprintf(
				"%s: %s\r\n",
				self::$form_options['meta_author']['title'],
				strip_tags( $meta['testimonials-widget-author'][0] )
			);
		}

		if ( ! empty( $meta['testimonials-widget-title'][0] ) ) {
			$body .= sprintf(
				"%s: %s\r\n",
				self::$form_options['meta_title']['title'],
				strip_tags( $meta['testimonials-widget-title'][0] )
			);
		}

		if ( ! empty( $meta['testimonials-widget-location'][0] ) ) {
			$body .= sprintf(
				"%s: %s\r\n",
				self::$form_options['meta_location']['title'],
				strip_tags( $meta['testimonials-widget-location'][0] )
			);
		}

		$email = false;
		if ( ! empty( $meta['testimonials-widget-email'][0] ) ) {
			$email = strip_tags( $meta['testimonials-widget-email'][0] );
			$body .= sprintf(
				"%s: %s\r\n",
				self::$form_options['meta_email']['title'],
				$email
			);
		}

		if ( ! empty( $meta['testimonials-widget-company'][0] ) ) {
			$body .= sprintf(
				"%s: %s\r\n",
				self::$form_options['meta_company']['title'],
				strip_tags( $meta['testimonials-widget-company'][0] )
			);
		}

		if ( ! empty( $meta['testimonials-widget-url'][0] ) ) {
			$body .= sprintf(
				"%s: %s\r\n",
				self::$form_options['meta_url']['title'],
				strip_tags( $meta['testimonials-widget-url'][0] )
			);
		}

		if ( ! empty( $meta['testimonials-widget-rating'][0] ) && Axl_Testimonials_Widget_Premium::$rating_none != $meta['testimonials-widget-rating'][0] ) {
			$body .= sprintf(
				"%s: %s\r\n",
				self::$form_options['meta_rating']['title'],
				strip_tags( $meta['testimonials-widget-rating'][0] ) . '/' . Axl_Testimonials_Widget::$rating_max
			);
		}

		if ( ! empty( $meta['testimonials-widget-item'][0] ) ) {
			$body .= sprintf(
				"%s: %s\r\n",
				self::$form_options['meta_item']['title'],
				strip_tags( $meta['testimonials-widget-item'][0] )
			);
		}

		if ( ! empty( $meta['testimonials-widget-item-url'][0] ) ) {
			$body .= sprintf(
				"%s: %s\r\n",
				self::$form_options['meta_item_url']['title'],
				strip_tags( $meta['testimonials-widget-item-url'][0] )
			);
		}

		if ( ! empty( $meta['testimonials-widget-read-more-link'][0] ) ) {
			$body .= sprintf(
				"%s: %s\r\n",
				self::$form_options['meta_read_more_link']['title'],
				strip_tags( $meta['testimonials-widget-read-more-link'][0] )
			);
		}

		$args = array(
			'fields' => 'names',
		);
		$tags = wp_get_post_tags( $post_id, $args );
		if ( ! empty( $tags ) ) {
			$body .= sprintf(
				"%s: %s\r\n",
				self::$form_options['tags_input']['title'],
				implode( ', ', $tags )
			);
		}

		$categories = wp_get_post_categories( $post_id, $args );
		if ( ! empty( $categories ) ) {
			$body .= sprintf(
				"%s: %s\r\n",
				esc_html__( 'Category', 'testimonials-widget-premium' ),
				implode( ', ', $categories )
			);
		}

		if ( ! empty( $testimonial->post_excerpt ) ) {
			$body .= sprintf(
				"%s: %s\r\n\r\n",
				self::$form_options['post_excerpt']['title'],
				strip_tags( $testimonial->post_excerpt )
			);
		}

		$body .= sprintf(
			"%s\r\n\r\n\r\n",
			strip_tags( $testimonial->post_content )
		);

		$body .= sprintf(
			"%s: %s\r\n",
			__( 'Review & Publish', 'testimonials-widget-premium' ),
			admin_url( 'post.php?action=edit&post=' .$post_id )
		);
		$body .= sprintf(
			"%s: %s\r\n\r\n",
			esc_html__( 'Pending Testimonials', 'testimonials-widget-premium' ),
			admin_url( 'edit.php?post_status=pending&post_type=testimonials-widget' )
		);
		$body .= sprintf(
			"%s\r\n%s\r\n",
			esc_html__( 'Notification message by Testimonials Widget Premium', 'testimonials-widget-premium' ),
			esc_html__( 'https://store.axelerant.com/downloads/best-wordpress-testimonials-plugin-testimonials-premium/', 'testimonials-widget-premium' )
		);

		$headers = array();
		if ( ! empty( $email ) && is_email( $email ) ) {
			$headers[] = "From: {$testimonial->post_title} <{$email}>\r\n";
		}

		$to = tw_get_option( 'mail_recipient' );
		if ( empty( $to ) ) {
			$to = get_bloginfo( 'admin_email' );
		}

		$to         = apply_filters( 'twp_send_mail_notification_to', $to, $post_id );
		$subject    = apply_filters( 'twp_send_mail_notification_subject', $subject, $post_id );
		$body       = apply_filters( 'twp_send_mail_notification_body', $body, $post_id );
		$headers    = apply_filters( 'twp_send_mail_notification_headers', $headers, $post_id );
		$attachment = apply_filters( 'twp_send_mail_notification_attachment', self::$mail_attachment, $post_id );

		wp_mail( $to, $subject, $body, $headers, $attachment );

		return $post_id;
	}


	public function sections( $sections ) {
		$sections['form'] = esc_html__( 'Form', 'testimonials-widget-premium' );

		return $sections;
	}


	public function settings( $settings ) {
		$settings['form_title'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Title', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'For form widget.', 'testimonials-widget-premium' ),
			'std' => esc_html__( 'Submit a Testimonial', 'testimonials-widget-premium' ),
			'validate' => 'wp_kses_post',
		);

		$settings['form_title_link'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Title Link', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'For form widget. URL or Post ID to link widget title to. Ex: 123 or http://example.com', 'testimonials-widget-premium' ),
			'validate' => 'wp_kses_data',
		);

		$settings['form_target'] = array(
			'section' => 'form',
			'title' => esc_html__( 'URL Target', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'For form widget. Add target to Title Link; leave blank if none.', 'testimonials-widget-premium' ),
			'validate' => 'term',
		);

		$settings['form_header'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Form Header', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Leave blank for default "Add a Testimonial" or "Edit Testimonial" headings.', 'testimonials-widget-premium' ),
			'validate' => 'wp_kses_post',
		);

		$settings['post_status'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Default Testimonial Status', 'testimonials-widget-premium' ),
			'type' => 'select',
			'std' => 'pending',
			'choices' => array(
				'publish' => 'Published',
				'pending' => 'Pending review',
				'draft' => 'Draft',
				'private' => 'Private',
			),
			'widget' => 0,
		);

		$args           = array(
			'fields' => array( 'ID', 'display_name' ),
			'role' => 'administrator',
		);
		$administrators = apply_filters( 'tw_cache_get', false, $args );
		if ( false === $administrators ) {
			$administrators = get_users( $args );
			$administrators = apply_filters( 'tw_cache_set', $administrators, $args );
		}

		$args['role'] = 'editor';
		$editors      = apply_filters( 'tw_cache_get', false, $args );
		if ( false === $editors ) {
			$editors = get_users( $args );
			$editors = apply_filters( 'tw_cache_set', $editors, $args );
		}

		$temp_users = array_merge( $administrators, $editors );
		$users      = array();
		foreach ( $temp_users as $user ) {
			$users[ $user->ID ] = $user->display_name;
		}

		$settings['post_author'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Default Testimonial Author', 'testimonials-widget-premium' ),
			'type' => 'select',
			'choices' => $users,
			'std' => 1,
			'widget' => 0,
		);

		$args = array(
			'type' => Axl_Testimonials_Widget::PT,
		);

		$temp_categories = get_categories( 'category' );
		$categories      = array();
		foreach ( $temp_categories as $category ) {
			$categories[ $category->cat_ID ] = $category->name;
		}

		$settings['post_category'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Default Testimonial Category', 'testimonials-widget-premium' ),
			'type' => 'select',
			'choices' => $categories,
			'std' => 1,
		);

		$settings['disallow_edit'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Disallow Edit After Submission?', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		$settings['include_form_css'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Include Form CSS?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Not all themes include form related CSS. This includes a very basic form stylesheet.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'widget' => 0,
		);

		$settings['send_mail_notification'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Send Mail Notification?', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'widget' => 0,
			'desc' => esc_html__( 'Enable emailed admin notification of user submitted testimonial.', 'testimonials-widget-premium' ),
		);

		$emails = array();
		foreach ( $users as $id => $name ) {
			$user                        = get_userdata( $id );
			$emails[ $user->user_email ] = "$name &lt;{$user->user_email}&gt;";
		}

		$settings['mail_recipient'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Notification Recipient', 'testimonials-widget-premium' ),
			'type' => 'select',
			'choices' => $emails,
			'widget' => 0,
		);

		$settings['thank_you_page'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Thank You Page Link', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'URL, path, or post ID of thank you page. Ex: http://example.com/thank-you, /thank-you, or 123', 'testimonials-widget-premium' ),
			'validate' => 'wp_kses_data',
		);

		$settings['form_expand_begin'] = array(
			'section' => 'form',
			'desc' => esc_html__( 'Additional Options', 'testimonials-widget-premium' ),
			'type' => 'expand_begin',
		);

		$settings['fields_to_show_heading'] = array(
			'section' => 'form',
			'desc' => esc_html__( 'Fields', 'testimonials-widget-premium' ),
			'type' => 'heading',
		);

		$settings['hide_form_header'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Hide Form Header?', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'Don\'t display form header above adding or editing user submitted testimonials.', 'testimonials-widget-premium' ),
		);

		$settings['hide_meta_author'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Hide Author Field?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Hide form field input for variable `meta_author`.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
		);

		$settings['hide_meta_company'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Hide Company Field?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Hide form field input for variable `meta_company`.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		$settings['hide_meta_email'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Hide Email Field?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Hide form field input for variable `meta_email`.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		$settings['hide_post_excerpt'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Hide Excerpt Field?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Hide form field input for variable `post_excerpt`.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		$settings['hide_featured_image'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Hide Image Field?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Hides both image upload and URL fields.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		$settings['hide_featured_image_url'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Hide Image Upload via URL?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Hide form field input for variable `featured_image_url`. Expects image URL.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true,esc_url',
		);

		$settings['hide_meta_item'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Hide Item Referenced Field?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Hide form field input for variable `meta_item`.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		$settings['hide_meta_item_url'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Hide Item URL Field?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Hide form field input for variable `meta_item_url`.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
		);

		$settings['hide_meta_title'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Hide Job Title Field?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Hide form field input for variable `meta_title`.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		$settings['hide_meta_location'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Hide Location Field?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Hide form field input for variable `meta_location`.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		$desc = esc_html__( 'Hide form field input for variable `meta_rating`. Expects integer, %1$s to %2$s.', 'testimonials-widget-premium' );

		$settings['hide_meta_rating'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Hide Ratings Field?', 'testimonials-widget-premium' ),
			'desc' => sprintf( $desc, Axl_Testimonials_Widget_Premium::$rating_min, Axl_Testimonials_Widget::$rating_max ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		$settings['hide_meta_read_more_link'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Hide "Read More Link" Field?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Hide form field input for variable `meta_read_more_link`.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
		);

		$settings['hide_reset_button'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Hide Reset Button?', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'Don\'t display form reset button below adding or editing user submitted testimonials.', 'testimonials-widget-premium' ),
		);

		$settings['hide_tags_input'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Hide Tags Field?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Hide form field input for variable `tags_input`.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
		);

		$settings['hide_meta_url'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Hide URL Field?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Hide form field input for variable `meta_url`.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		$settings['antispam_heading'] = array(
			'section' => 'form',
			'desc' => esc_html__( 'Anti-Spam', 'testimonials-widget-premium' ),
			'type' => 'heading',
		);

		$desc = __( 'For many people, <a href="%1$s">Akismet</a> will greatly reduce or even completely eliminate the testimonial spam you get on your site.', 'testimonials-widget-premium' );
		$desc = sprintf( $desc, 'http://wordpress.org/extend/plugins/akismet/' );

		$wordpress_api_key = get_option( 'wordpress_api_key' );
		if ( empty( $wordpress_api_key ) ) {
			$get_key = __( ' If you don\'t have an API key yet, you can get one at <a href="%1$s">Akismet.com</a>.', 'testimonials-widget-premium' );
			$get_key = sprintf( $get_key, 'https://akismet.com/signup/?connect=plugin' );
			$desc   .= $get_key;
		} else {
			$akismet_key = esc_html__( ' Your Akismet API key is "%1$s".', 'testimonials-widget-premium' );
			$akismet_key = sprintf( $akismet_key, $wordpress_api_key );
			$desc       .= $akismet_key;
		}

		$settings['akismet_api_key'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Akismet API Key', 'testimonials-widget-premium' ),
			'desc' => $desc,
			'validate' => 'wp_kses_data',
			'widget' => 0,
		);

		$settings['bbcode_check'] = array(
			'section' => 'form',
			'title' => esc_html__( 'BBCode is Spam', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Review the testimonial contents for BBCode links.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'widget' => 0,
		);

		$settings['advanced_check'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Validate Submission IP', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Validity check for used IP address.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'widget' => 0,
		);

		$settings['regexp_check'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Use Anti-spam Regular Expressions', 'testimonials-widget-premium' ),
			'desc' => __( 'Predefined and custom patterns by <a href="https://gist.github.com/4242142" target="_blank">plugin hook</a>.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'widget' => 0,
		);

		$settings['spam_ip'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Check Local Spam Database', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Already marked as spam? Yes? No?.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'widget' => 0,
		);

		$settings['dnsbl_check'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Check Public Anti-spam Database', 'testimonials-widget-premium' ),
			'desc' => __( 'Matching the IP address with <a href="http://opm.tornevall.org" target="_blank">Tornevall</a>.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'widget' => 0,
		);

		$settings['country_code'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Block Testimonials From Specific Countries', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Filtering the requests depending on country.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'widget' => 0,
		);

		$settings['country_black'] = array(
			'section' => 'form',
			'title' => __( 'Blacklist <a href="http://www.iso.org/iso/country_names_and_code_elements" target="_blank">ISO Codes</a>', 'testimonials-widget-premium' ),
			'widget' => 0,
			'desc' => esc_html__( 'Separate ISO codes with commas. Ex: US,TW,IN', 'testimonials-widget-premium' ),
		);

		$settings['country_white'] = array(
			'section' => 'form',
			'title' => __( 'Whitelist <a href="http://www.iso.org/iso/country_names_and_code_elements" target="_blank">ISO Codes</a>', 'testimonials-widget-premium' ),
			'widget' => 0,
			'desc' => esc_html__( 'Separate ISO codes with commas. Ex: US,TW,IN', 'testimonials-widget-premium' ),
		);

		$settings['translate_api'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Allow Testimonials Only In Certain Language', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Detection and approval in specified language.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'widget' => 0,
		);

		$settings['translate_lang'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Language', 'testimonials-widget-premium' ),
			'type' => 'select',
			'choices' => array(
				'en' => esc_html__( 'English', 'testimonials-widget-premium' ),
				'fr' => esc_html__( 'French', 'testimonials-widget-premium' ),
				'de' => esc_html__( 'German', 'testimonials-widget-premium' ),
				'it' => esc_html__( 'Italian', 'testimonials-widget-premium' ),
				'es' => esc_html__( 'Spanish', 'testimonials-widget-premium' ),
			),
			'std' => 'en',
			'widget' => 0,
			'desc' => esc_html__( 'Allowed language for user submitted testimonials.', 'testimonials-widget-premium' ),
		);

		$settings['hide_are_you_human'] = array(
			'section' => 'form',
			'title' => esc_html__( 'Hide "What is the sum of…?"', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'A simple anti-spam helper.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		$settings['form_expand_end'] = array(
			'section' => 'form',
			'type' => 'expand_end',
		);

		return $settings;
	}


	public static function styles() {
		$include_form_css = tw_get_option( 'include_form_css' );
		if ( $include_form_css ) {
			wp_register_style( 'testimonials-widget-premium-form', Axl_Testimonials_Widget_Premium::$plugin_assets . 'css/testimonials-widget-premium-form.css' );
			wp_enqueue_style( 'testimonials-widget-premium-form' );
		}
	}

	public static function get_cat_id( $cat_name ) {
		$use_cpt_taxonomy = tw_get_option( 'use_cpt_taxonomy', false );

		$type = ! $use_cpt_taxonomy ? 'category' : Axl_Testimonials_Widget::$cpt_category;

		$cat = get_term_by( 'name', $cat_name, $type );
		if ( $cat ) {
			return $cat->term_id;
		}

		return 0;
	}
}


?>
