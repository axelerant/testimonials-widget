<?php
/**
Testimonials Widget
Copyright (C) 2014  Michael Cannon

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
require_once AIHR_DIR_LIB . 'class-redrokk-metabox-class.php';
require_once TW_DIR_INC . 'class-testimonials-widget-archives-widget.php';
require_once TW_DIR_INC . 'class-testimonials-widget-categories-widget.php';
require_once TW_DIR_INC . 'class-testimonials-widget-recent-testimonials-widget.php';
require_once TW_DIR_INC . 'class-testimonials-widget-settings.php';
require_once TW_DIR_INC . 'class-testimonials-widget-slider-widget.php';
require_once TW_DIR_INC . 'class-testimonials-widget-tag-cloud-widget.php';
require_once TW_DIR_INC . 'class-testimonials-widget-template-loader.php';

if ( class_exists( 'Testimonials_Widget' ) ) {
	return;
}


class Testimonials_Widget extends Aihrus_Common {
	const BASE    = TW_BASE;
	const ID      = 'testimonials-widget-testimonials';
	const SLUG    = 'tw_';
	const VERSION = TW_VERSION;

	const PT = 'testimonials-widget';

	public static $class = __CLASS__;
	public static $cpt_category;
	public static $cpt_tags;
	public static $css             = array();
	public static $css_called      = false;
	public static $instance_number = 0;
	public static $instance_widget = 0;
	public static $library_assets;
	public static $max_num_pages = 0;
	public static $menu_shortcodes;
	public static $notice_key;
	public static $not_found = false;
	public static $plugin_assets;
	public static $post_count     = 0;
	public static $scripts        = array();
	public static $scripts_called = false;
	public static $settings_link;
	public static $tag_close_quote = '<span class="close-quote"></span>';
	public static $tag_open_quote  = '<span class="open-quote"></span>';
	public static $template_loader;
	public static $use_instance  = false;
	public static $widget_number = 100000;
	public static $wp_query;

	public static $aggregate_count   = 'reviewCount';
	public static $aggregate_data    = array();
	public static $aggregate_no_item = '__NO_ITEM__';
	public static $aggregate_rating  = 'aggregateRating';
	public static $aggregate_schema  = 'http://schema.org/AggregateRating';

	public static $cw_author     = 'author';
	public static $cw_date       = 'datePublished';
	public static $cw_date_mod   = 'dateModified';
	public static $cw_review     = 'review';
	public static $cw_source_org = 'sourceOrganization';

	public static $org_location = 'location';
	public static $org_schema   = 'http://schema.org/Organization';

	public static $person_email     = 'email';
	public static $person_home      = 'homeLocation';
	public static $person_job_title = 'jobTitle';
	public static $person_schema    = 'http://schema.org/Person';
	public static $person_member    = 'memberOf';

	public static $place_schema = 'http://schema.org/Place';

	public static $review_body   = 'reviewBody';
	public static $review_item   = 'itemReviewed';
	public static $review_schema = 'http://schema.org/Review';

	public static $schema_div_open  = '<div itemscope itemtype="%1$s">';
	public static $schema_div_prop  = '<div itemprop="%1$s" itemscope itemtype="%2$s">%3$s</div>';
	public static $schema_item_prop = 'itemprop="%1$s"';
	public static $schema_meta      = '<meta itemprop="%1$s" content="%2$s" />';

	public static $thing_image  = 'image';
	public static $thing_name   = 'name';
	public static $thing_schema = 'http://schema.org/Thing';
	public static $thing_url    = 'url';


	public function __construct() {
		parent::__construct();

		self::$library_assets = plugins_url( '/includes/libraries/', dirname( __FILE__ ) );
		self::$library_assets = self::strip_protocol( self::$library_assets );

		self::$plugin_assets = plugins_url( '/assets/', dirname( __FILE__ ) );
		self::$plugin_assets = self::strip_protocol( self::$plugin_assets );

		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		add_action( 'dashboard_glance_items', array( __CLASS__, 'dashboard_glance_items' ) );
		add_action( 'init', array( __CLASS__, 'init' ) );
		add_action( 'widgets_init', array( __CLASS__, 'widgets_init' ) );
		add_shortcode( 'testimonials', array( __CLASS__, 'testimonials' ) );
		add_shortcode( 'testimonialswidget_list', array( __CLASS__, 'testimonials' ) );
		add_shortcode( 'testimonialswidget_widget', array( __CLASS__, 'testimonials_slider' ) );
		add_shortcode( 'testimonials_archives', array( __CLASS__, 'testimonials_archives' ) );
		add_shortcode( 'testimonials_categories', array( __CLASS__, 'testimonials_categories' ) );
		add_shortcode( 'testimonials_examples', array( __CLASS__, 'testimonials_examples' ) );
		add_shortcode( 'testimonials_options', array( __CLASS__, 'testimonials_options' ) );
		add_shortcode( 'testimonials_recent', array( __CLASS__, 'testimonials_recent' ) );
		add_shortcode( 'testimonials_slider', array( __CLASS__, 'testimonials_slider' ) );
		add_shortcode( 'testimonials_tag_cloud', array( __CLASS__, 'testimonials_tag_cloud' ) );
	}


	public static function admin_init() {
		self::support_thumbnails();

		self::$settings_link = '<a href="' . get_admin_url() . 'edit.php?post_type=' . self::PT . '&page=' . Testimonials_Widget_Settings::ID . '">' . esc_html__( 'Settings', 'testimonials-widget' ) . '</a>';

		self::add_meta_box_testimonials_widget();
		self::update();

		add_action( 'manage_' . self::PT . '_posts_custom_column', array( __CLASS__, 'manage_posts_custom_column' ), 10, 2 );
		add_action( 'right_now_content_table_end', array( __CLASS__, 'right_now_content_table_end' ) );
		add_filter( 'manage_' . self::PT . '_posts_columns', array( __CLASS__, 'manage_posts_columns' ) );
		add_filter( 'plugin_action_links', array( __CLASS__, 'plugin_action_links' ), 10, 2 );
		add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 2 );
		add_filter( 'post_updated_messages', array( __CLASS__, 'post_updated_messages' ) );
		add_filter( 'pre_get_posts', array( __CLASS__, 'pre_get_posts_author' ) );

		if ( self::do_load() ) {
			add_filter( 'manage_' . self::PT . '-category_custom_column', array( __CLASS__, 'category_column' ), 10, 3 );
			add_filter( 'manage_' . self::PT . '-post_tag_custom_column', array( __CLASS__, 'post_tag_column' ), 10, 3 );
			add_filter( 'manage_category_custom_column', array( __CLASS__, 'category_column' ), 10, 3 );
			add_filter( 'manage_edit-' . self::PT . '-category_columns', array( __CLASS__, 'category_columns' ) );
			add_filter( 'manage_edit-' . self::PT . '-post_tag_columns', array( __CLASS__, 'category_columns' ) );
			add_filter( 'manage_edit-category_columns', array( __CLASS__, 'category_columns' ) );
			add_filter( 'manage_edit-post_tag_columns', array( __CLASS__, 'category_columns' ) );
			add_filter( 'manage_post_tag_custom_column', array( __CLASS__, 'post_tag_column' ), 10, 3 );
		}
	}


	public static function admin_menu() {
	}


	public static function init() {
		add_filter( 'the_content', array( __CLASS__, 'get_single' ) );
		$enable_archives = tw_get_option( 'enable_archives' );
		if ( $enable_archives ) {
			add_filter( 'pre_get_posts', array( __CLASS__, 'pre_get_posts_allow_testimonials' ) );
		}

		load_plugin_textdomain( self::PT, false, 'testimonials-widget/languages' );

		self::$cpt_category = self::PT . '-category';
		self::$cpt_tags     = self::PT . '-post_tag';

		self::init_post_type();
		self::styles();

		add_action( 'generate_rewrite_rules', array( __CLASS__, 'generate_rewrite_rules' ) );
	}


	public static function plugin_action_links( $links, $file ) {
		if ( self::BASE == $file ) {
			array_unshift( $links, self::$settings_link );
		}

		return $links;
	}


	public static function add_instance() {
		self::$use_instance = false;
		self::$instance_number++;

		return self::$instance_number;
	}


	public static function get_instance() {
		return self::$use_instance ? self::$instance_number : self::$instance_widget;
	}


	public static function set_instance( $widget_number ) {
		self::$use_instance    = true;
		self::$instance_widget = $widget_number;
	}


	public static function support_thumbnails() {
		$feature       = 'post-thumbnails';
		$feature_level = get_theme_support( $feature );

		if ( true === $feature_level ) {
			// already enabled for all post types
			return;
		} elseif ( false === $feature_level ) {
			// none allowed, only enable for our own
			add_theme_support( $feature, array( self::PT ) );
		} else {
			// add our own to list of supported
			$feature_level[0][] = self::PT;
			add_theme_support( $feature, $feature_level[0] );
		}
	}


	public static function get_single( $content ) {
		global $post;

		if ( ! is_single() || self::PT != $post->post_type ) {
			return $content;
		}

		$atts                 = self::get_defaults( true );
		$atts['hide_content'] = 1;
		$atts['ids']          = $post->ID;
		$atts['type']         = 'get_single';

		$instance              = self::add_instance();
		$atts['widget_number'] = $instance;

		$testimonials = array();

		$text = apply_filters( 'tw_cache_get', false, $atts );
		if ( false === $text ) {
			$testimonials = self::get_testimonials( $atts );
			$testimonial  = $testimonials[0];

			$details = self::get_testimonial_html( $testimonial, $atts );
			$details = apply_filters( 'tw_testimonial_html_single', $details, $testimonial, $atts );

			$content = self::do_video( $content, $atts );
			$content = apply_filters( 'tw_testimonial_html_single_content', $content, $testimonial, $atts );

			$text = $content . $details;
			$text = apply_filters( 'tw_cache_set', $text, $atts );
		}

		self::call_scripts_styles( $testimonials, $atts, $instance );

		return $text;
	}


	public static function activation() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		self::init();
		flush_rewrite_rules();
	}


	public static function deactivation() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		flush_rewrite_rules();
	}


	public static function uninstall() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		global $wpdb;

		require_once TW_DIR_INC . 'class-testimonials-widget-settings.php';

		$delete_data = tw_get_option( 'delete_data', false );
		if ( $delete_data ) {
			delete_option( Testimonials_Widget_Settings::ID );
			$wpdb->query( 'OPTIMIZE TABLE `' . $wpdb->options . '`' );

			self::delete_testimonials();
		}
	}


	public static function delete_testimonials() {
		global $wpdb;

		$query = "SELECT ID FROM {$wpdb->posts} WHERE post_type = '" . self::PT . "'";
		$posts = $wpdb->get_results( $query );

		foreach ( $posts as $post ) {
			$post_id = $post->ID;
			self::delete_attachments( $post_id );

			// dels post, meta & comments
			// true is force delete
			wp_delete_post( $post_id, true );
		}

		$wpdb->query( 'OPTIMIZE TABLE `' . $wpdb->postmeta . '`' );
		$wpdb->query( 'OPTIMIZE TABLE `' . $wpdb->posts . '`' );
	}


	public static function delete_attachments( $post_id = false ) {
		global $wpdb;

		$post_id     = $post_id ? $post_id : 0;
		$query       = "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_parent = {$post_id}";
		$attachments = $wpdb->get_results( $query );

		foreach ( $attachments as $attachment ) {
			// true is force delete
			wp_delete_attachment( $attachment->ID, true );
		}
	}


	public static function plugin_row_meta( $input, $file ) {
		if ( self::BASE != $file ) {
			return $input;
		}

		$disable_donate = tw_get_option( 'disable_donate' );
		if ( $disable_donate ) {
			return $input;
		}

		$links = array(
			self::$donate_link,
		);

		global $TW_Premium;
		if ( ! isset( $TW_Premium ) ) {
			$links[] = TW_PREMIUM_LINK;
		}

		$input = array_merge( $input, $links );

		return $input;
	}


	public static function notice_2_12_0() {
		$text = sprintf( __( 'If your Testimonials display has gone to funky town, please <a href="%s">read the FAQ</a> about possible CSS fixes.', 'testimonials-widget' ), esc_url( 'https://aihrus.zendesk.com/entries/23722573-Major-Changes-Since-2-10-0' ) );

		aihr_notice_updated( $text );
	}


	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function notice_donate( $disable_donate = null, $item_name = null ) {
		$disable_donate = tw_get_option( 'disable_donate' );

		parent::notice_donate( $disable_donate, TW_NAME );
	}


	public static function update() {
		$prior_version = tw_get_option( 'admin_notices' );
		if ( $prior_version ) {
			if ( $prior_version < '2.12.0' ) {
				self::set_notice( 'notice_2_12_0' );
			}

			if ( $prior_version < '2.15.0' ) {
				self::set_notice( 'notice_2_15_0' );
			}

			if ( $prior_version < self::VERSION ) {
				tw_requirements_check( true );
				do_action( 'tw_update' );
			}

			tw_set_option( 'admin_notices' );
		}

		// display donate on major/minor version release
		$donate_version = tw_get_option( 'donate_version', false );
		if ( ! $donate_version || ( $donate_version != self::VERSION && preg_match( '#\.0$#', self::VERSION ) ) ) {
			self::set_notice( 'notice_donate' );
			tw_set_option( 'donate_version', self::VERSION );
		}
	}


	public static function pre_get_posts_author( $query ) {
		global $user_ID;

		// author's and below
		if ( $query->is_admin ) {
			if ( empty( $query->is_main_query ) ) {
				if ( $query->is_post_type_archive( self::PT ) ) {
					if ( ! current_user_can( 'edit_others_posts' ) ) {
						$query->set( 'post_author', $user_ID );
					}
				}
			}
		}

		return $query;
	}


	public static function manage_posts_custom_column( $column, $post_id ) {
		$result = false;

		switch ( $column ) {
			case 'id':
				$result = $post_id;
				break;

			case 'shortcode':
				$result  = '[testimonials ids="';
				$result .= $post_id;
				$result .= '"]';
				$result .= '<br />';
				$result .= '[testimonials_slider ids="';
				$result .= $post_id;
				$result .= '"]';
				break;

			case 'testimonials-widget-author':
			case 'testimonials-widget-company':
			case 'testimonials-widget-location':
			case 'testimonials-widget-title':
				$result = get_post_meta( $post_id, $column, true );
				break;

			case 'testimonials-widget-email':
			case 'testimonials-widget-url':
				$url = get_post_meta( $post_id, $column, true );
				if ( ! empty( $url ) && ! is_email( $url ) && 0 === preg_match( '#https?://#', $url ) ) {
					$url = 'http://' . $url;
				}

				$result = make_clickable( $url );
				break;

			case 'thumbnail':
				$email = get_post_meta( $post_id, 'testimonials-widget-email', true );

				if ( has_post_thumbnail( $post_id ) ) {
					$result = get_the_post_thumbnail( $post_id, 'thumbnail' );
				} elseif ( is_email( $email ) ) {
					$result = get_avatar( $email );
				} else {
					$result = false;
				}
				break;

			case self::$cpt_category:
			case self::$cpt_tags:
				$terms  = get_the_terms( $post_id, $column );
				$result = '';
				if ( ! empty( $terms ) ) {
					$out = array();
					foreach ( $terms as $term ) {
						$out[] = '<a href="' . admin_url( 'edit-tags.php?action=edit&taxonomy=' . $column . '&tag_ID=' . $term->term_id . '&post_type=' . self::PT ) . '">' . $term->name . '</a>';
					}

					$result = join( ', ', $out );
				}
				break;
		}

		$result = apply_filters( 'tw_posts_custom_column', $result, $column, $post_id );

		if ( $result ) {
			echo $result;
		}
	}


	public static function manage_posts_columns( $columns ) {
		// order of keys matches column ordering
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => esc_html__( 'Author', 'testimonials-widget' ),
			'author' => esc_html__( 'Published by', 'testimonials-widget' ),
			'date' => esc_html__( 'Date', 'testimonials-widget' ),
		);

		$use_cpt_taxonomy = tw_get_option( 'use_cpt_taxonomy', false );
		if ( ! $use_cpt_taxonomy ) {
			$columns['categories'] = esc_html__( 'Category', 'testimonials-widget' );
			$columns['tags']       = esc_html__( 'Tags', 'testimonials-widget' );
		} else {
			$columns[ self::$cpt_category ] = esc_html__( 'Category', 'testimonials-widget' );
			$columns[ self::$cpt_tags ]     = esc_html__( 'Tags', 'testimonials-widget' );
		}

		$show_id = tw_get_option( 'columns_id' );
		if ( empty( $show_id ) ) {
			$columns['id'] = esc_html__( 'ID', 'testimonials-widget' );
		}

		$show_thumbnail = tw_get_option( 'columns_thumbnail' );
		if ( empty( $show_thumbnail ) ) {
			$columns['thumbnail'] = esc_html__( 'Image', 'testimonials-widget' );
		}

		$show_shortcode = tw_get_option( 'columns_shortcode' );
		if ( empty( $show_shortcode ) ) {
			$columns['shortcode'] = esc_html__( 'Shortcodes', 'testimonials-widget' );
		}

		$show_author = tw_get_option( 'columns_author' );
		if ( empty( $show_author ) ) {
			$columns['testimonials-widget-author'] = esc_html__( 'Author', 'testimonials-widget' );
		}

		$show_job_title = tw_get_option( 'columns_job_title' );
		if ( empty( $show_job_title ) ) {
			$columns['testimonials-widget-title'] = esc_html__( 'Job Title', 'testimonials-widget' );
		}

		$show_location = tw_get_option( 'columns_location' );
		if ( empty( $show_location ) ) {
			$columns['testimonials-widget-location'] = esc_html__( 'Location', 'testimonials-widget' );
		}

		$show_company = tw_get_option( 'columns_company' );
		if ( empty( $show_company ) ) {
			$columns['testimonials-widget-company'] = esc_html__( 'Company', 'testimonials-widget' );
		}

		$show_email = tw_get_option( 'columns_email' );
		if ( empty( $show_email ) ) {
			$columns['testimonials-widget-email'] = esc_html__( 'Email', 'testimonials-widget' );
		}

		$show_url = tw_get_option( 'columns_url' );
		if ( empty( $show_url ) ) {
			$columns['testimonials-widget-url'] = esc_html__( 'URL', 'testimonials-widget' );
		}

		$columns = apply_filters( 'tw_columns', $columns );

		return $columns;
	}


	public static function init_post_type() {
		$labels = array(
			'add_new' => esc_html__( 'Add New', 'testimonials-widget' ),
			'add_new_item' => esc_html__( 'Add New Testimonial', 'testimonials-widget' ),
			'edit_item' => esc_html__( 'Edit Testimonial', 'testimonials-widget' ),
			'name' => esc_html__( 'Testimonials', 'testimonials-widget' ),
			'new_item' => esc_html__( 'Add New Testimonial', 'testimonials-widget' ),
			'not_found' => esc_html__( 'No testimonials found', 'testimonials-widget' ),
			'not_found_in_trash' => esc_html__( 'No testimonials found in Trash', 'testimonials-widget' ),
			'parent_item_colon' => null,
			'search_items' => esc_html__( 'Search Testimonials', 'testimonials-widget' ),
			'singular_name' => esc_html__( 'Testimonial', 'testimonials-widget' ),
			'view_item' => esc_html__( 'View Testimonial', 'testimonials-widget' ),
		);

		$supports = array(
			'title',
			'editor',
			'thumbnail',
			'publicize',
		);

		$allow_comments = tw_get_option( 'allow_comments', false );
		if ( $allow_comments ) {
			$supports[] = 'comments';
		}

		$has_archive      = tw_get_option( 'has_archive', true );
		$rewrite_slug     = tw_get_option( 'rewrite_slug', 'testimonial' );
		$use_cpt_taxonomy = tw_get_option( 'use_cpt_taxonomy', false );

		// editor's and up
		if ( current_user_can( 'edit_others_posts' ) ) {
			$supports[] = 'author';
		}

		if ( ! $use_cpt_taxonomy ) {
			$do_register_taxonomy = false;
			$taxonomies           = array(
				'category',
				'post_tag',
			);
		} else {
			$do_register_taxonomy = true;
			$taxonomies           = array(
				self::$cpt_category,
				self::$cpt_tags,
			);

			self::register_taxonomies();
		}

		$args = array(
			'label' => esc_html__( 'Testimonials', 'testimonials-widget' ),
			'capability_type' => 'post',
			'has_archive' => $has_archive,
			'hierarchical' => false,
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'query_var' => true,
			'rewrite' => array(
				'slug' => $rewrite_slug,
				'with_front' => false,
			),
			'supports' => $supports,
			'taxonomies' => $taxonomies,
		);

		$args = apply_filters( 'tw_register_post_type_args', $args );
		register_post_type( self::PT, $args );

		if ( $do_register_taxonomy ) {
			register_taxonomy_for_object_type( self::$cpt_category, self::PT );
			register_taxonomy_for_object_type( self::$cpt_tags, self::PT );
		}
	}


	public static function register_taxonomies() {
		$args = array(
			'hierarchical' => true,
			'show_admin_column' => true,
		);
		$args = apply_filters( 'tw_register_category_args', $args );
		register_taxonomy( self::$cpt_category, self::PT, $args );

		$args = array(
			'show_admin_column' => true,
			'update_count_callback' => '_update_post_term_count',
		);
		$args = apply_filters( 'tw_register_tags_args', $args );
		register_taxonomy( self::$cpt_tags, self::PT, $args );
	}


	public static function get_defaults( $single_view = false ) {
		$options = tw_get_options();
		if ( empty( $single_view ) ) {
			$defaults = apply_filters( 'tw_defaults', $options );
		} else {
			$defaults = apply_filters( 'tw_defaults_single', $options );
		}

		return $defaults;
	}


	public static function testimonials( $atts ) {
		$atts = wp_parse_args( $atts, self::get_defaults() );
		$atts = Testimonials_Widget_Settings::validate_settings( $atts );

		if ( get_query_var( 'paged' ) ) {
			$atts['paged'] = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$atts['paged'] = get_query_var( 'page' );
		} else {
			$atts['paged'] = 1;
		}

		$atts['type'] = 'testimonials';

		$instance              = self::add_instance();
		$atts['widget_number'] = $instance;

		$testimonials = array();

		$content = apply_filters( 'tw_cache_get', false, $atts );
		if ( false === $content ) {
			$testimonials = self::get_testimonials( $atts );
			$content      = self::get_testimonials_html( $testimonials, $atts );
			$content      = apply_filters( 'tw_cache_set', $content, $atts );
		}

		self::call_scripts_styles( $testimonials, $atts, $instance );

		return $content;
	}


	public static function testimonials_slider( $atts, $widget_number = null ) {
		if ( empty( $widget_number ) ) {
			$widget_number = self::$widget_number++;

			if ( ! isset( $atts['random'] ) ) {
				$atts['random'] = 1;
			}
		}

		$atts = wp_parse_args( $atts, self::get_defaults() );
		$atts = Testimonials_Widget_Settings::validate_settings( $atts );

		$atts['paging'] = false;
		$atts['type']   = 'testimonials_slider';

		self::set_instance( $widget_number );
		$atts['widget_number'] = $widget_number;

		$testimonials = array();

		$content = apply_filters( 'tw_cache_get', false, $atts );
		if ( false === $content ) {
			$testimonials = self::get_testimonials( $atts );
			$content      = self::get_testimonials_html( $testimonials, $atts, false, $widget_number );
			$content      = apply_filters( 'tw_cache_set', $content, $atts );
		}

		self::call_scripts_styles( $testimonials, $atts, $widget_number );

		return $content;
	}


	public static function scripts( $atts ) {
		if ( is_admin() ) {
			return;
		}

		wp_enqueue_script( 'jquery' );

		$enable_video = $atts['enable_video'];
		if ( $enable_video ) {
			wp_register_script( 'jquery.fitvids', self::$library_assets . 'bxslider-4/plugins/jquery.fitvids.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'jquery.fitvids' );
		}

		wp_register_script( 'jquery.bxslider', self::$library_assets . 'bxslider-4/jquery.bxslider.js', array( 'jquery' ), '4.1.2', true );
		wp_enqueue_script( 'jquery.bxslider' );

		do_action( 'tw_scripts', $atts );
	}


	public static function styles() {
		if ( is_admin() ) {
			return;
		}

		$exclude_bxslider_css = tw_get_option( 'exclude_bxslider_css' );
		if ( empty( $exclude_bxslider_css ) ) {
			wp_register_style( 'jquery.bxslider', self::$library_assets . 'bxslider-4/jquery.bxslider.css' );
			wp_enqueue_style( 'jquery.bxslider' );
		}

		wp_register_style( __CLASS__, self::$plugin_assets . 'css/testimonials-widget.css' );

		$exclude_css = tw_get_option( 'exclude_css' );
		if ( empty( $exclude_css ) ) {
			wp_enqueue_style( __CLASS__ );
		}

		do_action( 'tw_styles' );
	}


	public static function get_testimonials_html_css( $atts, $widget_number = null ) {
		$css = array();
		$css = apply_filters( 'tw_testimonials_css', $css, $atts, $widget_number );

		return $css;
	}


	public static function get_testimonials_html_js( $testimonials, $atts, $widget_number = null ) {
		$not_found = self::get_not_found();
		if ( $not_found ) {
			return;
		}

		$scripts          = array();
		$scripts_internal = array();

		$id      = self::ID;
		$id_base = $id . $widget_number;

		switch ( $atts['type'] ) {
			case 'testimonials_slider':
				$javascript = '';
				if ( 1 < count( $testimonials ) ) {
					$refresh_interval = $atts['refresh_interval'];

					$javascript .= '<script type="text/javascript">' . "\n";

					$adaptive_height = $atts['adaptive_height'] ? 'true' : 'false';
					$enable_video    = $atts['enable_video'];
					$show_start_stop = $atts['show_start_stop'];
					$transition_mode = $atts['transition_mode'];

					$auto  = $refresh_interval ? 'true' : 'false';
					$pager = ! $refresh_interval ? 'pager: true' : 'pager: false';
					$pause = $refresh_interval * 1000;
					$video = $enable_video ? "video: true,\nuseCSS: false" : 'video: false';

					$autoControls = $show_start_stop ? 'autoControls: true,' : '';

					$slider_var  = self::SLUG . $widget_number;
					$javascript .= <<<EOF
var {$slider_var} = null;

jQuery(document).ready(function() {
	{$slider_var} = jQuery('.{$id_base}').bxSlider({
		adaptiveHeight: {$adaptive_height},
		auto: {$auto},
		{$autoControls}
		autoHover: true,
		controls: false,
		mode: '{$transition_mode}',
		{$pager},
		pause: {$pause},
		{$video},
		slideMargin: 2
	});
});
EOF;

					$javascript         .= "\n" . '</script>';
					$scripts[ $id_base ] = $javascript;
				}
				break;
		}

		$scripts          = apply_filters( 'tw_testimonials_js', $scripts, $testimonials, $atts, $widget_number );
		$scripts_internal = apply_filters( 'tw_testimonials_js_internal', $scripts_internal, $testimonials, $atts, $widget_number );
		$internal_scripts = implode( "\n", $scripts_internal );
		$scripts          = str_replace( '{INTERNAL_SCRIPTS}', $internal_scripts, $scripts );

		return $scripts;
	}


	public static function get_testimonials_html( $testimonials, $atts, $is_list = true, $widget_number = null ) {
		global $tw_template_args;

		$tw_template_args = compact( 'testimonials', 'atts', 'is_list', 'widget_number' );

		$div_open = self::get_template_part( 'testimonials', 'open' );

		$paging     = Testimonials_Widget_Settings::is_true( $atts['paging'] );
		$pre_paging = '';
		if ( $paging || 'before' === strtolower( $atts['paging'] ) ) {
			$pre_paging = self::get_testimonials_paging( $atts );
		}

		if ( empty( $testimonials ) && ! $atts['hide_not_found'] ) {
			$testimonials = array(
				array( 'testimonial_content' => esc_html__( 'No testimonials found', 'testimonials-widget' ) ),
			);

			self::set_not_found( true );
		} else {
			self::set_not_found();
		}

		$is_first            = true;
		$testimonial_content = '';
		foreach ( $testimonials as $testimonial ) {
			$content = self::get_testimonial_html( $testimonial, $atts, $is_list, $is_first, $widget_number );
			$content = apply_filters( 'tw_testimonial_html', $content, $testimonial, $atts, $is_list, $is_first, $widget_number );

			$testimonial_content .= $content;

			$is_first = false;
		}

		$post_paging = '';
		if ( $paging || 'after' === strtolower( $atts['paging'] ) ) {
			$post_paging = self::get_testimonials_paging( $atts, false );
		}

		$div_close = self::get_template_part( 'testimonials', 'close' );

		$html = $div_open
			. $pre_paging
			. $testimonial_content
			. $post_paging
			. $div_close;

		$html = apply_filters( 'tw_get_testimonials_html', $html, $testimonials, $atts, $is_list, $widget_number, $div_open, $pre_paging, $testimonial_content, $post_paging, $div_close );

		return $html;
	}


	public static function get_testimonial_html( $testimonial, $atts, $is_list = true, $is_first = false, $widget_number = null ) {
		global $tw_template_args;

		$tw_template_args = compact( 'testimonial', 'atts', 'is_list', 'is_first', 'widget_number' );

		$div_open = self::get_template_part( 'testimonial', 'open' );

		$image = '';
		if ( ! $atts['hide_image'] && ! empty( $testimonial['testimonial_image'] ) ) {
			if ( ! ( $atts['hide_image_single'] && 'get_single' == $atts['type'] ) ) {
				$image = self::get_template_part( 'testimonial', 'image' );
			}
		}

		$content = self::get_template_part( 'testimonial', 'content' );
		if ( $atts['target'] ) {
			$content = links_add_target( $content, $atts['target'] );
		}

		$cite = '';
		if ( 1 < count( $testimonial ) ) {
			$cite = self::get_template_part( 'testimonial', 'cite' );
		}

		$extra = '';
		if ( ! empty( $testimonial['testimonial_extra'] ) ) {
			$extra = self::get_template_part( 'testimonial', 'extra' );
		}

		$bottom_text = '';
		if ( ! empty( $atts['bottom_text'] ) && 'false' != $atts['bottom_text'] ) {
			$bottom_text = self::get_template_part( 'testimonial', 'bottom' );
		}

		$schema = '';
		if ( $atts['enable_schema'] ) {
			$schema  = self::get_schema( $testimonial, $atts );
			$schema .= "\n";
		}

		$div_close = self::get_template_part( 'testimonial', 'close' );
		$div_close = $schema . $div_close;

		$html = $div_open
			. $image
			. $content
			. $cite
			. $extra
			. $bottom_text
			. $div_close;

		$html = apply_filters( 'tw_get_testimonial_html', $html, $testimonial, $atts, $is_list, $is_first, $widget_number, $div_open, $image, $content, $cite, $extra, $bottom_text, $div_close );

		// not done sooner as tag_close_quote is used Testimonials Widget Premium
		if ( $atts['disable_quotes'] ) {
			$html = str_replace( self::$tag_open_quote, '', $html );
			$html = str_replace( self::$tag_close_quote, '', $html );
		}

		return $html;
	}


	// Original PHP code as myTruncate2 by Chirp Internet: www.chirp.com.au
	public static function testimonials_truncate( $string, $char_limit = false, $pad = '…', $force_pad = false ) {
		if ( empty( $force_pad ) ) {
			if ( ! $char_limit ) {
				return $string;
			}

			// return with no change if string is shorter than $char_limit
			if ( strlen( $string ) <= $char_limit ) {
				return $string;
			}
		}

		if ( $char_limit ) {
			return self::truncate( $string, $char_limit, $pad, $force_pad );
		}

		return $string . $pad;
	}


	/**
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function format_content( $content, $widget_number, $atts ) {
		if ( empty ( $content ) ) {
			return $content;
		}

		$keep_whitespace = $atts['keep_whitespace'];
		$do_shortcode    = $atts['do_shortcode'];

		$content = self::do_video( $content, $atts );

		// wrap our own quote class around the content before any formatting happens
		$temp_content  = self::$tag_open_quote;
		$temp_content .= trim( $content );
		$temp_content .= self::$tag_close_quote;

		$content = $temp_content;

		$content = wptexturize( $content );
		$content = convert_smilies( $content );
		$content = convert_chars( $content );
		if ( is_null( $widget_number ) || $keep_whitespace ) {
			$content = wpautop( $content );
		}

		$content = shortcode_unautop( $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		if ( $do_shortcode ) {
			$content = do_shortcode( $content );
		} else {
			$content = strip_shortcodes( $content );
		}

		return $content;
	}


	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function get_testimonials_paging( $atts, $prepend = true ) {
		global $tw_template_args;

		$tw_template_args = compact( 'atts', 'prepend' );

		$html = self::get_template_part( 'testimonials', 'paging' );

		return $html;
	}


	public static function get_testimonials_css() {
		if ( empty( self::$css_called ) ) {
			foreach ( self::$css as $css ) {
				echo $css;
			}

			self::$css_called = true;
		}
	}


	public static function get_testimonials_scripts() {
		if ( empty( self::$scripts_called ) ) {
			foreach ( self::$scripts as $script ) {
				echo $script;
			}

			self::$scripts_called = true;
		}
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	public static function get_query_args( $atts ) {
		extract( $atts );

		if ( has_filter( 'posts_orderby', 'CPTOrderPosts' ) ) {
			remove_filter( 'posts_orderby', 'CPTOrderPosts', 99, 2 );
		}

		if ( empty( $fields ) ) {
			$fields = false;
		}

		if ( $random ) {
			$orderby = 'rand';
			$order   = false;
		}

		$args = array(
			'fields' => $fields,
			'orderby' => $orderby,
			'post_status' => array(
				'publish',
				'private',
			),
			'post_type' => self::PT,
			'posts_per_page' => $limit,
		);

		if ( is_single() ) {
			$args['post_status'][] = 'pending';
			$args['post_status'][] = 'draft';
		}

		if ( $paging && ! empty( $atts['paged'] ) && is_singular() ) {
			$args['paged'] = $atts['paged'];
		}

		if ( ! $random && $meta_key ) {
			$args['meta_key'] = $meta_key;
			$args['orderby']  = 'meta_value';
		}

		if ( $order ) {
			$args['order'] = $order;
		}

		if ( $ids ) {
			$ids = explode( ',', $ids );

			$args['post__in'] = $ids;

			if ( 'none' == $args['orderby'] ) {
				add_filter( 'posts_results', array( __CLASS__, 'posts_results_sort_none' ), 10, 2 );
			}
		}

		if ( $exclude ) {
			$exclude              = explode( ',', $exclude );
			$args['post__not_in'] = $exclude;
		}

		$use_cpt_taxonomy = tw_get_option( 'use_cpt_taxonomy', false );
		if ( ! $use_cpt_taxonomy ) {
			if ( $category ) {
				if ( ! preg_match( '#^\d+$#', $category ) ) {
					$args['category_name'] = $category;
				} else {
					$args['cat'] = $category;
				}
			}

			if ( $tags ) {
				$tags = explode( ',', $tags );
				foreach ( $tags as $tag ) {
					if ( ! preg_match( '#^\d+$#', $tag ) ) {
						if ( $tags_all ) {
							if ( ! is_array( $args['tag_slug__and'] ) ) {
								$args['tag_slug__and'] = array();
							}

							$args['tag_slug__and'][] = $tag;
						}
						else {
							if ( ! is_array( $args['tag_slug__in'] ) ) {
								$args['tag_slug__in'] = array();
							}

							$args['tag_slug__in'][] = $tag;
						}
					} else {
						if ( $tags_all ) {
							if ( ! is_array( $args['tag__and'] ) ) {
								$args['tag__and'] = array();
							}

							$args['tag__and'][] = $tag;
						}
						else {
							if ( ! is_array( $args['tag__in'] ) ) {
								$args['tag__in'] = array();
							}

							$args['tag__in'][] = $tag;
						}
					}
				}
			}
		} else {
			if ( ! is_array( $args['tax_query'] ) ) {
				$args['tax_query'] = array();
			}

			if ( $category ) {
				if ( ! preg_match( '#^\d+$#', $category ) ) {
					$args['tax_query'][] = array(
						'taxonomy' => self::$cpt_category,
						'terms' => array( $category ),
						'field' => 'slug',
					);
				} else {
					$args['tax_query'][] = array(
						'taxonomy' => self::$cpt_category,
						'terms' => array( $category ),
						'field' => 'id',
					);
				}
			}

			if ( $tags ) {
				if ( $tags_all ) {
					$args['tax_query'] = array(
						'relation' => 'AND',
					);
				}

				$tags = explode( ',', $tags );
				foreach ( $tags as $term ) {
					if ( ! preg_match( '#^\d+$#', $term ) ) {
						$args['tax_query'][] = array(
							'taxonomy' => self::$cpt_tags,
							'terms' => array( $term ),
							'field' => 'slug',
						);
					} else {
						$args['tax_query'][] = array(
							'taxonomy' => self::$cpt_tags,
							'terms' => array( $term ),
							'field' => 'id',
						);
					}
				}
			}
		}

		$args = apply_filters( 'tw_query_args', $args, $atts );

		return $args;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.LongVariable)
	 */
	public static function get_testimonials( $atts ) {
		$hide_gravatar = $atts['hide_gravatar'];

		$args          = self::get_query_args( $atts );
		$args['query'] = true;

		$testimonials = apply_filters( 'tw_cache_get', false, $args );
		if ( false === $testimonials ) {
			$testimonials = new WP_Query( $args );
			$testimonials = apply_filters( 'tw_cache_set', $testimonials, $args );
		}

		if ( has_filter( 'posts_results', array( __CLASS__, 'posts_results_sort_none' ) ) ) {
			remove_filter( 'posts_results', array( __CLASS__, 'posts_results_sort_none' ) );
		}

		self::$max_num_pages = $testimonials->max_num_pages;
		self::$post_count    = $testimonials->post_count;
		self::$wp_query      = $testimonials;

		wp_reset_postdata();

		$image_size = apply_filters( 'tw_image_size', 'thumbnail' );
		if ( ! is_array( $image_size ) ) {
			global $_wp_additional_image_sizes;
			if ( ! empty( $_wp_additional_image_sizes[ $image_size ] ) ) {
				$gravatar_size = $_wp_additional_image_sizes[ $image_size ]['width'];
			} else {
				$gravatar_size = get_option( $image_size . '_size_w' );
			}

			$gravatar_size = apply_filters( 'tw_gravatar_size', $gravatar_size );
		} else {
			$gravatar_size = apply_filters( 'tw_gravatar_size', $image_size );
		}

		$testimonial_data = array();

		if ( empty( self::$post_count ) ) {
			return $testimonial_data;
		}

		foreach ( $testimonials->posts as $row ) {
			$post_id = $row->ID;
			$email   = get_post_meta( $post_id, 'testimonials-widget-email', true );

			if ( has_post_thumbnail( $post_id ) ) {
				$image = get_the_post_thumbnail( $post_id, $image_size );
			} elseif ( ! $hide_gravatar && is_email( $email ) ) {
				$image = get_avatar( $email, $gravatar_size );

				self::make_gravatar_featured( $post_id, $email );
			} else {
				$image = false;
			}

			$image = self::strip_protocol( $image );

			$url = get_post_meta( $post_id, 'testimonials-widget-url', true );
			if ( ! empty( $url ) && 0 === preg_match( '#https?://#', $url ) ) {
				$url = 'http://' . $url;
			}

			$data = array(
				'post_id' => $post_id,
				'testimonial_author' => get_post_meta( $post_id, 'testimonials-widget-author', true ),
				'testimonial_company' => get_post_meta( $post_id, 'testimonials-widget-company', true ),
				'testimonial_content' => $row->post_content,
				'testimonial_email' => $email,
				'testimonial_extra' => '',
				'testimonial_image' => $image,
				'testimonial_location' => get_post_meta( $post_id, 'testimonials-widget-location', true ),
				'testimonial_source' => $row->post_title,
				'testimonial_title' => get_post_meta( $post_id, 'testimonials-widget-title', true ),
				'testimonial_url' => $url,
			);

			$testimonial_data[] = $data;
		}

		$testimonial_data = apply_filters( 'tw_data', $testimonial_data, $atts );

		return $testimonial_data;
	}


	public static function posts_results_sort_none( $posts, $query ) {
		$order = $query->query_vars['post__in'];
		if ( empty( $order ) ) {
			return $posts;
		}

		$posts_none_sorted = array();
		// put posts in same orders as post__in
		foreach ( $order as $id ) {
			foreach ( $posts as $key => $post ) {
				if ( $id == $post->ID ) {
					$posts_none_sorted[] = $post;
					unset( $posts[ $key ] );
				}
			}
		}

		return $posts_none_sorted;
	}


	public static function widgets_init() {
		register_widget( 'Testimonials_Widget_Archives_Widget' );
		register_widget( 'Testimonials_Widget_Categories_Widget' );
		register_widget( 'Testimonials_Widget_Recent_Testimonials_Widget' );
		register_widget( 'Testimonials_Widget_Slider_Widget' );
		register_widget( 'Testimonials_Widget_Tag_Cloud_Widget' );
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	public static function add_meta_box_testimonials_widget() {
		$fields = array(
			array(
				'name' => esc_html__( 'Author', 'testimonials-widget' ),
				'id' => 'testimonials-widget-author',
				'type' => 'text',
				'desc' => esc_html__( 'Use when the testimonial title is not the authors\' name.', 'testimonials-widget' ),
			),
			array(
				'name' => esc_html__( 'Job Title', 'testimonials-widget' ),
				'id' => 'testimonials-widget-title',
				'type' => 'text',
				'desc' => '',
			),
			array(
				'name' => esc_html__( 'Location', 'testimonials-widget' ),
				'id' => 'testimonials-widget-location',
				'type' => 'text',
				'desc' => '',
			),
			array(
				'name' => esc_html__( 'Company', 'testimonials-widget' ),
				'id' => 'testimonials-widget-company',
				'type' => 'text',
				'desc' => '',
			),
			array(
				'name' => esc_html__( 'Email', 'testimonials-widget' ),
				'id' => 'testimonials-widget-email',
				'type' => 'text',
				'desc' => esc_html__( 'If an email is provided, but not an image, a Gravatar icon will be attempted to be loaded.', 'testimonials-widget' ),
			),
			array(
				'name' => esc_html__( 'URL', 'testimonials-widget' ),
				'id' => 'testimonials-widget-url',
				'type' => 'text',
				'desc' => '',
			),
		);

		$fields = apply_filters( 'tw_meta_box', $fields );

		$meta_box = redrokk_metabox_class::getInstance(
			self::ID,
			array(
				'title' => esc_html__( 'Testimonial Data', 'testimonials-widget' ),
				'description' => '',
				'_object_types' => 'testimonials-widget',
				'priority' => 'high',
				'_fields' => $fields,
			)
		);
	}


	/**
	 * Update messages for custom post type
	 *
	 * Original author: Travis Ballard http://www.travisballard.com
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 * @param mixed   $m
	 * @return mixed $m
	 */
	public static function post_updated_messages( $m ) {
		global $post;

		$m[ self::PT ] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __( 'Testimonial updated. <a href="%s">View testimonial</a>', 'testimonials-widget' ), esc_url( get_permalink( $post->ID ) ) ),
			2 => esc_html__( 'Custom field updated.', 'testimonials-widget' ),
			3 => esc_html__( 'Custom field deleted.', 'testimonials-widget' ),
			4 => esc_html__( 'Testimonial updated.', 'testimonials-widget' ),
			/* translators: %s: date and time of the revision */
			5 => isset( $_GET['revision'] ) ? sprintf( esc_html__( 'Testimonial restored to revision from %s', 'testimonials-widget' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __( 'Testimonial published. <a href="%s">View testimonial</a>', 'testimonials-widget' ), esc_url( get_permalink( $post->ID ) ) ),
			7 => esc_html__( 'Testimonial saved.', 'testimonials-widget' ),
			8 => sprintf( __( 'Testimonial submitted. <a target="_blank" href="%s">Preview testimonial</a>', 'testimonials-widget' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) ),
			9 => sprintf( __( 'Testimonial scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview testimonial</a>', 'testimonials-widget' ), date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ) ), esc_url( get_permalink( $post->ID ) ) ),
			10 => sprintf( __( 'Testimonial draft updated. <a target="_blank" href="%s">Preview testimonial</a>', 'testimonials-widget' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) )
		);

		return $m;
	}


	public static function right_now_content_table_end() {
		$content = '
			<tr>
				<td class="first b b-%1$s">%4$s%2$s%5$s</td>
				<td class="t %1$s">%4$s%3$s%5$s</td>
			</tr>';
		$posts   = wp_count_posts( self::PT );
		$count   = $posts->publish;
		$name    = _n( 'Testimonial', 'Testimonials', $count, 'testimonials-widget' );
		$count_f = number_format_i18n( $count );
		$a_open  = '<a href="edit.php?post_type=' . self::PT . '">';
		$a_close = '</a>';

		if ( current_user_can( 'edit_others_posts' ) ) {
			$result = sprintf( $content, self::PT, $count_f, $name, $a_open, $a_close );
		} else {
			$result = sprintf( $content, self::PT, $count_f, $name, '', '' );
		}

		echo $result;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	public static function get_schema( $testimonial, $atts ) {
		foreach ( $testimonial as $key => $value ) {
			if ( 'testimonial_image' != $key ) {
				$testimonial[ $key ] = self::clean_string( $value );
			}
		}

		extract( $testimonial );

		$do_company  = ! $atts['hide_company'] && ! empty( $testimonial_company );
		$do_content  = ! empty( $testimonial['testimonial_content'] );
		$do_email    = ! $atts['hide_email'] && ! empty( $testimonial_email ) && is_email( $testimonial_email );
		$do_image    = ! $atts['hide_image'] && ! empty( $testimonial_image );
		$do_location = ! $atts['hide_location'] && ! empty( $testimonial_location );
		$do_source   = ! $atts['hide_source'] && ! empty( $testimonial_source );
		$do_title    = ! $atts['hide_title'] && ! empty( $testimonial_title );
		$do_url      = ! $atts['hide_url'] && ! empty( $testimonial_url );

		$item_reviewed     = self::clean_string( $atts['item_reviewed'] );
		$item_reviewed_url = self::clean_string( $atts['item_reviewed_url'] );

		$schema  = sprintf( self::$schema_div_open, self::$review_schema );
		$schema .= "\n";

		$author_meta   = array();
		$item_meta     = array();
		$location_meta = array();
		$org_meta      = array();
		$review_meta   = array();

		if ( ! empty( $testimonial_author ) ) {
			$author_meta[ self::$thing_name ] = $testimonial_author;
		}

		if ( $do_source ) {
			if ( empty( $testimonial_author ) ) {
				$author_meta[ self::$thing_name ] = $testimonial_source;
			} else {
				$review_meta[ self::$thing_name ] = $testimonial_source;
			}
		}

		if ( $do_title ) {
			$author_meta[ self::$person_job_title ] = $testimonial_title;
		}

		if ( $do_email ) {
			$author_meta[ self::$person_email ] = $testimonial_email;
		}

		if ( ! $do_company ) {
			if ( $do_url ) {
				$author_meta[ self::$thing_url ] = $testimonial_url;
			}
		} else {
			if ( $do_url ) {
				$org_meta[ self::$thing_url ] = $testimonial_url;
			}

			$org_meta[ self::$thing_name ] = $testimonial_company;
		}

		if ( $do_location ) {
			$location_meta[ self::$thing_name ] = $testimonial_location;

			if ( ! $do_company ) {
				$author_meta[ self::$person_home ] = array( self::$place_schema, $location_meta );
			} else {
				$org_meta[ self::$org_location ] = array( self::$place_schema, $location_meta );
			}
		}

		if ( ! empty( $author_meta ) && ! empty( $org_meta ) ) {
			$author_meta[ self::$person_member ] = array( self::$org_schema, $org_meta );
		} elseif ( ! empty( $org_meta ) ) {
			$author_meta[ self::$cw_source_org ] = array( self::$org_schema, $org_meta );
		}

		$author_meta = apply_filters( 'tw_schema_author', $author_meta, $testimonial, $atts );
		$author      = self::create_schema_div_prop( self::$cw_author, self::$person_schema, $author_meta );
		$schema     .= $author;
		$schema     .= "\n";

		$post         = get_post( $testimonial['post_id'] );
		$the_date     = mysql2date( 'Y-m-d', $post->post_date );
		$the_date_mod = mysql2date( 'Y-m-d', $post->post_modified );

		$review_name_length = apply_filters( 'tw_review_name_length', 156 );

		if ( $do_content ) {
			$review_meta[ self::$review_body ] = $testimonial['testimonial_content'];
		}

		$review_meta[ self::$cw_date ]     = $the_date;
		$review_meta[ self::$cw_date_mod ] = $the_date_mod;
		$review_meta[ self::$thing_url ]   = post_permalink( $post->ID );
		if ( empty( $review_meta[ self::$thing_name ] ) ) {
			$review_meta[ self::$thing_name ] = self::testimonials_truncate( $testimonial_content, $review_name_length );
		}

		if ( $do_image ) {
			$src = self::get_image_src( $testimonial_image );

			$review_meta[ self::$thing_image ] = $src;
		}

		$aggregate_meta = array(
			self::$aggregate_count => self::get_aggregate_count( $testimonial ),
		);

		$review_meta[ self::$aggregate_rating ] = array( self::$aggregate_schema, $aggregate_meta );

		$review_meta = apply_filters( 'tw_schema_review', $review_meta, $testimonial, $atts );
		$review      = self::create_schema_meta( $review_meta );
		$schema     .= $review;
		$schema     .= "\n";

		$item_meta[ self::$thing_name ] = $item_reviewed;
		$item_meta[ self::$thing_url ]  = $item_reviewed_url;

		$item_meta = apply_filters( 'tw_schema_item', $item_meta, $testimonial, $atts );
		$item      = self::create_schema_div_prop( self::$review_item, self::$thing_schema, $item_meta );
		$schema   .= $item;
		$schema   .= "\n";

		$schema .= '</div>';
		$schema .= "\n";

		$schema = apply_filters( 'tw_schema', $schema, $testimonial, $atts );

		return $schema;
	}


	public static function create_schema_meta( $meta_data ) {
		$meta = '';

		if ( empty( $meta_data ) ) {
			return $meta;
		}

		foreach ( $meta_data as $key => $value ) {
			if ( is_array( $value ) ) {
				$meta .= self::create_schema_div_prop( $key, $value[0], $value[1] );
			} else {
				$meta .= sprintf( self::$schema_meta, $key, $value );
			}

			$meta .= "\n";
		}

		return $meta;
	}


	public static function create_schema_div_prop( $property_name, $schema_name, $meta_data ) {
		$meta   = '';
		$schema = '';

		if ( empty( $meta_data ) ) {
			return $schema;
		}

		if ( is_array( $meta_data ) ) {
			foreach ( $meta_data as $key => $value ) {
				if ( is_array( $value ) ) {
					$meta .= self::create_schema_div_prop( $key, $value[0], $value[1] );
				} else {
					$meta .= sprintf( self::$schema_meta, $key, $value );
				}

				$meta .= "\n";
			}

			$schema = sprintf( self::$schema_div_prop, $property_name, $schema_name, $meta );
		} else {
			$schema = sprintf( self::$schema_div_prop, $property_name, $schema_name, $meta_data );
		}

		return $schema;
	}


	public static function generate_css( $atts, $widget_number = null ) {
		$atts['subtype'] = 'css';

		$css = apply_filters( 'tw_cache_get', false, $atts );
		if ( false === $css ) {
			$css = self::get_testimonials_html_css( $atts, $widget_number );
			$css = apply_filters( 'tw_cache_set', $css, $atts );
		}

		if ( ! empty( $css ) ) {
			self::$css = array_merge( $css, self::$css );
			add_action( 'wp_footer', array( __CLASS__, 'get_testimonials_css' ), 20 );
		}
	}


	public static function generate_js( $testimonials, $atts, $widget_number = null ) {
		$atts['subtype'] = 'js';

		$js = apply_filters( 'tw_cache_get', false, $atts );
		if ( false === $js ) {
			$js = self::get_testimonials_html_js( $testimonials, $atts, $widget_number );
			$js = apply_filters( 'tw_cache_set', $js, $atts );
		}

		if ( ! empty( $js ) ) {
			self::$scripts = array_merge( $js, self::$scripts );
			add_action( 'wp_footer', array( __CLASS__, 'get_testimonials_scripts' ), 20 );
		}
	}


	public static function call_scripts_styles( $testimonials, $atts, $widget_number = null ) {
		if ( is_null( $widget_number ) ) {
			$widget_number = self::get_instance();
		}

		self::scripts( $atts );

		self::generate_css( $atts );
		self::generate_js( $testimonials, $atts, $widget_number );
	}


	public static function make_gravatar_featured( $post_id, $email ) {
		$size  = get_option( 'large_size_w' );
		$image = get_avatar( $email, $size );
		$src   = self::get_image_src( $image, false );
		$file  = sanitize_title( $email ) . '.jpeg';

		self::add_media( $post_id, $src, $file );
	}


	public static function notice_2_15_0() {
		$text = sprintf( __( 'If your Testimonials display has gone to funky town, please <a href="%s">read the FAQ</a> about possible fixes.', 'testimonials-widget' ), esc_url( 'https://aihrus.zendesk.com/entries/28402246-Major-Change-for-2-15-0' ) );

		aihr_notice_updated( $text );
	}


	public static function set_not_found( $not_found = false ) {
		self::$not_found = $not_found;
	}


	public static function get_not_found() {
		return self::$not_found;
	}


	public static function version_check() {
		$valid_version = true;
		if ( ! $valid_version ) {
			$deactivate_reason = esc_html__( 'Failed version check', 'testimonials-widget' );
			aihr_deactivate_plugin( self::BASE, TW_NAME, $deactivate_reason );
			self::check_notices();
		}

		return $valid_version;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function do_load() {
		$do_load = false;
		if ( ! empty( $GLOBALS['pagenow'] ) && in_array( $GLOBALS['pagenow'], array( 'options.php', 'widgets.php' ) ) ) {
			$do_load = true;
		} elseif ( ! empty( $_REQUEST['post_type'] ) && self::PT == $_REQUEST['post_type'] ) {
			if ( ! empty( $GLOBALS['pagenow'] ) && in_array( $GLOBALS['pagenow'], array( 'edit.php', 'edit-tags.php' ) ) ) {
				$do_load = true;
			} elseif ( ! empty( $_REQUEST['option_page'] ) && Testimonials_Widget_Settings::ID == $_REQUEST['option_page'] ) {
				$do_load = true;
			}
		}

		return $do_load;
	}


	public static function category_columns( $columns ) {
		$columns['shortcode'] = esc_html__( 'Shortcode', 'testimonials-widget' );

		return $columns;
	}


	public static function category_column( $result, $column_name, $term_id, $category = true ) {
		$attribute = $category ? 'category' : 'tags';

		$use_cpt_taxonomy = tw_get_option( 'use_cpt_taxonomy', false );
		if ( ! $use_cpt_taxonomy ) {
			if ( $category ) {
				$term = get_term( $term_id, 'category' );
			} else {
				$term = get_term( $term_id, 'post_tag' );
			}
		} else {
			if ( $category ) {
				$term = get_term( $term_id, self::$cpt_category );
			} else {
				$term = get_term( $term_id, self::$cpt_tags );
			}
		}

		switch ( $column_name ) {
			case 'shortcode':
				$result  = '[testimonials ' . $attribute . '="' .$term->slug . '"]';
				$result .= '<br />';
				$result .= '[testimonials_slider ' . $attribute . '="' .$term->slug . '"]';
				break;
		}

		return $result;
	}


	public static function post_tag_column( $result, $column_name, $term_id ) {
		return self::category_column( $result, $column_name, $term_id, false );
	}


	public static function dashboard_glance_items( $array ) {
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			return $array;
		}

		$count = apply_filters( 'tw_cache_get', false, 'dashboard_count' );
		if ( false === $count ) {
			$posts = wp_count_posts( self::PT );
			$count = $posts->publish;
			$count = apply_filters( 'tw_cache_set', $count, 'dashboard_count' );
		}

		if ( $count ) {
			$content = '%1$s%2$s %3$s%4$s';
			$name    = _n( 'Testimonial', 'Testimonials', $count, 'testimonials-widget-premium', 'testimonials-widget' );
			$count_f = number_format_i18n( $count );
			$a_open  = '<a href="edit.php?post_type=' . self::PT . '">';
			$a_close = '</a>';

			$array[] = sprintf( $content, $a_open, $count_f, $name, $a_close );
		}

		return $array;
	}


	/**
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function do_video( $content, $atts ) {
		$enable_video = $atts['enable_video'];
		if ( $enable_video && ! empty( $GLOBALS['wp_embed'] ) ) {
			$content = $GLOBALS['wp_embed']->autoembed( $content );
			$content = $GLOBALS['wp_embed']->run_shortcode( $content );
		}

		return $content;
	}


	public static function get_template_part( $slug, $name = null ) {
		if ( is_null( self::$template_loader ) ) {
			self::$template_loader = new Testimonials_Widget_Template_Loader();
		}

		ob_start();
		self::$template_loader->get_template_part( $slug, $name );
		$content = ob_get_clean();

		return $content;
	}


	public static function pre_get_posts_allow_testimonials( $query ) {
		if ( $query->is_admin ) {
			return $query;
		} elseif ( ( $query->is_main_query() || is_feed() )
			&& ! is_page()
			&& ( ( ! empty( $query->query_vars['post_type'] ) && 'post' == $query->query_vars['post_type'] )
			|| is_archive() )
		) {
			$query->set( 'post_type', array( 'post', self::PT ) );
		}

		return $query;
	}


	public static function testimonials_archives( $atts, $widget_number = null ) {
		$atts = wp_parse_args( $atts, Testimonials_Widget_Archives_Widget::get_defaults() );
		$atts = Testimonials_Widget_Archives_Widget::validate_settings( $atts );

		$atts['type'] = 'testimonials_archives';

		$instance              = ! empty( $widget_number ) ? $widget_number : self::add_instance();
		$atts['widget_number'] = $instance;

		$content = apply_filters( 'tw_cache_get', false, $atts );
		if ( false === $content ) {
			$content = self::get_archives_html( $atts );
			$content = apply_filters( 'tw_cache_set', $content, $atts );
		}

		self::call_scripts_styles( array(), $atts, $instance );

		return $content;
	}


	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function get_archives_html( $atts ) {
		global $tw_template_args;

		$tw_template_args = compact( 'atts' );

		$content = self::get_template_part( 'testimonials', 'archives' );

		return $content;
	}


	public static function get_archives_link( $link_html ) {
		$home_url  = home_url();
		$slug      = Aihrus_Common::get_archive_slug( self::PT );
		$link_html = str_replace( $home_url, $home_url . '/' . $slug, $link_html );

		return $link_html;
	}


	public static function generate_rewrite_rules( $wp_rewrite ) {
		$rules             = Aihrus_Common::rewrite_rules_date_archives( self::PT, $wp_rewrite );
		$wp_rewrite->rules = $rules + $wp_rewrite->rules;

		return $wp_rewrite;
	}


	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function getarchives_where( $where, $args ) {
		return "WHERE post_type = '" . Testimonials_Widget::PT . "' AND post_status = 'publish'";
	}


	public static function testimonials_categories( $atts, $widget_number = null ) {
		$atts = wp_parse_args( $atts, Testimonials_Widget_Categories_Widget::get_defaults() );
		$atts = Testimonials_Widget_Categories_Widget::validate_settings( $atts );

		$atts['type'] = 'testimonials_categories';

		$instance              = ! empty( $widget_number ) ? $widget_number : self::add_instance();
		$atts['widget_number'] = $instance;

		$content = apply_filters( 'tw_cache_get', false, $atts );
		if ( false === $content ) {
			$content = self::get_categories_html( $atts );
			$content = apply_filters( 'tw_cache_set', $content, $atts );
		}

		self::call_scripts_styles( array(), $atts, $instance );

		return $content;
	}


	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function get_categories_html( $atts ) {
		global $tw_template_args;

		$tw_template_args = compact( 'atts' );

		$content = self::get_template_part( 'testimonials', 'categories' );

		return $content;
	}


	public static function testimonials_recent( $atts, $widget_number = null ) {
		$atts = wp_parse_args( $atts, Testimonials_Widget_Recent_Testimonials_Widget::get_defaults() );
		$atts = Testimonials_Widget_Recent_Testimonials_Widget::validate_settings( $atts );

		$atts['type'] = 'testimonials_recent';

		$instance              = ! empty( $widget_number ) ? $widget_number : self::add_instance();
		$atts['widget_number'] = $instance;

		$content = apply_filters( 'tw_cache_get', false, $atts );
		if ( false === $content ) {
			$content = self::get_recent_html( $atts );
			$content = apply_filters( 'tw_cache_set', $content, $atts );
		}

		self::call_scripts_styles( array(), $atts, $instance );

		return $content;
	}


	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function get_recent_html( $atts ) {
		global $tw_template_args;

		$tw_template_args = compact( 'atts' );

		$content = self::get_template_part( 'testimonials', 'recent' );

		return $content;
	}


	public static function testimonials_tag_cloud( $atts, $widget_number = null ) {
		$atts = wp_parse_args( $atts, Testimonials_Widget_Tag_Cloud_Widget::get_defaults() );
		$atts = Testimonials_Widget_Tag_Cloud_Widget::validate_settings( $atts );

		$atts['type'] = 'testimonials_tag_cloud';

		$instance              = ! empty( $widget_number ) ? $widget_number : self::add_instance();
		$atts['widget_number'] = $instance;

		$content = apply_filters( 'tw_cache_get', false, $atts );
		if ( false === $content ) {
			$content = self::get_tag_cloud_html( $atts );
			$content = apply_filters( 'tw_cache_set', $content, $atts );
		}

		self::call_scripts_styles( array(), $atts, $instance );

		return $content;
	}


	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function get_tag_cloud_html( $atts ) {
		global $tw_template_args;

		$tw_template_args = compact( 'atts' );

		$content = self::get_template_part( 'testimonials', 'tag-cloud' );

		return $content;
	}


	public static function get_aggregate_count( $testimonial ) {
		$testimonial_item = ! empty( $testimonial['testimonial_item'] ) ? $testimonial['testimonial_item'] : self::$aggregate_no_item;
		if ( ! isset( self::$aggregate_data[ $testimonial_item ]['count'] ) ) {
			// @codingStandardsIgnoreStart
			$query_args = array(
				'post_type' => Testimonials_Widget::PT,
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'testimonials-widget-item',
						'value' => $testimonial_item,
						'compare' => 'LIKE',
					),
				),
			);
			// @codingStandardsIgnoreEnd

			$count = 0;
			$query = new WP_Query( $query_args );
			while  ( $query->have_posts() ) {
				$query->the_post();
				$count++;
			}

			self::$aggregate_data[ $testimonial_item ]['count'] = $count;
		}

		return self::$aggregate_data[ $testimonial_item ]['count'];
	}


	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function testimonials_examples( $atts = null ) {
		$atts = wp_parse_args( $atts, self::get_defaults() );

		$atts['type'] = 'testimonials_examples';

		$content = apply_filters( 'tw_cache_get', false, $atts );
		if ( false === $content ) {
			$content = self::get_testimonials_examples( $atts );
			$content = apply_filters( 'tw_cache_set', $content, $atts );
		}

		self::call_scripts_styles( array(), $atts );

		return $content;
	}


	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function get_testimonials_examples( $atts = null ) {
		$examples_file = TW_DIR . 'EXAMPLES.md';
		$examples_html = self::markdown2html( $examples_file );
		$examples_html = apply_filters( 'tw_examples_html', $examples_html );
		$examples_html = str_replace( '[[', '[', $examples_html );
		$examples_html = str_replace( ']]', ']', $examples_html );

		return $examples_html;
	}


	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function testimonials_options( $atts = null ) {
		$atts = wp_parse_args( $atts, self::get_defaults() );

		$atts['type'] = 'testimonials_options';

		$content = apply_filters( 'tw_cache_get', false, $atts );
		if ( false === $content ) {
			$content = self::get_testimonials_options( $atts );
			$content = apply_filters( 'tw_cache_set', $content, $atts );
		}

		self::call_scripts_styles( array(), $atts );

		return $content;
	}


	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function get_testimonials_options( $atts = null ) {
		$sections = Testimonials_Widget_Settings::get_sections();
		$settings = Testimonials_Widget_Settings::get_settings();

		$ignored_types = array( 'expand_begin', 'expand_end', 'expand_all', 'content', );

		$do_continue  = false;
		$do_used_with = false;

		$first_dl = true;
		$open_dl  = false;
		$html     = '';
		foreach ( $settings as $setting => $parts ) {
			if ( in_array( $parts['type'], $ignored_types ) ) {
				continue;
			}

			if ( empty( $parts['show_code'] ) ) {
				continue;
			}

			// section header
			if ( ! empty( $sections[ $parts['section'] ] ) ) {
				if ( ! $first_dl ) {
					$html .= '</dl>';

					$open_dl = false;
				}

				$html .= '<h2>' . $sections[ $parts['section'] ] . '</h2>';

				unset( $sections[ $parts['section'] ] );

				$do_used_with = true;
			}

			if ( 'heading' == $parts['type'] ) {
				if ( $open_dl ) {
					$html .= '</dl>';

					$open_dl = false;
				}

				$html .= '<h2>' . $parts['desc'] . '</h2>';

				$do_continue  = true;
				$do_used_with = true;
			}

			if ( $do_used_with ) {
				$used_with_codes = self::get_used_with_codes( $parts );
				if ( ! empty( $used_with_codes ) ) {
					$used_with_codes = implode( '</code>, <code>', $used_with_codes );

					$html .= '<p>' . esc_html__( 'Used with: ', 'testimonials-widget' );
					$html .= '<code>' . $used_with_codes . '</code>';
					$html .= '</p>';
				}

				$do_used_with = false;
			}

			if ( $do_continue ) {
				$do_continue = false;

				continue;
			}

			if ( empty( $open_dl ) ) {
				$html .= '<dl>';

				$first_dl = false;
				$open_dl  = true;
			}

			// option name
			$html .= '<dt>' . $parts['title'] . '</dt>';

			// description
			if ( ! empty( $parts['desc'] ) ) {
				$html .= '<dd>' . $parts['desc'] . '</dd>';
			}

			// validation helpers
			$validate = self::define_options_validate( $parts );
			if ( ! empty( $validate ) ) {
				$html .= '<dd>' . $validate . '</dd>';
			}

			$choices = self::define_options_choices( $parts );
			if ( ! empty( $choices ) ) {
				$html .= '<dd>' . esc_html__( 'Options: ', 'testimonials-widget' ) . '<code>' . $choices . '</code></dd>';
			}

			$value = self::define_options_value( $setting, $parts );
			if ( ! empty( $value ) ) {
				$html .= '<dd>' . esc_html__( 'Usage: ', 'testimonials-widget' ) . '<code>' . $setting . '="' . $value . '"</code></dd>';
			}
		}

		// remaining widgets
		$widgets = array(
			'Testimonials_Widget_Archives_Widget' => 'testimonials_archives',
			'Testimonials_Widget_Categories_Widget' => 'testimonials_categories',
			'Testimonials_Widget_Recent_Testimonials_Widget' => 'testimonials_recent',
			'Testimonials_Widget_Tag_Cloud_Widget' => 'testimonials_tag_cloud',
		);

		$widgets = apply_filters( 'tw_options_widgets', $widgets );

		foreach ( $widgets as $widget => $shortcode ) {
			$form_parts = $widget::form_parts();

			// section header
			$html .= '</dl>';
			$html .= '<h2>' . $widget::$title . '</h2>';

			$used_with_codes = array(
				'[' . $shortcode . ']',
				'' . $shortcode . '()',
			);

			$used_with_codes = apply_filters( 'tw_used_with_codes_widgets', $used_with_codes, $widget, $shortcode );

			if ( ! empty( $used_with_codes ) ) {
				$used_with_codes = implode( '</code>, <code>', $used_with_codes );

				$html .= '<p>' . esc_html__( 'Used with: ', 'testimonials-widget' );
				$html .= '<code>' . $used_with_codes . '</code>';
				$html .= '</p>';
			}

			$html .= '<dl>';

			foreach ( $form_parts as $setting => $parts ) {
				if ( in_array( $parts['type'], $ignored_types ) ) {
					continue;
				}

				// option name
				$html .= '<dt>' . $parts['title'] . '</dt>';

				// description
				if ( ! empty( $parts['desc'] ) ) {
					$html .= '<dd>' . $parts['desc'] . '</dd>';
				}

				// validation helpers
				$validate = self::define_options_validate( $parts );
				if ( ! empty( $validate ) ) {
					$html .= '<dd>' . $validate . '</dd>';
				}

				$choices = self::define_options_choices( $parts );
				if ( ! empty( $choices ) ) {
					$html .= '<dd>' . esc_html__( 'Options: ', 'testimonials-widget' ) . '<code>' . $choices . '</code></dd>';
				}

				$value = self::define_options_value( $setting, $parts );
				if ( ! empty( $value ) ) {
					$html .= '<dd>' . esc_html__( 'Usage: ', 'testimonials-widget' ) . '<code>' . $setting . '="' . $value . '"</code></dd>';
				}
			}

			if ( $open_dl ) {
				$html .= '</dl>';
			}
		}

		if ( $open_dl ) {
			$html .= '</dl>';
		}

		$html = links_add_target( $html, '_tw' );
		$html = apply_filters( 'tw_options_html', $html );

		return $html;
	}


	public static function get_used_with_codes( $parts ) {
		$used_with_codes = array(
			'[testimonials_slider]',
			'testimonials_slider()',
		);

		if ( 'widget' != $parts['section'] ) {
			$used_with_codes[] = '[testimonials]';
			$used_with_codes[] = 'testimonials()';
		}

		$used_with_codes = apply_filters( 'tw_used_with_codes', $used_with_codes, $parts );

		return $used_with_codes;
	}

}

?>
