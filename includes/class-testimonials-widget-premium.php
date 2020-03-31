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

require_once AIHR_DIR_INC . 'class-aihrus-common.php';
require_once TWP_DIR_INC . 'class-testimonials-widget-premium-cache.php';
require_once TWP_DIR_INC . 'class-testimonials-widget-premium-form.php';
require_once TWP_DIR_INC . 'class-testimonials-widget-premium-licensing.php';
require_once TWP_DIR_INC . 'class-testimonials-widget-premium-session.php';
require_once TWP_DIR_INC . 'class-testimonials-widget-premium-template-loader.php';
require_once TWP_DIR_INC . 'class-testimonials-widget-premium-sticky.php';

if ( class_exists( 'Axl_Testimonials_Widget_Premium' ) ) {
	return;
}


class Axl_Testimonials_Widget_Premium extends Aihrus_Common {
	const BASE    = TWP_BASE;
	const ID      = 'testimonials-widget-premium';
	const SLUG    = 'twp_';
	const VERSION = TWP_VERSION;

	private static $unique_args = null;

	public static $class = __CLASS__;
	public static $library_assets;
	public static $notice_key;
	public static $plugin_assets;
	public static $post_types      = array();
	public static $scripts_display = array();
	public static $session         = array();
	public static $sticky;
	public static $template_loader;
	public static $testimonial_count    = 0;
	public static $testimonial_instance = 0;

	public static $rating_best   = 'bestRating';
	public static $rating_min    = 1;
	public static $rating_none   = -1;
	public static $rating_schema = 'http://schema.org/Rating';
	public static $rating_worst  = 'worstRating';
	public static $review_rating = 'reviewRating';

	public static $aggregate_ratings = array();


	public function __construct() {
		parent::__construct();

		self::$library_assets = plugins_url( '/includes/libraries/', dirname( __FILE__ ) );
		self::$library_assets = self::strip_protocol( self::$library_assets );

		self::$plugin_assets = plugins_url( '/assets/', dirname( __FILE__ ) );
		self::$plugin_assets = self::strip_protocol( self::$plugin_assets );

		self::$session = new Axl_Testimonials_Widget_Premium_Session();
		self::$sticky  = new Axl_Testimonials_Widget_Premium_Sticky();

		self::load_options();

		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		add_action( 'init', array( __CLASS__, 'init' ) );

		add_shortcode( 'testimonialswidgetpremium_count', array( __CLASS__, 'testimonials_count' ) );
		add_shortcode( 'testimonialswidgetpremium_link_list', array( __CLASS__, 'testimonials_links' ) );
		add_shortcode( 'testimonials_count', array( __CLASS__, 'testimonials_count' ) );
		add_shortcode( 'testimonials_links', array( __CLASS__, 'testimonials_links' ) );
	}


	public static function admin_menu() {
		add_action( 'tw_settings_add_help_tabs', array( __CLASS__, 'add_help_tabs' ) );
	}


	public static function admin_init() {
		if ( ! self::version_check() ) {
			return;
		}

		global $TW_Premium_Licensing;
		if ( ! $TW_Premium_Licensing->valid_license() ) {
			self::set_notice( 'notice_license', DAY_IN_SECONDS );
		}

		add_action( 'save_post', array( __CLASS__, 'save_post' ), 25 );
		add_filter( 'manage_edit-' . Axl_Testimonials_Widget::PT . '_sortable_columns', array( __CLASS__, 'sortable_columns' ) );
		add_filter( 'plugin_action_links', array( __CLASS__, 'plugin_action_links' ), 10, 2 );
		add_filter( 'request', array( __CLASS__, 'request' ) );
		add_filter( 'tw_columns', array( __CLASS__, 'columns' ) );
		add_filter( 'tw_meta_box', array( __CLASS__, 'meta_box' ) );
		add_filter( 'tw_posts_custom_column', array( __CLASS__, 'posts_custom_column' ), 10, 3 );
		add_filter( 'tw_version', array( __CLASS__, 'version' ) );
	}


	public static function init() {
		load_plugin_textdomain( 'testimonials-widget-premium', false, 'testimonials-widget/languages' );

		add_action( 'do_feed_testimonials', array( __CLASS__, 'feed' ) );
		add_action( 'generate_rewrite_rules', array( __CLASS__, 'generate_rewrite_rules' ) );
		add_action( 'tw_scripts', array( __CLASS__, 'scripts' ) );
		add_action( 'tw_styles', array( __CLASS__, 'styles' ) );
		add_filter( 'posts_where', array( __CLASS__, 'posts_where' ) );
		add_filter( 'the_content_feed', array( __CLASS__, 'feed_image' ) );
		add_filter( 'the_excerpt_rss', array( __CLASS__, 'feed_image' ) );
		add_filter( 'tw_content', array( __CLASS__, 'truncate_content' ), 10, 4 );
		add_filter( 'tw_data', array( __CLASS__, 'data_set' ), 10, 2 );
		add_filter( 'tw_display_setting', array( __CLASS__, 'display_setting' ), 10, 3 );
		add_filter( 'tw_examples_html', array( __CLASS__, 'tw_examples_html' ) );
		add_filter( 'tw_get_testimonials_html', array( __CLASS__, 'get_testimonials_html_controls' ), 10, 10 );
		add_filter( 'tw_get_testimonial_html', array( __CLASS__, 'get_testimonial_html' ), 10, 13 );
		add_filter( 'tw_query_args', array( __CLASS__, 'query_args' ), 10, 2 );
		add_filter( 'tw_slider_widget_options', array( __CLASS__, 'tw_slider_widget_options' ) );
		add_filter( 'tw_testimonials_js', array( __CLASS__, 'bxslider_js' ), 10, 4 );
		add_filter( 'tw_testimonials_js', array( __CLASS__, 'ratings_js' ), 10, 4 );
		add_filter( 'tw_testimonials_js', array( __CLASS__, 'rotate_per_page_js' ), 10, 4 );
		add_filter( 'tw_testimonials_js', array( __CLASS__, 'slider_options_js' ), 10, 4 );
		add_filter( 'tw_testimonial_html_single_content', array( __CLASS__, 'testimonial_html_single_content' ), 10, 3 );
		add_filter( 'tw_used_with_codes', array( __CLASS__, 'used_with_codes' ), 10, 2 );
		add_filter( 'tw_validate_settings', array( __CLASS__, 'validate_settings' ), 10, 2 );
		add_post_type_support( Axl_Testimonials_Widget::PT, 'excerpt' );

		self::$unique_args = array(
			'type' => 'testimonialswidgetpremium_unique',
			'post_id' => null,
		);
	}


	public static function load_options() {
		add_filter( 'tw_sections', array( __CLASS__, 'sections' ) );
		add_filter( 'tw_settings', array( __CLASS__, 'settings' ) );
	}


	public static function version_check() {
		$valid_version = true;
		if ( ! $valid_version ) {
			$deactivate_reason = esc_html__( 'Failed version check', 'testimonials-widget-premium' );
			aihr_deactivate_plugin( self::BASE, TWP_NAME, $deactivate_reason );
			self::check_notices();
		}

		return $valid_version;
	}


	public static function notice_license() {
		$post_type     = Axl_Testimonials_Widget::PT;
		$settings_id   = Axl_Testimonials_Widget_Settings::ID;
		$required_name = TWP_REQ_NAME;
		$purchase_url  = 'https://store.axelerant.com/downloads/best-wordpress-testimonials-plugin-testimonials-premium/';
		$item_name     = TWP_NAME;
		$product_id    = TWP_PRODUCT_ID;
		$license       = tw_get_option( Axl_Testimonials_Widget_Premium::SLUG . 'license_key' );

		aihr_notice_license( $post_type, $settings_id, $required_name, $purchase_url, $item_name, $product_id, $license );
	}


	public static function plugin_action_links( $links, $file ) {
		if ( self::BASE == $file ) {
			array_unshift( $links, Axl_Testimonials_Widget::$settings_link );
		}

		return $links;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function get_unique_args() {
		$cache_per_page               = tw_get_option( 'cache_per_page' );
		self::$unique_args['post_id'] = ( $cache_per_page && ! empty( $GLOBALS['post'] ) ) ? get_the_ID() : -1;

		return self::$unique_args;
	}


	public static function columns( $columns ) {
		$show_excerpt = tw_get_option( 'columns_excerpt' );
		if ( empty( $show_excerpt ) ) {
			$columns['post_excerpt'] = esc_html__( 'Excerpt', 'testimonials-widget-premium' );
		}

		$show_rating = tw_get_option( 'columns_rating' );
		if ( empty( $show_rating ) ) {
			$columns['testimonials-widget-rating'] = esc_html__( 'Rating', 'testimonials-widget-premium' );
		}

		$show_item = tw_get_option( 'columns_item' );
		if ( empty( $show_item ) ) {
			$columns['testimonials-widget-item'] = esc_html__( 'Item Referenced', 'testimonials-widget-premium' );
		}

		$show_item_url = tw_get_option( 'columns_item_url' );
		if ( empty( $show_item_url ) ) {
			$columns['testimonials-widget-item-url'] = esc_html__( 'Item URL', 'testimonials-widget-premium' );
		}

		$show_read_more = tw_get_option( 'columns_read_more' );
		if ( empty( $show_read_more ) ) {
			$columns['testimonials-widget-read-more-link'] = esc_html__( 'Read More Link', 'testimonials-widget-premium' );
		}

		return $columns;
	}


	public static function posts_custom_column( $result, $column, $post_id ) {
		switch ( $column ) {
			case 'testimonials-widget-rating':
				$rating = get_post_meta( $post_id, $column, true );
				$result = self::get_ratings( $rating, $post_id, null, false );
				break;

			case 'testimonials-widget-item':
				$result = get_post_meta( $post_id, $column, true );
				break;

			case 'testimonials-widget-item-url':
			case 'testimonials-widget-read-more-link':
				$url = get_post_meta( $post_id, $column, true );
				if ( ! empty( $url ) && 0 === preg_match( '#https?://#', $url ) ) {
					$url = 'http://' . $url;
				}

				$result = make_clickable( $url );
				break;

			case 'post_excerpt':
				global $post;

				$result = $post->post_excerpt;

				$show_content_excerpt = tw_get_option( 'show_content_excerpt' );
				if ( $show_content_excerpt && empty( $result ) ) {
					$char_limit = apply_filters( 'excerpt_length', 55 );
					$char_limit = $char_limit * 6;

					$result = Axl_Testimonials_Widget::clean_string( $post->post_content );
					$result = Axl_Testimonials_Widget::truncate( $result, $char_limit );
				}
				break;
		}

		return $result;
	}


	public static function meta_box( $fields ) {
		$rating = array(
			'name' => esc_html__( 'Rating', 'testimonials-widget-premium' ),
			'id' => 'testimonials-widget-rating',
			'type' => 'twp_ratings',
			'desc' => esc_html__( 'Rating of item referenced by testimonial.', 'testimonials-widget-premium' ),
		);

		$fields[] = $rating;

		$item = array(
			'name' => esc_html__( 'Item Referenced', 'testimonials-widget-premium' ),
			'id' => 'testimonials-widget-item',
			'type' => 'text',
			'desc' => esc_html__( 'Name of item referenced by testimonial.', 'testimonials-widget-premium' ),
			'default' => tw_get_option( 'item_reviewed' ),
		);

		$fields[] = $item;

		$item_url = array(
			'name' => esc_html__( 'Item URL', 'testimonials-widget-premium' ),
			'id' => 'testimonials-widget-item-url',
			'type' => 'text',
			'desc' => esc_html__( 'URL of item referenced by testimonial.', 'testimonials-widget-premium' ),
			'default' => tw_get_option( 'item_reviewed_url' ),
		);

		$fields[] = $item_url;

		$read_more_link = array(
			'name' => esc_html__( 'Read More Link', 'testimonials-widget-premium' ),
			'id' => 'testimonials-widget-read-more-link',
			'type' => 'text',
			'desc' => esc_html__( 'Alternate destination for "Read more" link. Leave blank for normal linking to full testimonial.', 'testimonials-widget-premium' ),
		);

		$fields[] = $read_more_link;

		return $fields;
	}


	public static function testimonials_count( $atts ) {
		$atts = wp_parse_args( $atts, Axl_Testimonials_Widget::get_defaults() );
		$atts = Axl_Testimonials_Widget_Settings::validate_settings( $atts );

		$atts['limit'] = -1;
		$atts['type']  = 'testimonials_count';

		$instance              = Axl_Testimonials_Widget::add_instance();
		$atts['widget_number'] = $instance;
		$atts['fields']        = 'ids';

		$testimonials = array();

		$count = apply_filters( 'tw_cache_get', false, $atts );
		if ( false === $count ) {
			$args         = Axl_Testimonials_Widget::get_query_args( $atts );
			$testimonials = new WP_Query( $args );

			$count = $testimonials->post_count;
			$count = apply_filters( 'tw_cache_set', $count, $atts );
		}

		Axl_Testimonials_Widget::call_scripts_styles( $testimonials, $atts, $instance );

		return $count;
	}


	public static function query_args( $args, $atts ) {
		global $wpdb;

		$args['post_type'] = $atts['post_type'];

		$no_cache = ! empty( $atts['no_cache'] );
		if ( $no_cache ) {
			$args['no_cache'] = 1;
		}

		// when using post__not_in, our selection logic is reversed
		if ( empty( $args['post__not_in'] ) ) {
			$post__not_in = array();
		} else {
			$post__not_in = $args['post__not_in'];
		}

		$unique = $atts['unique'];
		if ( $unique ) {
			$unique_args = self::get_unique_args();

			$selected = apply_filters( 'tw_cache_get', false, $unique_args );
			if ( false !== $selected && is_array( $selected ) ) {
				$selected     = self::array_values_recursive( $selected );
				$post__not_in = array_merge( $post__not_in, $selected );
			}
		}

		$maximum_length = $atts['maximum_length'];
		$minimum_length = $atts['minimum_length'];
		$query_base     = 'SELECT ID FROM ' . $wpdb->posts . ' WHERE post_status = "publish" AND post_type = "' . $args['post_type'] . '"';
		$query          = $query_base;

		// post_content might include HTML which arbitrarily increases length
		if ( $maximum_length ) {
			$query .= ' AND CHAR_LENGTH(post_content) > ' . $maximum_length;
		}

		if ( $minimum_length ) {
			$query .= ' AND CHAR_LENGTH(post_content) < ' . $minimum_length;
		}

		if ( $maximum_length || $minimum_length ) {
			$results      = $wpdb->get_col( $query );
			$post__not_in = array_merge( $post__not_in, $results );
		}

		if ( empty( $args['post__in'] ) ) {
			$post__in       = array();
			$uses_intersect = false;

			$require_excerpt = $atts['require_excerpt'];
			if ( $require_excerpt ) {
				$query    = $query_base;
				$query   .= ' AND post_excerpt NOT LIKE ""';
				$results  = $wpdb->get_col( $query );
				$post__in = $results;
			}

			$require_image = $atts['require_image'];
			if ( $require_image ) {
				$query = "
					SELECT m.post_id
					FROM {$wpdb->postmeta} m
					WHERE m.meta_key = '_thumbnail_id'
					AND m.meta_value > 0
					";

				$results = $wpdb->get_col( $query );
				if ( empty( $post__in ) ) {
					$post__in = $results;
				} else {
					$post__in       = array_intersect( $post__in, $results );
					$uses_intersect = true;
				}
			}

			$require_ratings = $atts['require_ratings'];
			if ( $require_ratings && 'false' != $require_ratings ) {
				$match = $atts['strict_rating'] ? '=' : '>=';
				$query = "
					SELECT m.post_id
					FROM {$wpdb->postmeta} m
					WHERE m.meta_key = 'testimonials-widget-rating'
					AND m.meta_value {$match} {$require_ratings}
					";

				$results = $wpdb->get_col( $query );
				if ( empty( $post__in ) ) {
					$post__in = $results;
				} else {
					$post__in       = array_intersect( $post__in, $results );
					$uses_intersect = true;
				}
			}

			if ( ! empty( $post__in ) ) {
				$post__in         = array_unique( $post__in );
				$args['post__in'] = $post__in;
			} elseif ( empty( $post__in ) && $uses_intersect ) {
				$args['post_type'] = '__no_matching_testimonials_found__';
			}
		}

		if ( ! empty( $post__not_in ) ) {
			$post__not_in         = array_unique( $post__not_in );
			$args['post__not_in'] = $post__not_in;
		}

		return $args;
	}


	public static function testimonial_html_single_content( $content, $testimonial, $atts ) {
		global $tw_template_args;

		$atts = wp_parse_args( $atts, Axl_Testimonials_Widget::get_defaults( true ) );
		$atts = Axl_Testimonials_Widget_Settings::validate_settings( $atts );

		$tw_template_args = compact( 'content', 'testimonial', 'atts' );

		$excerpt = '';
		if ( empty( $atts['hide_excerpt'] ) && ! empty( $testimonial['testimonial_excerpt'] ) ) {
			$excerpt = self::get_template_part( 'single', 'excerpt' );
		}

		$rating = '';
		if ( empty( $atts['hide_ratings'] ) && ! self::empty_rating( $testimonial['testimonial_rating'] ) ) {
			$rating = self::get_template_part( 'single', 'rating' );
		}

		return $rating . $excerpt . $content;
	}


	public static function counter() {
		$instance_check = Axl_Testimonials_Widget::get_instance();

		if ( self::$testimonial_instance == $instance_check ) {
			self::$testimonial_count++;
		} else {
			self::$testimonial_instance = $instance_check;
			self::$testimonial_count    = 0;
		}

		return self::$testimonial_count;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function get_testimonial_html( $html, $testimonial, $atts, $is_list, $is_first, $widget_number, $div_open, $image, $quote, $cite, $extra, $bottom_text, $div_close ) {
		// see Axl_Testimonials_Widget::get_testimonial_html for default processing
		$post_id = ! empty( $testimonial['post_id'] ) ? $testimonial['post_id'] : false;
		if ( ! $post_id ) {
			return $html;
		}

		global $tw_template_args;

		$tw_template_args = compact( 'html', 'testimonial', 'atts', 'is_list', 'is_first', 'widget_number', 'div_open', 'image', 'quote', 'cite', 'extra', 'bottom_text', 'div_close' );

		if ( $is_list ) {
			// append even/odd to div_open
			$search   = ' list';
			$replace  = ' list ';
			$replace .= ( 0 == self::counter() % 2 ) ? 'even' : 'odd';
			$div_open = str_replace( $search, $replace, $div_open );
		}

		$do_image = ! $atts['hide_image'] && ! empty( $testimonial['testimonial_image'] );
		if ( $do_image && $atts['link_image'] && 'get_single' != $atts['type'] ) {
			$image = self::get_template_part( 'testimonial', 'image' );
		}

		$hide_ratings = $atts['hide_ratings'];
		if ( ! empty( $atts['type'] ) && 'get_single' == $atts['type'] ) {
			$hide_ratings = true;
		}

		$ratings = '';
		if ( empty( $hide_ratings ) && ! self::empty_rating( $testimonial['testimonial_rating'] ) ) {
			$ratings = self::get_ratings_div( $post_id, $atts['widget_number'] );
		}

		$hide_excerpt = $atts['hide_excerpt'];
		if ( ! empty( $atts['type'] ) && 'get_single' == $atts['type'] ) {
			$hide_excerpt = true;
		}

		if ( empty( $hide_excerpt ) && ! empty( $testimonial['testimonial_excerpt'] ) ) {
			$excerpt                   = $testimonial['testimonial_excerpt'];
			$excerpt                   = Axl_Testimonials_Widget::format_content( $excerpt, $widget_number, $atts );
			$atts['excerpt_read_more'] = 1;
			$excerpt                   = self::truncate_content( $excerpt, $widget_number, $testimonial, $atts );
			$excerpt                   = make_clickable( $excerpt );

			$tw_template_args = compact( 'html', 'testimonial', 'atts', 'is_list', 'is_first', 'widget_number', 'div_open', 'image', 'quote', 'cite', 'extra', 'bottom_text', 'div_close', 'excerpt' );

			$use_quote_tag = $atts['use_quote_tag'];
			if ( empty( $use_quote_tag ) ) {
				$quote = self::get_template_part( 'testimonial', 'blockquote' );
			} else {
				$quote = self::get_template_part( 'testimonial', 'quote' );
			}
		}

		$html = $div_open
			. $image
			. $ratings
			. $quote
			. $cite
			. $extra
			. $bottom_text
			. $div_close;

		return $html;
	}


	public static function get_ratings( $rating, $post_id = null, $name = null, $editable = true ) {
		$id_name  = 'raty_' . $post_id;
		$path_img = self::$plugin_assets . 'images/';

		$read_only = '';
		if ( empty( $editable ) ) {
			$read_only = ",\nreadOnly: true";
		}

		$cancel = '';
		if ( ! empty( $editable ) ) {
			$cancel = ",\ncancel: true";
		}

		if ( empty( $rating ) ) {
			if ( empty( $editable ) ) {
				$rating = self::$rating_none;
			} else {
				$rating = tw_get_option( 'default_rating' );
			}
		}

		$score = '';
		if ( $rating ) {
			$score = ",\nscore: {$rating}";
		}

		$score_name = '';
		if ( ! is_null( $name ) ) {
			$score_name = ",\nscoreName: '{$name}'";
		}

		self::scripts( array( 'hide_ratings' => false ) );

		self::$scripts_display[] = '<script type="text/javascript">';
		self::$scripts_display[] = "
			jQuery(document).ready( function() {
				jQuery('#{$id_name}').raty({
					path: '{$path_img}'
					{$read_only}
					{$cancel}
					{$score}
					{$score_name}
				});
			});";
		self::$scripts_display[] = '</script>';

		$ratings = self::get_ratings_div( $post_id );

		return $ratings;
	}


	public static function testimonials_links( $atts ) {
		$atts = wp_parse_args( $atts, Axl_Testimonials_Widget::get_defaults() );
		$atts = Axl_Testimonials_Widget_Settings::validate_settings( $atts );

		if ( get_query_var( 'paged' ) ) {
			$atts['paged'] = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$atts['paged'] = get_query_var( 'page' );
		} else {
			$atts['paged'] = 1;
		}

		$atts['type'] = 'testimonials_links';

		$instance              = Axl_Testimonials_Widget::add_instance();
		$atts['widget_number'] = $instance;

		$testimonials = array();

		$content = apply_filters( 'tw_cache_get', false, $atts );
		if ( false === $content ) {
			$testimonials = Axl_Testimonials_Widget::get_testimonials( $atts );
			$content      = self::get_testimonials_html_links( $testimonials, $atts );
			$content      = apply_filters( 'tw_cache_set', $content, $atts );
		}

		Axl_Testimonials_Widget::call_scripts_styles( $testimonials, $atts, $instance );

		return $content;
	}


	public static function get_testimonials_html_links( $testimonials, $atts ) {
		global $tw_template_args;

		$atts['enable_schema'] = false;
		$tw_template_args      = compact( 'testimonials', 'atts' );

		$div_open = self::get_template_part( 'testimonials', 'open' );

		$paging     = Axl_Testimonials_Widget_Settings::is_true( $atts['paging'] );
		$pre_paging = '';
		if ( $paging || 'before' === strtolower( $atts['paging'] ) ) {
			$pre_paging = Axl_Testimonials_Widget::get_testimonials_paging( $atts );
		}

		$links_open = self::get_template_part( 'links', 'open' );

		if ( empty( $testimonials ) && ! $atts['hide_not_found'] ) {
			$testimonials = array(
				array( 'testimonial_content' => esc_html__( 'No testimonials found', 'testimonials-widget-premium' ) ),
			);

			Axl_Testimonials_Widget::set_not_found( true );
		} else {
			Axl_Testimonials_Widget::set_not_found();
		}

		$target = preg_match( '#^\w+$#', $atts['target'] ) ? $atts['target'] : false;

		$testimonial_content = '';
		foreach ( $testimonials as $testimonial ) {
			$content = self::get_testimonial_html_link( $testimonial, $atts );
			if ( $target ) {
				$content = links_add_target( $content, $target );
			}

			$testimonial_content .= apply_filters( 'twp_html_link', $content, $testimonial, $atts );
		}

		$post_paging = '';
		if ( $paging || 'after' === strtolower( $atts['paging'] ) ) {
			$post_paging = Axl_Testimonials_Widget::get_testimonials_paging( $atts, false );
		}

		$links_close = self::get_template_part( 'links', 'close' );
		$div_close   = self::get_template_part( 'testimonials', 'close' );

		$html = $div_open
			. $pre_paging
			. $links_open
			. $testimonial_content
			. $links_close
			. $post_paging
			. $div_close;

		return $html;
	}


	public static function get_testimonial_html_link( $testimonial, $atts ) {
		global $tw_template_args;

		$tw_template_args = compact( 'testimonial', 'atts' );

		$link = self::get_template_part( 'links', 'link' );

		return $link;
	}


	public static function validate_settings( $input, $errors = array(), $do_errors = false ) {
		if ( ! empty( $input['no_cache'] ) && ! empty( $input['unique'] ) ) {
			unset( $input['no_cache'] );
			$errors['no_cache'] = esc_html__( 'If you want to prevent duplicates, caching must be enabled', 'testimonials-widget-premium' );
		}

		if ( empty( $do_errors ) ) {
			$validated = $input;
		} else {
			$validated = array(
				'input' => $input,
				'errors' => $errors,
			);
		}

		return $validated;
	}


	public static function sections( $sections ) {
		$sections['premium']  = esc_html__( 'Premium', 'testimonials-widget-premium' );
		$sections['cache']    = esc_html__( 'Caching', 'testimonials-widget-premium' );
		$sections['readmore'] = esc_html__( 'Readmore JS', 'testimonials-widget-premium' );
		$sections['rss']      = esc_html__( 'RSS', 'testimonials-widget-premium' );

		return $sections;
	}


	public static function settings( $settings ) {
		$settings['disable_donate'] = array(
			'section' => 'premium',
			'title' => esc_html__( 'Disable Donate Text?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Remove "If you like…" text with the donate and premium purchase links from the settings screen.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'widget' => 0,
			'show_code' => false,
		);

		$settings['premium_expand_begin'] = array(
			'section' => 'premium',
			'desc' => esc_html__( 'Premium Options', 'testimonials-widget-premium' ),
			'type' => 'expand_begin',
		);

		$settings['premium_fields_to_show_heading'] = array(
			'section' => 'fields',
			'desc' => esc_html__( 'Premium Fields', 'testimonials-widget-premium' ),
			'type' => 'heading',
		);

		$settings['hide_excerpt'] = array(
			'section' => 'fields',
			'title' => esc_html__( 'Hide Excerpt?', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'Don\'t display testimonial excerpt in a view.', 'testimonials-widget-premium' ),
		);

		$settings['hide_ratings'] = array(
			'section' => 'fields',
			'title' => esc_html__( 'Hide Ratings?', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'Don\'t display testimonial ratings in a view.', 'testimonials-widget-premium' ),
		);

		$settings['hide_read_more'] = array(
			'section' => 'fields',
			'title' => esc_html__( 'Hide "Read more" Links?', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'Don\'t display testimonial\'s read more link in a view.', 'testimonials-widget-premium' ),
		);

		$settings['hide_read_more_no_content'] = array(
			'section' => 'fields',
			'title' => esc_html__( 'Hide "Read more" Links On Excerpts Only?', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'Don\'t display testimonial\'s read more link in a view when there\'s only an excerpt.', 'testimonials-widget-premium' ),
		);

		$settings['premium_miscellaneous_heading'] = array(
			'desc' => esc_html__( 'Premium General', 'testimonials-widget-premium.', 'testimonials-widget-premium' ),
			'type' => 'heading',
		);

		$settings['nofollow_read_more'] = array(
			'title' => esc_html__( 'Add `nofollow` to "Read more" Links?', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		$rating_options = array();
		for ( $i = self::$rating_min; $i <= Axl_Testimonials_Widget::$rating_max; $i++ ) {
			$rating_options[ $i ] = $i;
		}

		$settings['default_rating'] = array(
			'title' => esc_html__( 'Default Rating?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Default new testimonial rating.', 'testimonials-widget-premium' ),
			'type' => 'select',
			'choices' => $rating_options,
			'std' => Axl_Testimonials_Widget::$rating_max,
			'widget' => 0,
		);

		$settings['force_read_more'] = array(
			'title' => esc_html__( 'Force "Read more" Links?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Overrides Hide "Read more" Links.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		$settings['link_image'] = array(
			'title' => esc_html__( 'Link Image?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'If checked, wraps image in link to testimonial.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
		);

		$settings['show_content_excerpt'] = array(
			'title' => esc_html__( 'Show Content as Excerpt?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'On Testimonials edit page listing, show truncated post content if no excerpt.', 'testimonials-widget-premium' ),
			'std' => 1,
			'type' => 'checkbox',
			'validate' => 'is_true',
			'widget' => 0,
		);

		$settings['enable_sticky_testimonials'] = array(
			'title' => esc_html__( 'Enable Sticky Testimonials?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'For listing and sliding testimonials, sticky entries will be displayed on first.', 'testimonials-widget-premium' ),
			'std' => 0,
			'type' => 'checkbox',
			'validate' => 'is_true',
			'widget' => 0,
		);

		$settings['premium_selection_heading'] = array(
			'section' => 'selection',
			'desc' => esc_html__( 'Premium Selection', 'testimonials-widget-premium' ),
			'type' => 'heading',
		);

		$settings['maximum_length'] = array(
			'section' => 'selection',
			'title' => esc_html__( 'Maximum Length', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Maximum number of allowed characters in testimonial.', 'testimonials-widget-premium' ),
			'validate' => 'min1',
		);

		$settings['minimum_length'] = array(
			'section' => 'selection',
			'title' => esc_html__( 'Minimum Length', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Minimum number of characters required in testimonial.', 'testimonials-widget-premium' ),
			'validate' => 'min1',
		);

		$settings['require_excerpt'] = array(
			'section' => 'selection',
			'title' => esc_html__( 'Require Excerpt?', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'Don\'t display any testimonials without excerpts.', 'testimonials-widget-premium' ),
		);

		$settings['require_image'] = array(
			'section' => 'selection',
			'title' => esc_html__( 'Require Image?', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'Don\'t display any testimonials without featured images.', 'testimonials-widget-premium' ),
		);

		$settings['require_ratings'] = array(
			'section' => 'selection',
			'title' => esc_html__( 'Require Ratings?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Minimum rating required before selecting testimonials to display.', 'testimonials-widget-premium' ),
			'type' => 'select',
			'choices' => array(
				0 => esc_html__( '– No –', 'testimonials-widget-premium' ),
				1 => 1,
				2 => 2,
				3 => 3,
				4 => 4,
				5 => 5,
			),
			'validate' => 'intval',
		);

		$settings['strict_rating'] = array(
			'section' => 'selection',
			'title' => esc_html__( 'Strict Ratings?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Show only testimonials matching the Require Ratings selection.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		$settings['post_type_heading'] = array(
			'section' => 'post_type',
			'desc' => esc_html__( 'Premium Post Type', 'testimonials-widget-premium' ),
			'type' => 'heading',
		);

		$args               = array(
			'public' => true,
		);
		$post_types         = get_post_types( $args, 'objects' );
		self::$post_types[] = Axl_Testimonials_Widget::PT;
		$choices            = array(
			Axl_Testimonials_Widget::PT => esc_html__( 'Testimonials', 'testimonials-widget-premium' ),
		);

		foreach ( $post_types as $type => $data ) {
			if ( 'attachment' == $type ) {
				continue;
			}

			self::$post_types[] = $type;
			$choices[ $type ]   = $data->labels->name;
		}

		$settings['post_type'] = array(
			'section' => 'post_type',
			'title' => esc_html__( 'Alternate Testimonial Post Type?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Set this to use Posts, Posts or another custom post type for testimonials.', 'testimonials-widget-premium' ),
			'type' => 'select',
			'choices' => $choices,
			'std' => Axl_Testimonials_Widget::PT,
		);

		$settings['excerpt_read_more'] = array(
			'section' => 'premium',
			'type' => 'hidden',
			'std' => 0,
		);

		$settings['cache_per_page'] = array(
			'section' => 'cache',
			'title' => esc_html__( 'Cache Per Page?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'This is useful when you use custom testimonial instances per page.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'widget' => 0,
		);

		$settings['no_cache'] = array(
			'section' => 'cache',
			'title' => esc_html__( 'Disable Cache?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'If you want to prevent duplicates, caching must be enabled.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 0,
		);

		$settings['unique'] = array(
			'section' => 'cache',
			'title' => esc_html__( 'Prevent Duplicates?', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'Helpful to prevent showing duplicates when displaying multiple testimonial instances on a single page.', 'testimonials-widget-premium' ),
		);

		$settings['premium_widget_heading'] = array(
			'section' => 'widget',
			'desc' => esc_html__( 'Premium Slider Widget', 'testimonials-widget-premium' ),
			'type' => 'heading',
		);

		$settings['rotate_per_page'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Auto-Rotate Page-to-Page?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Automatically change to the next testimonial, per the sort order, whenever a user navigates to another page.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
		);

		$settings['carousel_count'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Carousel Count?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'The number of testimonials to be shown at a time. "1" means no carousel. Shown number of testimonials will be reduced if carousel width becomes smaller than the original size. Requires Transition Mode "Horizontal" or "Vertical".', 'testimonials-widget-premium' ),
			'std' => 1,
			'validate' => 'intval',
		);

		$settings['show_controls'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Show Next/Prev Controls?', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'desc' => esc_html__( 'Display a next or previous button controller when using `testimonials_slider` or the testimonials widget.', 'testimonials-widget-premium' ),
		);

		$settings['show_pager'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Show Pager?', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'Display a slide pager when using `testimonials_slider` or the testimonials widget.', 'testimonials-widget-premium' ),
		);

		$settings['slide_margin'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Slide Margin?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Margin between each testimonial. Increase to thumbnail height or greater for vertical transitions. Used with Carousel Count.', 'testimonials-widget-premium' ),
			'std' => 10,
			'validate' => 'intval',
		);

		$settings['bxslider_speed'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'bxSlider Speed', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Slide transition duration (in ms).', 'testimonials-widget-premium' ),
			'std' => 500,
			'validate' => 'min1',
		);

		$settings['bxslider_adaptiveHeightSpeed'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'bxSlider Adaptive Height Speed', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Slide height transition duration (in ms). Note: only used if `adaptiveHeight: true`', 'testimonials-widget-premium' ),
			'std' => 500,
			'validate' => 'min1',
		);

		$settings['premium_expand_end'] = array(
			'section' => 'premium',
			'type' => 'expand_end',
		);

		$settings['premium_columns_heading'] = array(
			'section' => 'columns',
			'desc' => esc_html__( 'Premium Columns', 'testimonials-widget-premium' ),
			'type' => 'heading',
			'widget' => 0,
			'show_code' => false,
		);

		$settings['columns_excerpt'] = array(
			'section' => 'columns',
			'title' => esc_html__( 'Hide Excerpt?', 'testimonials-widget', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'widget' => 0,
			'show_code' => false,
		);

		$settings['columns_item'] = array(
			'section' => 'columns',
			'title' => esc_html__( 'Hide Item Referenced?', 'testimonials-widget', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'widget' => 0,
			'show_code' => false,
		);

		$settings['columns_item_url'] = array(
			'section' => 'columns',
			'title' => esc_html__( 'Hide Item URL?', 'testimonials-widget', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'widget' => 0,
			'show_code' => false,
		);

		$settings['columns_rating'] = array(
			'section' => 'columns',
			'title' => esc_html__( 'Hide Rating?', 'testimonials-widget', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'widget' => 0,
			'show_code' => false,
		);

		$settings['columns_read_more'] = array(
			'section' => 'columns',
			'title' => esc_html__( 'Hide Read More Link?', 'testimonials-widget', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'widget' => 0,
			'show_code' => false,
		);

		$settings['rss_slug'] = array(
			'section' => 'rss',
			'title' => esc_html__( 'Testimonials RSS Slug', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'URL component name used for Testimonials RSS Feed URLs below', 'testimonials-widget-premium' ),
			'show_code' => false,
			'std' => 'testimonials',
			'widget' => 0,
			'validate' => 'slug',
		);

		$slug     = tw_get_option( 'rss_slug', 'testimonials' );
		$desc     = __( '<ul><li><a href="%1$s/?feed=%3$s">%1$s/?feed=%3$s</a></li><li><a href="%1$s/feed/%3$s">%1$s/feed/%3$s</a></li><li><a href="%1$s/%3$s.xml">%1$s/%3$s.xml</a></li></ul>', 'testimonials-widget-premium' );
		$site_url = network_site_url();

		$desc     .= __( '<p>If RSS feed links don\'t work, <a href="%2$s">resave your permalink settings</a>.</p>', 'testimonials-widget-premium' );
		$links_url = network_admin_url( 'options-permalink.php' );

		$settings['rss_content'] = array(
			'section' => 'rss',
			'title' => esc_html__( 'Testimonials RSS Feeds', 'testimonials-widget-premium' ),
			'desc' => sprintf( $desc, $site_url, $links_url, $slug ),
			'type' => 'content',
			'show_code' => false,
			'widget' => 0,
		);

		$settings['rss_title'] = array(
			'section' => 'rss',
			'title' => esc_html__( 'Title', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Title of testimonials RSS feed.', 'testimonials-widget-premium' ),
			'show_code' => false,
			'std' => get_bloginfo_rss( 'name' ) . esc_html__( ' Testimonials', 'testimonials-widget-premium' ),
			'validate' => 'wp_kses_post',
			'widget' => 0,
		);

		$settings['rss_description'] = array(
			'section' => 'rss',
			'title' => esc_html__( 'Description', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Description of testimonials RSS feed.', 'testimonials-widget-premium' ),
			'show_code' => false,
			'std' => get_bloginfo_rss( 'description' ),
			'validate' => 'wp_kses_post',
			'widget' => 0,
		);

		$settings['rss_count'] = array(
			'section' => 'rss',
			'title' => esc_html__( 'Count', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Number of testimonials to show in RSS feed.', 'testimonials-widget-premium' ),
			'show_code' => false,
			'std' => 10,
			'validate' => 'intval',
			'widget' => 0,
		);

		$choices = array();
		$sizes   = get_intermediate_image_sizes();
		foreach ( $sizes as $size ) {
			$choices[ $size ] = $size;
		}

		$settings['rss_image_size'] = array(
			'section' => 'rss',
			'title' => esc_html__( 'Image Size', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Featured image size for testimonials to show in RSS feed.', 'testimonials-widget-premium' ),
			'choices' => $choices,
			'show_code' => false,
			'std' => 'medium',
			'type' => 'select',
			'widget' => 0,
		);

		// Readmore JS
		$settings['use_readmore_js'] = array(
			'section' => 'readmore',
			'title' => esc_html__( 'Use Readmore JS?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Doesn\'t work well with testimonials slider. Overrides "Read More" page links, by collapsing and expanding long blocks of text with “Read more” and “Close” jQuery based links.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'widget' => 0,
		);

		$settings['readmore_speed'] = array(
			'section' => 'readmore',
			'title' => esc_html__( 'Speed', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'in milliseconds.', 'testimonials-widget-premium' ),
			'std' => 100,
			'validate' => 'min1',
			'widget' => 0,
		);

		$settings['readmore_max_height'] = array(
			'section' => 'readmore',
			'title' => esc_html__( 'Maximum Height', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'in pixels. Maximum height of collasped content.', 'testimonials-widget-premium' ),
			'std' => 200,
			'validate' => 'min1',
			'widget' => 0,
		);

		$settings['readmore_height_margin'] = array(
			'section' => 'readmore',
			'title' => esc_html__( 'Height Margin', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'in pixels. Avoids collapsing blocks that are only slightly larger than Maximum Height.', 'testimonials-widget-premium' ),
			'std' => 16,
			'validate' => 'min1',
			'widget' => 0,
		);

		$settings['readmore_more_link'] = array(
			'section' => 'readmore',
			'title' => esc_html__( 'More Link', 'testimonials-widget-premium' ),
			'std' => htmlentities( __( '<a href="#">Read more</a>', 'testimonials-widget-premium' ) ),
			'validate' => 'wp_kses_post',
			'widget' => 0,
		);

		$settings['readmore_less_link'] = array(
			'section' => 'readmore',
			'title' => esc_html__( 'Close Link', 'testimonials-widget-premium' ),
			'std' => htmlentities( __( '<a href="#">Close</a>', 'testimonials-widget-premium' ) ),
			'validate' => 'wp_kses_post',
			'widget' => 0,
		);

		$settings['readmore_embed_css'] = array(
			'section' => 'readmore',
			'title' => esc_html__( 'Embed CSS?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Insert required CSS dynamically, uncheck this if you include the necessary CSS in a stylesheet.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'std' => 1,
			'validate' => 'is_true',
			'widget' => 0,
		);

		$settings['readmore_section_css'] = array(
			'section' => 'readmore',
			'title' => esc_html__( 'Section CSS', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Sets the styling of the blocks.', 'testimonials-widget-premium' ),
			'std' => htmlentities( __( 'display: block; width: 100%;', 'testimonials-widget-premium' ) ),
			'validate' => 'wp_kses_post',
			'widget' => 0,
		);

		$settings['readmore_start_open'] = array(
			'section' => 'readmore',
			'title' => esc_html__( 'Start Open?', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Do not immediately truncate, start in the fully opened position.', 'testimonials-widget-premium' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'widget' => 0,
		);

		return $settings;
	}


	public static function truncate_content( $content, $widget_number, $testimonial, $atts ) {
		$char_limit        = $atts['char_limit'];
		$excerpt_read_more = $atts['excerpt_read_more'];
		$force_read_more   = $atts['force_read_more'];
		$hide_read_more    = $atts['hide_read_more'];
		$hide_read_more_nc = $atts['hide_read_more_no_content'];
		$content_bare      = strip_tags( $content );

		if ( self::use_readmore_js( $atts ) ) {
			return self::add_readmore_js( $widget_number, $testimonial, $atts );
		}

		if ( ! $force_read_more ) {
			if ( $hide_read_more ) {
				return $content;
			} elseif ( $hide_read_more_nc && empty( $testimonial['testimonial_content'] ) ) {
				return $content;
			} elseif ( ! $excerpt_read_more ) {
				if ( empty( $char_limit ) || strlen( $content_bare ) <= $char_limit ) {
					return $content;
				}
			}
		}

		if ( empty( $testimonial['post_id'] ) ) {
			return $content;
		}

		// regenerate content
		if ( ! empty( $testimonial['testimonial_excerpt'] ) ) {
			$content = $testimonial['testimonial_excerpt'];
		} else {
			$content = $testimonial['testimonial_content'];
		}

		$content = Axl_Testimonials_Widget::format_content( $content, $widget_number, $atts );

		$close_quote = Axl_Testimonials_Widget::$tag_close_quote;
		$content     = preg_replace( '#' . $close_quote . '.*$#', '', $content );

		global $tw_template_args;

		$tw_template_args = compact( 'content', 'testimonial', 'atts' );

		$more_text = self::get_template_part( 'links', 'more' );

		$content = Axl_Testimonials_Widget::testimonials_truncate( $content, $char_limit, $more_text, true );
		$content = force_balance_tags( $content );

		return $content;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function slider_options_js( $scripts, $testimonials, $atts, $widget_number ) {
		$not_found = Axl_Testimonials_Widget::get_not_found();
		if ( $not_found ) {
			return $scripts;
		}

		$id      = Axl_Testimonials_Widget::ID;
		$id_base = $id . $widget_number;

		switch ( $atts['type'] ) {
			case 'testimonials_slider':
				$show_controls = $atts['show_controls'];
				if ( $show_controls ) {
					$id      = Axl_Testimonials_Widget::ID;
					$id_base = $id . $widget_number;
					$control = $id_base . '-control';

					if ( empty( $scripts[ $id_base ] ) ) {
						return $scripts;
					}

					$script = $scripts[ $id_base ];

					$replace  = "\tprevSelector: '.{$control}'";
					$replace .= ",\nnextSelector: '.{$control}'";
					$replace .= ',';

					$script = preg_replace( "#(\tcontrols: false,)#", $replace, $script );

					$scripts[ $id_base ] = $script;
				}

				$show_pager = $atts['show_pager'];
				if ( $show_pager ) {
					$script = $scripts[ $id_base ];
					$script = str_replace( "\tpager: false,", "\tpager: true,", $script );

					$scripts[ $id_base ] = $script;
				}

				$carousel_count  = $atts['carousel_count'];
				$transition_mode = $atts['transition_mode'];
				if ( 1 < $carousel_count && in_array( $transition_mode, array( 'horizontal', 'vertical' ) ) ) {
					$slide_margin = $atts['slide_margin'];
					$script       = $scripts[ $id_base ];

					$replace  = "\tslideMargin: {$slide_margin}";
					$replace .= ",\nminSlides: {$carousel_count}";
					$replace .= ",\nmoveSlides: {$carousel_count}";
					if ( 'horizontal' == $transition_mode ) {
						$replace .= ",\nmaxSlides: {$carousel_count}";
					}

					$script = str_replace( "\tslideMargin: 2", $replace, $script );

					$scripts[ $id_base ] = $script;
				}

				break;
		}

		return $scripts;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.LongVariable)
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function bxslider_js( $scripts, $testimonials, $atts, $widget_number ) {
		$not_found = Axl_Testimonials_Widget::get_not_found();
		if ( $not_found ) {
			return $scripts;
		}

		$id      = Axl_Testimonials_Widget::ID;
		$id_base = $id . $widget_number;

		if ( empty( $scripts[ $id_base ] ) ) {
			return $scripts;
		}

		$speed                 = $atts['bxslider_speed'];
		$adaptive_height_speed = $atts['bxslider_adaptiveHeightSpeed'];

		switch ( $atts['type'] ) {
			case 'testimonials_slider':
				$find       = 'slideMargin: 2';

				$bxslider = <<<EOD
					adaptiveHeightSpeed: {$adaptive_height_speed},
					speed: {$speed}
EOD;

				$script = $scripts[ $id_base ];
				$script = str_replace( "\t{$find}", "\t{$find},\n{$bxslider}", $script );

				$scripts[ $id_base ] = $script;
				break;
		}

		return $scripts;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function rotate_per_page_js( $scripts, $testimonials, $atts, $widget_number ) {
		$not_found = Axl_Testimonials_Widget::get_not_found();
		if ( $not_found ) {
			return $scripts;
		}

		if ( empty( $atts['rotate_per_page'] ) ) {
			return $scripts;
		}

		$id      = Axl_Testimonials_Widget::ID;
		$id_base = $id . $widget_number;
		$key     = "rotatePerPage{$widget_number}";

		if ( empty( $scripts[ $id_base ] ) ) {
			return $scripts;
		}

		switch ( $atts['type'] ) {
			case 'testimonials_slider':
				$find       = 'slideMargin: 2';
				$limit      = $atts['limit'];
				$js_key     = Axl_Testimonials_Widget::SLUG;
				$slider_var = $js_key . $widget_number;
				$cnt_var    = 'cnt_' . $slider_var;

				$pre_script = <<<EOD
<script type="text/javascript">
function {$slider_var}_next_index( index, increment ) {
	if ( ! increment ) {
		index++;
	}

	if ( null == index || isNaN( index ) || index >= {$limit} ) {
		index = 0;
	}

	jQuery.cookie('{$key}', index, { path: '/' });

	return index;
}

var {$cnt_var} = 0;

jQuery(document).ready( function() {
{$cnt_var} = jQuery.cookie('{$key}');
{$cnt_var} = {$slider_var}_next_index( {$cnt_var}, true );
});
</script>
EOD;

				$rotate = <<<EOD
	startSlide: {$cnt_var},
	onSlideAfter: function( \$slideElement, oldIndex, newIndex ) {
		{$slider_var}_next_index( newIndex );
	}
EOD;

				$script = $scripts[ $id_base ];
				$script = str_replace( "\t{$find}", "\t{$find},\n{$rotate}", $script );

				$scripts[ $id_base ] = $pre_script . $script;
				break;
		}

		return $scripts;
	}


	public static function scripts( $atts ) {
		$atts = wp_parse_args( $atts, Axl_Testimonials_Widget::get_defaults() );
		$atts = Axl_Testimonials_Widget_Settings::validate_settings( $atts );

		$rotate_per_page = $atts['rotate_per_page'];
		if ( $rotate_per_page ) {
			wp_register_script( 'jquery.cookie', self::$plugin_assets . 'js/jquery.cookie.min.js', array( 'jquery' ), '1.3.1', true );
			wp_enqueue_script( 'jquery.cookie' );
		}

		$do_ratings = ! $atts['hide_ratings'];
		if ( $do_ratings ) {
			wp_register_script( 'jquery.raty', self::$plugin_assets . 'js/jquery.raty.min.js', array( 'jquery' ), '2.5.2', true );
			wp_enqueue_script( 'jquery.raty' );

			add_action( 'admin_footer', array( 'Axl_Testimonials_Widget_Premium', 'get_scripts' ), 20 );
			add_action( 'wp_footer', array( 'Axl_Testimonials_Widget_Premium', 'get_scripts' ), 20 );
		}

		if ( self::use_readmore_js( $atts ) ) {
			wp_register_script( 'jquery.readmore', self::$library_assets . 'Readmore/readmore.min.js', array( 'jquery' ), 'master', true );
			wp_enqueue_script( 'jquery.readmore' );
		}
	}


	public static function styles() {
		$exclude_css = tw_get_option( 'exclude_css' );
		if ( empty( $exclude_css ) ) {
			wp_register_style( __CLASS__, self::$plugin_assets . 'css/testimonials-widget-premium.css' );
			wp_enqueue_style( __CLASS__ );
		}
	}


	public static function data_set( $data, $atts ) {
		if ( empty( $data ) ) {
			return $data;
		}

		$do_unique = $atts['unique'];
		if ( $do_unique ) {
			$unique_args = self::get_unique_args();
			$instance    = Axl_Testimonials_Widget::get_instance();

			$selected = apply_filters( 'tw_cache_get', false, $unique_args );
			if ( false === $selected ) {
				$selected = array( $instance => array() );
			} elseif ( empty( $selected[ $instance ] ) ) {
				$selected[ $instance ] = array();
			}
		}

		foreach ( $data as $key => $value ) {
			$post_id = $value['post_id'];

			if ( $do_unique ) {
				$selected[ $instance ][] = $post_id;
			}

			$post = get_post( $post_id );
			$url  = get_post_meta( $post_id, 'testimonials-widget-read-more-link', true );
			if ( ! empty( $url ) && 0 === preg_match( '#https?://#', $url ) ) {
				$url = 'http://' . $url;
			}

			$rating   = get_post_meta( $post_id, 'testimonials-widget-rating', true );
			$item     = get_post_meta( $post_id, 'testimonials-widget-item', true );
			$item_url = get_post_meta( $post_id, 'testimonials-widget-item-url', true );
			if ( ! empty( $item_url ) && 0 === preg_match( '#https?://#', $item_url ) ) {
				$item_url = 'http://' . $item_url;
			}

			$data[ $key ]['testimonial_excerpt']        = $post->post_excerpt;
			$data[ $key ]['testimonial_read_more_link'] = $url;
			$data[ $key ]['testimonial_rating']         = $rating;
			$data[ $key ]['testimonial_item']           = $item;
			$data[ $key ]['testimonial_item_url']       = $item_url;
		}

		if ( $do_unique ) {
			$unique_posts          = $selected[ $instance ];
			$unique_posts          = array_unique( $unique_posts );
			$selected[ $instance ] = $unique_posts;
			$selected              = apply_filters( 'tw_cache_set', $selected, $unique_args );
		}

		return $data;
	}


	public static function activation() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		Axl_Testimonials_Widget_Premium_Cache::activate_cron();
		Axl_Testimonials_Widget_Premium_Cache::purge_transients( '0', true );

		tw_init_options();

		// Making sure post types and taxonomies are registered before flushing rewrite rules
		Axl_Testimonials_Widget::init_post_type();

		// for enhanced archives and RSS feeds
		flush_rewrite_rules();

		$wordpress_api_key = get_option( 'wordpress_api_key' );
		if ( ! empty( $wordpress_api_key ) ) {
			tw_set_option( 'akismet_api_key', $wordpress_api_key );
		}
	}


	public static function deactivation() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		Axl_Testimonials_Widget_Premium_Cache::clear_cache_all();
		Axl_Testimonials_Widget_Premium_Cache::deactivate_cron();
		delete_transient( 'hpsc_session' );
	}


	public static function uninstall() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		require_once TWP_DIR_INC . 'class-testimonials-widget-premium-licensing.php';

		// disable license and update checks
		//$TW_Premium_Licensing = new Axl_Testimonials_Widget_Premium_Licensing();
		//$TW_Premium_Licensing->deactivate_license();
	}


	public static function add_help_tabs( $screen ) {
		$screen->add_help_tab(
			array(
				'id'     => 'twp-premium',
				'title'     => esc_html__( 'Premium', 'testimonials-widget-premium' ),
				'content' => '<p>' . esc_html__( 'Testimonials Widget Premium options.', 'testimonials-widget-premium' ) . '</p>',
			)
		);

		$screen->add_help_tab(
			array(
				'id'     => 'twp-premium-form',
				'title'     => esc_html__( 'Form', 'testimonials-widget-premium' ),
				'content' => '<p>' . esc_html__( 'Testimonials Widget Premium form options.', 'testimonials-widget-premium' ) . '</p>',
			)
		);
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	public static function display_setting( $content, $args, $input ) {
		extract( $args );

		if ( is_null( $input ) ) {
			$options = get_option( Axl_Testimonials_Widget_Settings::ID );
		} else {
			$options      = array();
			$options[ $id ] = $input;
		}

		if ( ! isset( $options[ $id ] ) ) {
			$options[ $id ] = $std;
		}

		$id    = esc_attr( $id );
		$value = $options[ $id ];

		switch ( $type ) {
			case 'rating':
				if ( ! is_null( $value ) && empty( $value ) ) {
					$value = self::$rating_none;
				}

				$instance = Axl_Testimonials_Widget::get_instance();
				if ( empty( $instance ) ) {
					$instance = Axl_Testimonials_Widget::add_instance();
				}

				$content = self::get_ratings( $value, $instance, Axl_Testimonials_Widget_Premium_Form::$form_base . '[' . $id . ']' );
				break;
		}

		return $content;
	}


	public static function get_scripts() {
		if ( empty( self::$scripts_display ) ) {
			return;
		}

		self::$scripts_display = apply_filters( 'twp_scripts_display', self::$scripts_display );

		foreach ( self::$scripts_display as $script ) {
			echo $script;
		}
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function ratings_js( $scripts, $testimonials, $atts, $widget_number ) {
		$not_found = Axl_Testimonials_Widget::get_not_found();
		if ( $not_found ) {
			return $scripts;
		}

		switch ( $atts['type'] ) {
			case 'get_single':
			case 'testimonials':
			case 'testimonials_slider':
				$carousel_count = $atts['carousel_count'];
				$hide_ratings   = $atts['hide_ratings'];
				if ( $hide_ratings ) {
					return $scripts;
				}

				$ratings = '';
				foreach ( $testimonials as $testimonial ) {
					$rating = $testimonial['testimonial_rating'];
					if ( empty( $rating ) ) {
						continue;
					}

					$post_id = $testimonial['post_id'];
					$caching = '// ' . Axl_Testimonials_Widget::ID . ":{$post_id}:";
					$id_name = "raty_{$widget_number}_{$post_id}";

					$path_img = self::$plugin_assets . 'images/';

					$read_only = ",\nreadOnly: true";
					$score     = ",\nscore: {$rating}";

					if ( 1 < $carousel_count ) {
						$ratings .= "
							{$caching}
							jQuery(document).ready( function() {
								jQuery('div:not(.bx-clone) #{$id_name}').raty({
									path: '{$path_img}'
									{$read_only}
									{$score}
								});
								jQuery('div.bx-clone #{$id_name}').raty({
									path: '{$path_img}'
									{$read_only}
									{$score}
								});
							});";
					} else {
						$ratings .= "
							{$caching}
							jQuery(document).ready( function() {
								jQuery('#{$id_name}').raty({
									path: '{$path_img}'
									{$read_only}
									{$score}
								});
							});";
					}
				}

				if ( ! empty( $ratings ) ) {
					$ratings   = '<script type="text/javascript">' . $ratings . '</script>';
					$scripts[] = $ratings;
				}
				break;
		}

		return $scripts;
	}


	public static function get_ratings_div( $post_id = null, $widget_number = null ) {
		global $tw_template_args;

		if ( $widget_number ) {
			$widget_number .= '_';
		}

		$id_name          = "raty_{$widget_number}{$post_id}";
		$tw_template_args = compact( 'post_id', 'widget_number', 'id_name' );
		$ratings          = self::get_template_part( 'testimonial', 'ratings' );

		return $ratings;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function get_testimonials_html_controls( $html, $testimonials, $atts, $is_list, $widget_number, $div_open, $pre_paging, $testimonial_content, $post_paging, $div_close ) {
		switch ( $atts['type'] ) {
			case 'testimonials_slider':
				$show_controls = $atts['show_controls'];
				if ( $show_controls ) {
					$id      = Axl_Testimonials_Widget::ID;
					$id_base = $id . $widget_number;
					$control = $id_base . '-control';

					$controls = '<div class="' . $id . ' bx-controls ' . $control . '"></div>';

					$html .= $controls;
				}
				break;
		}

		return $html;
	}


	public static function tw_examples_html( $html ) {
		$examples_file = TWP_DIR . 'EXAMPLES.md';
		$examples_html = self::markdown2html( $examples_file );
		if ( empty( $examples_html ) ) {
			return $html;
		} else {
			return $examples_html;
		}
	}


	public static function get_aggregate_rating_count( $testimonial ) {
		global $wpdb;

		Axl_Testimonials_Widget::$aggregate_data = apply_filters( 'tw_cache_get', Axl_Testimonials_Widget::$aggregate_data, 'tw_aggregate_data' );

		$testimonial_item = ! empty( $testimonial['testimonial_item'] ) ? $testimonial['testimonial_item'] : Axl_Testimonials_Widget::$aggregate_no_item;
		if ( ! isset( Axl_Testimonials_Widget::$aggregate_data[ $testimonial_item ]['rating_count'] ) ) {
			if ( Axl_Testimonials_Widget::$aggregate_no_item != $testimonial_item ) {
				// @codingStandardsIgnoreStart
				$query_args = array(
					'post_type' => Axl_Testimonials_Widget::PT,
					'posts_per_page' => -1,
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => 'testimonials-widget-item',
							'value' => $testimonial_item,
							'compare' => '=',
						),
						array(
							'key' => 'testimonials-widget-rating',
							'compare' => 'EXISTS',
						),
					),
				);
				// @codingStandardsIgnoreEnd
			} else {
				// @codingStandardsIgnoreStart
				$query_args = array(
					'post_type' => Axl_Testimonials_Widget::PT,
					'posts_per_page' => -1,
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => 'testimonials-widget-item',
							'value' => '',
							'compare' => '=',
						),
						array(
							'key' => 'testimonials-widget-rating',
							'compare' => 'EXISTS',
						),
					),
				);
				// @codingStandardsIgnoreEnd
			}

			$ratings_args  = "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key LIKE 'testimonials-widget-rating'";
			$ratings_query = $wpdb->get_results( $ratings_args, ARRAY_A );

			$ratings = array();
			foreach ( $ratings_query as $rating ) {
				$value = ( 0 < $rating['meta_value'] ) ? $rating['meta_value'] : 0;

				$ratings[ $rating['post_id'] ] = $value;
			}

			$average = 0;
			$count   = 0;
			$value   = 0;

			$query = new WP_Query( $query_args );
			while ( $query->have_posts() ) {
				$query->the_post();
				if ( ! empty( $ratings[ $query->post->ID ] ) ) {
					$count++;
					$value += $ratings[ $query->post->ID ];
				}
			}

			if ( ! empty( $count ) ) {
				$average = $value / $count;
				$average = number_format( $average, 2 );
			}

			Axl_Testimonials_Widget::$aggregate_data[ $testimonial_item ]['rating_count'] = $count;
			Axl_Testimonials_Widget::$aggregate_data[ $testimonial_item ]['rating_value'] = $average;

			Axl_Testimonials_Widget::$aggregate_data = apply_filters( 'tw_cache_set', Axl_Testimonials_Widget::$aggregate_data, 'tw_aggregate_data' );
		}

		return Axl_Testimonials_Widget::$aggregate_data[ $testimonial_item ]['rating_count'];
	}


	public static function get_aggregate_rating_value( $testimonial ) {
		$testimonial_item = ! empty( $testimonial['testimonial_item'] ) ? $testimonial['testimonial_item'] : Axl_Testimonials_Widget::$aggregate_no_item;
		if ( ! isset( Axl_Testimonials_Widget::$aggregate_data[ $testimonial_item ]['rating_value'] ) ) {
			self::get_aggregate_rating_count( $testimonial );
		}

		return Axl_Testimonials_Widget::$aggregate_data[ $testimonial_item ]['rating_value'];
	}


	public static function get_template_part( $slug, $name = null ) {
		if ( is_null( self::$template_loader ) ) {
			self::$template_loader = new Axl_Testimonials_Widget_Premium_Template_Loader();
		}

		ob_start();
		self::$template_loader->get_template_part( $slug, $name );
		$content = ob_get_clean();
		$content = trim( $content );

		return $content;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function save_post( $post_id ) {
		$post_type = get_post_type( $post_id );
		if ( Axl_Testimonials_Widget::PT != $post_type ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$post_status = get_post_status( $post_id );
		$field_name  = 'testimonials-widget-rating';
		if ( 'auto-draft' != $post_status && empty( $_POST[ $field_name ] ) ) {
			$post_rating = get_post_meta( $post_id, $field_name, true );
			if ( ! empty( $post_rating ) ) {
				update_post_meta( $post_id, $field_name, $post_rating );
			} else {
				update_post_meta( $post_id, $field_name, self::$rating_none );
			}
		}
	}


	public static function empty_rating( $rating ) {
		if ( empty( $rating ) || self::$rating_none == $rating ) {
			return true;
		} else {
			return false;
		}
	}


	public static function feed() {
		load_template( TWP_DIR_INC . 'feed-rss2.php' );
	}


	public static function generate_rewrite_rules( $wp_rewrite ) {
		$slug              = tw_get_option( 'rss_slug', 'testimonials' );
		$rules             = Aihrus_Common::rewrite_rules_feed( $wp_rewrite, $slug );
		$wp_rewrite->rules = $rules + $wp_rewrite->rules;

		return $wp_rewrite;
	}


	public static function feed_image( $content ) {
		global $post;

		$post_id = $post->ID;
		if ( Axl_Testimonials_Widget::PT == $post->post_type
			&& has_post_thumbnail( $post_id ) ) {
			$size = tw_get_option( 'rss_image_size', 'thumbnail' );

			$testimonial = array(
				'post_id' => $post_id,
				'testimonial_author' => get_post_meta( $post_id, 'testimonials-widget-author', true ),
				'testimonial_image' => get_the_post_thumbnail( $post_id, $size ),
				'testimonial_source' => get_the_title( $post_id ),
			);

			global $tw_template_args;

			$tw_template_args = compact( 'testimonial' );

			$image   = self::get_template_part( 'testimonial', 'image' );
			$content = $image . $content;
		}

		return $content;
	}


	public static function tw_slider_widget_options( $form_parts ) {
		foreach ( $form_parts as $key => $value ) {
			if ( 'form' == $value['section'] ) {
				unset( $form_parts[ $key ] );
			}
		}

		return $form_parts;
	}


	public static function used_with_codes( $used_with_codes, $parts ) {
		if ( 'widget' != $parts['section'] ) {
			$used_with_codes[] = '[testimonials_links]';
			$used_with_codes[] = 'testimonials_links()';

			$used_with_codes[] = '[testimonials_count]';
			$used_with_codes[] = 'testimonials_count()';
		}

		if ( 'form' == $parts['section'] ) {
			$used_with_codes = array(
				'[testimonials_form]',
				'testimonials_form()',
			);
		}

		if ( 'readmore' == $parts['section'] ) {
			$used_with_codes = array(
				'[testimonials]',
				'testimonials()',
				'[testimonials_slider]',
				'testimonials_slider()',
			);
		}

		return $used_with_codes;
	}


	public static function posts_where( $where ) {
		global $wp_query, $wpdb;

		if ( ! is_search() || Axl_Testimonials_Widget::PT != $wp_query->query_vars['post_type'] || empty( $wp_query->query_vars['s'] ) ) {
			return $where;
		}

		$custom_fields = array(
			'testimonials-widget-author',
			'testimonials-widget-company',
			'testimonials-widget-email',
			'testimonials-widget-item',
			'testimonials-widget-item-url',
			'testimonials-widget-location',
			'testimonials-widget-rating',
			'testimonials-widget-read-more-link',
			'testimonials-widget-title',
			'testimonials-widget-url',
		);

		$meta_keys = implode( "','", $custom_fields );
		$meta_keys = "'" . $meta_keys . "'";

		$term = like_escape( $wp_query->query_vars['s'] );
		$term = '%' . $term . '%';

		$query = "SELECT DISTINCT post_id FROM {$wpdb->postmeta} WHERE meta_value LIKE '%s' AND meta_key IN ( {$meta_keys} )";
		$query = $wpdb->prepare( $query, $term, $meta_keys );

		$post_ids_meta = $wpdb->get_col( $query );

		$query = "SELECT DISTINCT ID FROM {$wpdb->posts} WHERE post_title LIKE '%s' OR post_content LIKE '%s' OR post_excerpt LIKE '%s'";
		$query = $wpdb->prepare( $query, $term, $term, $term );

		$post_ids_ids = $wpdb->get_col( $query );

		$post_ids = array_merge( $post_ids_meta, $post_ids_ids );
		$post_ids = array_unique( $post_ids );

		if ( empty( $post_ids ) ) {
			return $where;
		}

		$post_ids = implode( ',', $post_ids );

		$query = " AND {$wpdb->posts}.ID IN ( {$post_ids} )";

		$uniq_id = uniqid();

		$where  = str_replace( '(((', $uniq_id, $where );
		$where  = str_replace( ')))', $uniq_id, $where );
		$where  = preg_replace( "#AND\s+{$uniq_id}.*{$uniq_id}#", '', $where );
		$where .= $query;

		return $where;
	}


	public static function sortable_columns( $columns ) {
		$columns['id']                                 = 'id';
		$columns['testimonials-widget-author']         = 'testimonials-widget-author';
		$columns['testimonials-widget-company']        = 'testimonials-widget-company';
		$columns['testimonials-widget-email']          = 'testimonials-widget-email';
		$columns['testimonials-widget-item']           = 'testimonials-widget-item';
		$columns['testimonials-widget-item-url']       = 'testimonials-widget-item-url';
		$columns['testimonials-widget-location']       = 'testimonials-widget-location';
		$columns['testimonials-widget-rating']         = 'testimonials-widget-rating';
		$columns['testimonials-widget-read-more-link'] = 'testimonials-widget-read-more-link';
		$columns['testimonials-widget-title']          = 'testimonials-widget-title';
		$columns['testimonials-widget-url']            = 'testimonials-widget-url';

		return $columns;
	}


	public static function request( $query ) {
		if ( ! is_admin() || empty( $query['orderby'] ) ) {
			return $query;
		}

		$columns = array(
			'testimonials-widget-author',
			'testimonials-widget-company',
			'testimonials-widget-email',
			'testimonials-widget-item',
			'testimonials-widget-item-url',
			'testimonials-widget-location',
			'testimonials-widget-rating',
			'testimonials-widget-read-more-link',
			'testimonials-widget-title',
			'testimonials-widget-url',
		);

		$order_by = $query['orderby'];
		if ( ! in_array( $order_by, $columns ) ) {
			return $query;
		}

		$query = array_merge( $query, array( 'meta_key' => $order_by, 'orderby' => 'meta_value' ) );

		return $query;
	}


	/**
	 * @SuppressWarnings(PHPMD.LongVariable)
	 */
	public static function add_readmore_js( $widget_number, $testimonial, $atts ) {
		$content = $testimonial['testimonial_content'];
		$content = Axl_Testimonials_Widget::format_content( $content, $widget_number, $atts );

		$id  = '.post-' . $testimonial['post_id'] . '.testimonials-widget-testimonial';
		$tag = 'blockquote';
		if ( ! empty( $atts['use_quote_tag'] ) ) {
			$tag = 'q';
		}

		$readmore_speed         = $atts['readmore_speed'];
		$readmore_max_height    = $atts['readmore_max_height'];
		$readmore_height_margin = $atts['readmore_height_margin'];
		$readmore_more_link     = html_entity_decode( $atts['readmore_more_link'] );
		$readmore_less_link     = html_entity_decode( $atts['readmore_less_link'] );
		$readmore_embed_css     = $atts['readmore_embed_css'] ? 'true' : 'false';
		$readmore_section_css   = html_entity_decode( $atts['readmore_section_css'] );
		$readmore_section_css   = trim( $readmore_section_css );
		$readmore_start_open    = $atts['readmore_start_open'] ? 'true' : 'false';

		self::$scripts_display[] = '<script type="text/javascript">';
		self::$scripts_display[] = "jQuery( '{$id} {$tag}' ).readmore({";
		self::$scripts_display[] = "speed: {$readmore_speed},";
		self::$scripts_display[] = "maxHeight: {$readmore_max_height},";
		self::$scripts_display[] = "heightMargin: {$readmore_height_margin},";
		self::$scripts_display[] = "moreLink: '{$readmore_more_link}',";
		self::$scripts_display[] = "lessLink: '{$readmore_less_link}',";
		self::$scripts_display[] = "embedCSS: {$readmore_embed_css},";
		self::$scripts_display[] = "sectionCSS: '{$readmore_section_css}',";
		self::$scripts_display[] = "startOpen: {$readmore_start_open},";
		self::$scripts_display[] = '});';
		self::$scripts_display[] = '</script>';

		return $content;
	}


	public static function use_readmore_js( $atts ) {
		$types = array(
			'testimonials',
			'testimonials_slider',
		);

		$use_readmore_js = $atts['use_readmore_js'];
		if ( ! empty( $atts['type'] ) && in_array( $atts['type'], $types ) && $use_readmore_js ) {
			return true;
		}

		return false;
	}
}

?>