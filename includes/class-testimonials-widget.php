<?php
/**
 * Copyright 2014 Michael Cannon (email: mc@aihr.us)
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

require_once AIHR_DIR_INC . 'class-aihrus-common.php';
require_once AIHR_DIR_LIB . 'class-redrokk-metabox-class.php';
require_once TW_DIR_INC . 'class-testimonials-widget-settings.php';
require_once TW_DIR_INC . 'class-testimonials-widget-widget.php';

if ( class_exists( 'Testimonials_Widget' ) )
	return;


class Testimonials_Widget extends Aihrus_Common {
	const BASE    = TW_BASE;
	const ID      = 'testimonials-widget-testimonials';
	const SLUG    = 'tw_';
	const VERSION = TW_VERSION;

	const OLD_NAME = 'testimonialswidget';
	const PT       = 'testimonials-widget';

	private static $found_posts   = 0;
	private static $max_num_pages = 0;
	private static $post_count    = 0;
	private static $wp_query;

	public static $class           = __CLASS__;
	public static $cpt_category    = '';
	public static $cpt_tags        = '';
	public static $css             = array();
	public static $css_called      = false;
	public static $instance_number = 0;
	public static $instance_widget = 0;
	public static $menu_shortcodes;
	public static $not_found = false;
	public static $notice_key;
	public static $plugin_assets;
	public static $scripts         = array();
	public static $scripts_called  = false;
	public static $settings_link   = '';
	public static $tag_close_quote = '<span class="close-quote"></span>';
	public static $tag_open_quote  = '<span class="open-quote"></span>';
	public static $use_instance    = false;
	public static $widget_number   = 100000;

	public static $agg_count  = 'reviewCount';
	public static $agg_schema = 'http://schema.org/AggregateRating';

	public static $cw_author     = 'author';
	public static $cw_date       = 'datePublished';
	public static $cw_date_mod   = 'dateModified';
	public static $cw_aggregate  = 'aggregateRating';
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
	public static $schema_span      = '<span itemprop="%1$s">%2$s</span>';

	public static $thing_image  = 'image';
	public static $thing_name   = 'name';
	public static $thing_schema = 'http://schema.org/Thing';
	public static $thing_url    = 'url';


	public function __construct() {
		parent::__construct();

		self::$plugin_assets = plugins_url( '/assets/', dirname( __FILE__ ) );
		self::$plugin_assets = self::strip_protocol( self::$plugin_assets );

		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		add_action( 'dashboard_glance_items', array( __CLASS__, 'dashboard_glance_items' ) );
		add_action( 'init', array( __CLASS__, 'init' ) );
		add_action( 'widgets_init', array( __CLASS__, 'widgets_init' ) );
		add_shortcode( 'testimonials', array( __CLASS__, 'testimonials' ) );
		add_shortcode( 'testimonialswidget_list', array( __CLASS__, 'testimonialswidget_list' ) );
		add_shortcode( 'testimonialswidget_widget', array( __CLASS__, 'testimonialswidget_widget' ) );
		add_shortcode( 'testimonials_slider', array( __CLASS__, 'testimonials_slider' ) );
	}


	public static function admin_init() {
		self::support_thumbnails();

		self::$settings_link = '<a href="' . get_admin_url() . 'edit.php?post_type=' . self::PT . '&page=' . Testimonials_Widget_Settings::ID . '">' . esc_html__( 'Settings', 'testimonials-widget' ) . '</a>';

		self::add_meta_box_testimonials_widget();
		self::update();

		add_action( 'gettext', array( __CLASS__, 'gettext_testimonials' ) );
		add_action( 'manage_' . self::PT . '_posts_custom_column', array( __CLASS__, 'manage_posts_custom_column' ), 10, 2 );
		add_action( 'right_now_content_table_end', array( __CLASS__, 'right_now_content_table_end' ) );
		add_filter( 'manage_' . self::PT . '_posts_columns', array( __CLASS__, 'manage_posts_columns' ) );
		add_filter( 'plugin_action_links', array( __CLASS__, 'plugin_action_links' ), 10, 2 );
		add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 2 );
		add_filter( 'post_updated_messages', array( __CLASS__, 'post_updated_messages' ) );
		add_filter( 'pre_get_posts', array( __CLASS__, 'pre_get_posts_author' ) );

		if ( self::do_load() ) {
			add_filter( 'manage_category_custom_column', array( __CLASS__, 'category_column' ), 10, 3 );
			add_filter( 'manage_edit-category_columns', array( __CLASS__, 'category_columns' ) );
			add_filter( 'manage_edit-post_tag_columns', array( __CLASS__, 'category_columns' ) );
			add_filter( 'manage_edit-testimonials-widget-category_columns', array( __CLASS__, 'category_columns' ) );
			add_filter( 'manage_edit-testimonials-widget-post_tag_columns', array( __CLASS__, 'category_columns' ) );
			add_filter( 'manage_post_tag_custom_column', array( __CLASS__, 'post_tag_column' ), 10, 3 );
			add_filter( 'manage_testimonials-widget-category_custom_column', array( __CLASS__, 'category_column' ), 10, 3 );
			add_filter( 'manage_testimonials-widget-post_tag_custom_column', array( __CLASS__, 'post_tag_column' ), 10, 3 );
		}
	}


	public static function admin_menu() {
		self::$menu_shortcodes = add_submenu_page( 'edit.php?post_type=' . self::PT, esc_html__( 'Testimonials Shortcode Examples', 'testimonials-widget' ), esc_html__( 'E.g. Shortcodes', 'testimonials-widget' ), 'manage_options', 'shortcodes', array( __CLASS__, 'show_shortcodes' ) );
	}


	public static function init() {
		add_filter( 'the_content', array( __CLASS__, 'get_single' ), -1 );

		load_plugin_textdomain( self::PT, false, 'testimonials-widget/languages' );

		self::$cpt_category = self::PT . '-category';
		self::$cpt_tags     = self::PT . '-post_tag';

		self::init_post_type();
		self::styles();
	}


	public static function plugin_action_links( $links, $file ) {
		if ( self::BASE == $file )
			array_unshift( $links, self::$settings_link );

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

		if ( ! is_single() || self::PT != $post->post_type )
			return $content;

		$atts                 = self::get_defaults( true );
		$atts['hide_content'] = 1;
		$atts['ids']          = $post->ID;
		$atts['type']         = 'get_single';

		$instance              = self::add_instance();
		$atts['widget_number'] = $instance;

		$testimonials = array();

		$text = apply_filters( 'testimonials_widget_cache_get', false, $atts );
		if ( false === $text ) {
			$testimonials = self::get_testimonials( $atts );
			$testimonial  = $testimonials[0];

			$details = self::get_testimonial_html( $testimonial, $atts );
			$details = apply_filters( 'testimonials_widget_testimonial_html_single', $details, $testimonial, $atts );

			$content = apply_filters( 'testimonials_widget_testimonial_html_single_content', $content, $testimonial, $atts );

			$text = $content . $details;
			$text = apply_filters( 'testimonials_widget_cache_set', $text, $atts );
		}

		self::call_scripts_styles( $testimonials, $atts, $instance );

		return $text;
	}


	public static function activation() {
		if ( ! current_user_can( 'activate_plugins' ) )
			return;

		self::init();
		flush_rewrite_rules();
	}


	public static function deactivation() {
		if ( ! current_user_can( 'activate_plugins' ) )
			return;

		flush_rewrite_rules();
	}


	public static function uninstall() {
		if ( ! current_user_can( 'activate_plugins' ) )
			return;

		global $wpdb;
		
		require_once TW_DIR_INC . 'class-testimonials-widget-settings.php';

		$delete_data = tw_get_option( 'delete_data', false );
		if ( $delete_data ) {
			delete_option( self::OLD_NAME );
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
		if ( self::BASE != $file )
			return $input;

		$disable_donate = tw_get_option( 'disable_donate' );
		if ( $disable_donate )
			return $input;

		$links = array(
			self::$donate_link,
		);

		global $TW_Premium;
		if ( ! isset( $TW_Premium ) )
			$links[] = TW_PREMIUM_LINK;

		$input = array_merge( $input, $links );

		return $input;
	}


	public static function notice_2_12_0() {
		$text = sprintf( __( 'If your Testimonials display has gone to funky town, please <a href="%s">read the FAQ</a> about possible CSS fixes.', 'testimonials-widget' ), esc_url( 'https://aihrus.zendesk.com/entries/23722573-Major-Changes-Since-2-10-0' ) );

		aihr_notice_updated( $text );
	}


	public static function notice_donate( $disable_donate = null, $item_name = null ) {
		$disable_donate = tw_get_option( 'disable_donate' );

		parent::notice_donate( $disable_donate, TW_NAME );
	}


	public static function update() {
		$prior_version = tw_get_option( 'admin_notices' );
		if ( $prior_version ) {
			if ( $prior_version < '2.12.0' )
				self::set_notice( 'notice_2_12_0' );

			if ( $prior_version < '2.15.0' )
				self::set_notice( 'notice_2_15_0' );

			if ( $prior_version < self::VERSION ) {
				tw_requirements_check( true );
				do_action( 'testimonials_widget_update' );
			}

			tw_set_option( 'admin_notices' );
		}

		// display donate on major/minor version release
		$donate_version = tw_get_option( 'donate_version', false );
		if ( ! $donate_version || ( $donate_version != self::VERSION && preg_match( '#\.0$#', self::VERSION ) ) ) {
			self::set_notice( 'notice_donate' );
			tw_set_option( 'donate_version', self::VERSION );
		}

		$options = get_option( self::OLD_NAME );
		if ( true !== $options['migrated'] )
			self::migrate();
	}


	public static function migrate() {
		global $wpdb;

		$table_name       = $wpdb->prefix . self::OLD_NAME;
		$meta_key         = '_' . self::PT . ':testimonial_id';
		$has_table_query  = "SELECT table_name FROM information_schema.tables WHERE table_schema='{$wpdb->dbname}' AND table_name='{$table_name}'";
		$has_table_result = $wpdb->get_col( $has_table_query );

		if ( ! empty( $has_table_result ) ) {
			// check that db table exists and has entries
			$query = 'SELECT `testimonial_id`, `testimonial`, `author`, `source`, `tags`, `public`, `time_added`, `time_updated` FROM `' . $table_name . '`';

			// ignore already imported
			$done_import_query = 'SELECT meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key = "' . $meta_key . '"';
			$done_import       = $wpdb->get_col( $done_import_query );

			if ( ! empty( $done_import ) ) {
				$done_import = array_unique( $done_import );
				$query      .= ' WHERE testimonial_id NOT IN ( ' . implode( ',', $done_import ) . ' )';
			}

			$results = $wpdb->get_results( $query );
			if ( ! empty( $results ) ) {
				foreach ( $results as $result ) {
					// author can contain title and company details
					$author  = $result->author;
					$company = false;

					// ex: First Last of Company!
					$author = str_replace( ' of ', ', ', $author );
					// now ex: First Last, Company!

					// ex: First Last, Company
					// ex: First Last, Web Development Manager, Topcon Positioning Systems, Inc.
					// ex: First Last, Owner, Company, LLC
					$author     = str_replace( ' of ', ', ', $author );
					$temp_comma = '^^^';
					$author     = str_replace( ', LLC', $temp_comma . ' LLC', $author );

					// now ex: First Last, Owner, Company^^^ LLC
					$author = str_replace( ', Inc', $temp_comma . ' Inc', $author );

					// ex: First Last, Web Development Manager, Company^^^ Inc.
					// it's possible to have "Michael Cannon, Senior Developer" and "Senior Developer" become the company. Okay for now
					$author = explode( ', ', $author );

					if ( 1 < count( $author ) ) {
						$company = array_pop( $author );
						$company = str_replace( $temp_comma, ',', $company );
					}

					$author = implode( ', ', $author );
					$author = str_replace( $temp_comma, ',', $author );

					$post_data = array(
						'post_type' => self::PT,
						'post_status' => ( 'yes' == $result->public ) ? 'publish' : 'private',
						'post_date' => $result->time_added,
						'post_modified' => $result->time_updated,
						'post_title' => $author,
						'post_content' => $result->testimonial,
						'tags_input' => $result->tags,
					);

					$post_id = wp_insert_post( $post_data, true );

					// track/link testimonial import to new post
					add_post_meta( $post_id, $meta_key, $result->testimonial_id );

					if ( ! empty( $company ) )
						add_post_meta( $post_id, 'testimonials-widget-company', $company );

					$source = $result->source;
					if ( ! empty( $source ) ) {
						if ( is_email( $source ) ) {
							add_post_meta( $post_id, 'testimonials-widget-email', $source );
						} else {
							add_post_meta( $post_id, 'testimonials-widget-url', $source );
						}
					}
				}
			}
		}

		$options['migrated'] = true;
		delete_option( self::OLD_NAME );
		add_option( self::OLD_NAME, $options, '', 'no' );
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

			case 'testimonials-widget-company':
			case 'testimonials-widget-location':
			case 'testimonials-widget-title':
				$result = get_post_meta( $post_id, $column, true );
				break;

			case 'testimonials-widget-email':
			case 'testimonials-widget-url':
				$url = get_post_meta( $post_id, $column, true );
				if ( ! empty( $url ) && ! is_email( $url ) && 0 === preg_match( '#https?://#', $url ) )
					$url = 'http://' . $url;

				$result = make_clickable( $url );
				break;

			case 'thumbnail':
				$email = get_post_meta( $post_id, 'testimonials-widget-email', true );

				if ( has_post_thumbnail( $post_id ) )
					$result = get_the_post_thumbnail( $post_id, 'thumbnail' );
				elseif ( is_email( $email ) )
					$result = get_avatar( $email );
				else
					$result = false;
				break;

			case self::$cpt_category:
			case self::$cpt_tags:
				$terms  = get_the_terms( $post_id, $column );
				$result = '';
				if ( ! empty( $terms ) ) {
					$out = array();
					foreach ( $terms as $term )
						$out[] = '<a href="' . admin_url( 'edit-tags.php?action=edit&taxonomy=' . $column . '&tag_ID=' . $term->term_id . '&post_type=' . self::PT ) . '">' . $term->name . '</a>';

					$result = join( ', ', $out );
				}
				break;
		}

		$result = apply_filters( 'testimonials_widget_posts_custom_column', $result, $column, $post_id );

		if ( $result )
			echo $result;
	}


	public static function manage_posts_columns( $columns ) {
		// order of keys matches column ordering
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => esc_html__( 'Source', 'testimonials-widget' ),
			'author' => esc_html__( 'Published by', 'testimonials-widget' ),
			'date' => esc_html__( 'Date', 'testimonials-widget' ),
		);

		$use_cpt_taxonomy = tw_get_option( 'use_cpt_taxonomy', false );
		if ( ! $use_cpt_taxonomy ) {
			$columns[ 'categories' ] = esc_html__( 'Category', 'testimonials-widget' );
			$columns[ 'tags' ]       = esc_html__( 'Tags', 'testimonials-widget' );
		} else {
			$columns[ self::$cpt_category ] = esc_html__( 'Category', 'testimonials-widget' );
			$columns[ self::$cpt_tags ]     = esc_html__( 'Tags', 'testimonials-widget' );
		}

		$show_id = tw_get_option( 'columns_id' );
		if ( empty( $show_id ) ) {
			$columns[ 'id' ] = esc_html__( 'ID', 'testimonials-widget' );
		}

		$show_thumbnail = tw_get_option( 'columns_thumbnail' );
		if ( empty( $show_thumbnail ) ) {
			$columns[ 'thumbnail' ] = esc_html__( 'Image', 'testimonials-widget' );
		}

		$show_shortcode = tw_get_option( 'columns_shortcode' );
		if ( empty( $show_shortcode ) ) {
			$columns[ 'shortcode' ] = esc_html__( 'Shortcodes', 'testimonials-widget' );
		}

		$show_job_title = tw_get_option( 'columns_job_title' );
		if ( empty( $show_job_title ) ) {
			$columns[ 'testimonials-widget-title' ] = esc_html__( 'Job Title', 'testimonials-widget' );
		}

		$show_location = tw_get_option( 'columns_location' );
		if ( empty( $show_location ) ) {
			$columns[ 'testimonials-widget-location' ] = esc_html__( 'Location', 'testimonials-widget' );
		}

		$show_email = tw_get_option( 'columns_email' );
		if ( empty( $show_email ) ) {
			$columns[ 'testimonials-widget-email' ] = esc_html__( 'Email', 'testimonials-widget' );
		}

		$show_company = tw_get_option( 'columns_company' );
		if ( empty( $show_company ) ) {
			$columns[ 'testimonials-widget-company' ] = esc_html__( 'Company', 'testimonials-widget' );
		}

		$show_url = tw_get_option( 'columns_url' );
		if ( empty( $show_url ) ) {
			$columns[ 'testimonials-widget-url' ] = esc_html__( 'URL', 'testimonials-widget' );
		}

		$columns = apply_filters( 'testimonials_widget_columns', $columns );

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
		if ( $allow_comments )
			$supports[] = 'comments';

		$has_archive      = tw_get_option( 'has_archive', true );
		$rewrite_slug     = tw_get_option( 'rewrite_slug', 'testimonial' );
		$use_cpt_taxonomy = tw_get_option( 'use_cpt_taxonomy', false );

		// editor's and up
		if ( current_user_can( 'edit_others_posts' ) )
			$supports[] = 'author';

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
		register_taxonomy( self::$cpt_category, self::PT, $args );

		$args = array(
			'show_admin_column' => true,
			'update_count_callback' => '_update_post_term_count',
		);
		register_taxonomy( self::$cpt_tags, self::PT, $args );
	}


	public static function get_defaults( $single_view = false ) {
		$options = tw_get_options();
		if ( empty( $single_view ) ) {
			$defaults = apply_filters( 'testimonials_widget_defaults', $options );
		} else {
			$defaults = apply_filters( 'testimonials_widget_defaults_single', $options );
		}

		return $defaults;
	}


	public static function testimonialswidget_list( $atts ) {
		_deprecated_function( __FUNCTION__, '2.19.0', 'testimonials()' );

		return self::testimonials( $atts );
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

		$content = apply_filters( 'testimonials_widget_cache_get', false, $atts );
		if ( false === $content ) {
			$testimonials = self::get_testimonials( $atts );
			$content      = self::get_testimonials_html( $testimonials, $atts );
			$content      = apply_filters( 'testimonials_widget_cache_set', $content, $atts );
		}

		self::call_scripts_styles( $testimonials, $atts, $instance );

		return $content;
	}


	public static function testimonialswidget_widget( $atts, $widget_number = null ) {
		_deprecated_function( __FUNCTION__, '2.19.0', 'testimonials_slider()' );

		return self::testimonials_slider( $atts, $widget_number );
	}


	public static function testimonials_slider( $atts, $widget_number = null ) {
		if ( empty( $widget_number ) ) {
			$widget_number = self::$widget_number++;

			if ( ! isset( $atts['random'] ) )
				$atts['random'] = 1;

			if ( ! isset( $atts['enable_schema'] ) )
				$atts['enable_schema'] = 0;
		}

		$atts = wp_parse_args( $atts, self::get_defaults() );
		$atts = Testimonials_Widget_Settings::validate_settings( $atts );

		$atts['paging'] = false;
		$atts['type']   = 'testimonials_slider';

		self::set_instance( $widget_number );
		$atts['widget_number'] = $widget_number;

		$testimonials = array();

		$content = apply_filters( 'testimonials_widget_cache_get', false, $atts );
		if ( false === $content ) {
			$testimonials = self::get_testimonials( $atts );
			$content      = self::get_testimonials_html( $testimonials, $atts, false, $widget_number );
			$content      = apply_filters( 'testimonials_widget_cache_set', $content, $atts );
		}

		self::call_scripts_styles( $testimonials, $atts, $widget_number );

		return $content;
	}


	public static function scripts( $atts ) {
		if ( is_admin() )
			return;

		wp_enqueue_script( 'jquery' );

		$use_bxslider = $atts['use_bxslider'];
		if ( $use_bxslider ) {
			$enable_video = $atts['enable_video'];
			if ( $enable_video ) {
				wp_register_script( 'jquery.fitvids', self::$plugin_assets . 'js/jquery.fitvids.js', array( 'jquery' ), '1.0', true );
				wp_enqueue_script( 'jquery.fitvids' );
			}

			wp_register_script( 'jquery.bxslider', self::$plugin_assets . 'js/jquery.bxslider.js', array( 'jquery' ), '4.1.1', true );
			wp_enqueue_script( 'jquery.bxslider' );
		}

		do_action( 'testimonials_widget_scripts', $atts );
	}


	public static function styles() {
		if ( is_admin() )
			return;

		$use_bxslider = tw_get_option( 'use_bxslider' );
		if ( $use_bxslider ) {
			$exclude_bxslider_css = tw_get_option( 'exclude_bxslider_css' );
			if ( empty( $exclude_bxslider_css ) ) {
				wp_register_style( 'jquery.bxslider', self::$plugin_assets . 'css/jquery.bxslider.css' );
				wp_enqueue_style( 'jquery.bxslider' );
			}

			wp_register_style( __CLASS__, self::$plugin_assets . 'css/testimonials-widget.css' );
		} else {
			wp_register_style( __CLASS__, self::$plugin_assets . 'css/testimonials-widget-2.14.0.css' );

			$include_ie7_css = tw_get_option( 'include_ie7_css' );
			if ( $include_ie7_css ) {
				wp_register_style( __CLASS__ . '-ie7', self::$plugin_assets . 'css/testimonials-widget-ie7.css' );
				wp_enqueue_style( __CLASS__ . '-ie7' );
			}
		}

		wp_enqueue_style( __CLASS__ );

		do_action( 'testimonials_widget_styles' );
	}


	public static function get_testimonials_html_css( $atts, $widget_number = null ) {
		$css     = array();
		$id_base = self::ID . $widget_number;

		switch ( $atts['type'] ) {
			case 'testimonials_slider':
				$use_bxslider = $atts['use_bxslider'];
				if ( ! $use_bxslider ) {
					$height     = $atts['height'];
					$max_height = $atts['max_height'];
					$min_height = $atts['min_height'];

					if ( $height ) {
						$max_height = $height;
						$min_height = $height;
					}

					if ( $min_height ) {
						$css[] = <<<EOF
<style>
.$id_base {
min-height: {$min_height}px;
}
</style>
EOF;
					}

					if ( $max_height ) {
						$css[] = <<<EOF
<style>
.$id_base {
	max-height: {$max_height}px;
}
</style>
EOF;
					}
				}
				break;
		}

		$css = apply_filters( 'testimonials_widget_testimonials_css', $css, $atts, $widget_number );

		return $css;
	}


	public static function get_testimonials_html_js( $testimonials, $atts, $widget_number = null ) {
		$not_found = self::get_not_found();
		if ( $not_found )
			return;

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

					$use_bxslider = $atts['use_bxslider'];
					if ( $use_bxslider ) {
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
					} else {
						$tw_padding = 'tw_padding' . $widget_number;
						$tw_wrapper = 'tw_wrapper' . $widget_number;

						$disable_animation = $atts['disable_animation'];
						$fade_in_speed     = $atts['fade_in_speed'];
						$fade_out_speed    = $atts['fade_out_speed'];
						$height            = $atts['height'];
						$max_height        = $atts['max_height'];
						$min_height        = $atts['min_height'];

						$enable_animation = 1;
						if ( $disable_animation || $height || $max_height || $min_height )
							$enable_animation = 0;

						if ( $refresh_interval ) {
							$javascript .= <<<EOF
function nextTestimonial{$widget_number}() {
	if ( ! jQuery('.{$id_base}').first().hasClass('hovered') ) {
		var active = jQuery('.{$id_base} .active');
		var next   = (jQuery('.{$id_base} .active').next().length > 0) ? jQuery('.{$id_base} .active').next() : jQuery('.{$id_base} .testimonials-widget-testimonial:first-child');

		active.fadeOut({$fade_out_speed}, function() {
			active.removeClass('active');
			next.fadeIn({$fade_in_speed});
			next.removeClass('display-none');
			next.addClass('active');

			{INTERNAL_SCRIPTS}

			// added padding
			if ( {$enable_animation} )
				{$tw_wrapper}.animate({ height: next.height() + {$tw_padding} });
		});
	}
}

jQuery(document).ready(function() {
	jQuery('.{$id_base}').hover(function() {
		jQuery(this).addClass('hovered')
	}, function() {
		jQuery(this).removeClass('hovered')
	});

	nextTestimonial{$widget_number}interval = setInterval('nextTestimonial{$widget_number}()', {$refresh_interval} * 1000);
});

EOF;
						}

						$javascript .= <<<EOF
if ( {$enable_animation} ) {
	var {$tw_wrapper} = jQuery('.{$id_base}');
	var {$tw_padding} = 0;

	jQuery(document).ready(function() {
		// tw_padding is the difference in height to take into account all styling options
		{$tw_padding} = {$tw_wrapper}.height() - jQuery('.{$id_base} .testimonials-widget-testimonial').height();

		// fixes first animation by defining height to adjust to
		{$tw_wrapper}.height( {$tw_wrapper}.height() );
	});
}
EOF;
					}

					$javascript         .= "\n" . '</script>';
					$scripts[ $id_base ] = $javascript;
				}
				break;
		}

		$scripts          = apply_filters( 'testimonials_widget_testimonials_js', $scripts, $testimonials, $atts, $widget_number );
		$scripts_internal = apply_filters( 'testimonials_widget_testimonials_js_internal', $scripts_internal, $testimonials, $atts, $widget_number );
		$internal_scripts = implode( "\n", $scripts_internal );
		$scripts          = str_replace( '{INTERNAL_SCRIPTS}', $internal_scripts, $scripts );

		return $scripts;
	}


	public static function get_testimonials_html( $testimonials, $atts, $is_list = true, $widget_number = null ) {
		$hide_not_found = $atts['hide_not_found'];
		$paging         = Testimonials_Widget_Settings::is_true( $atts['paging'] );
		$paging_before  = ( 'before' === strtolower( $atts['paging'] ) );
		$paging_after   = ( 'after' === strtolower( $atts['paging'] ) );
		$target         = $atts['target'];

		$id = self::ID;

		if ( is_null( $widget_number ) ) {
			$div_open = '<div class="' . $id;

			if ( $is_list )
				$div_open .= ' listing';

			$div_open .= '">';
		} else {
			$id_base  = $id . $widget_number;
			$div_open = '<div class="' . $id . ' ' . $id_base . '">';
		}

		$div_open .= "\n";
		if ( empty( $testimonials ) && ! $hide_not_found ) {
			$testimonials = array(
				array( 'testimonial_content' => esc_html__( 'No testimonials found', 'testimonials-widget' ) ),
			);

			self::set_not_found( true );
		} else {
			self::set_not_found();
		}

		$pre_paging = '';
		if ( $paging || $paging_before ) {
			$pre_paging = self::get_testimonials_paging( $atts );
		}

		$is_first = true;

		$testimonial_content = '';
		foreach ( $testimonials as $testimonial ) {
			$content = self::get_testimonial_html( $testimonial, $atts, $is_list, $is_first, $widget_number );
			if ( $target ) {
				$content = links_add_target( $content, $target );
			}

			$content  = apply_filters( 'testimonials_widget_testimonial_html', $content, $testimonial, $atts, $is_list, $is_first, $widget_number );
			$is_first = false;

			$testimonial_content .= $content;
		}

		$post_paging = '';
		if ( $paging || $paging_after )
			$post_paging = self::get_testimonials_paging( $atts, false );

		$div_close  = '</div>';
		$div_close .= "\n";

		$html = $div_open
			. $pre_paging
			. $testimonial_content
			. $post_paging
			. $div_close;

		$html = apply_filters( 'testimonials_widget_get_testimonials_html', $html, $testimonials, $atts, $is_list, $widget_number, $div_open, $pre_paging, $testimonial_content, $post_paging, $div_close );

		return $html;
	}


	public static function get_testimonial_html( $testimonial, $atts, $is_list = true, $is_first = false, $widget_number = null ) {
		$disable_quotes  = $atts['disable_quotes'];
		$do_image        = ! $atts['hide_image'] && ! empty( $testimonial['testimonial_image'] );
		$do_image_single = ! $atts['hide_image_single'];
		$do_schema       = $atts['enable_schema'];
		$keep_whitespace = $atts['keep_whitespace'];
		$remove_hentry   = $atts['remove_hentry'];
		$transition_mode = $atts['transition_mode'];
		$use_bxslider    = $atts['use_bxslider'];

		$class = 'testimonials-widget-testimonial';
		if ( is_single() && empty( $widget_number ) ) {
			$class .= ' single';
		} elseif ( $is_list ) {
			$class .= ' list';
		} else {
			// widget display
			if ( $use_bxslider ) {
				$refresh_interval = $atts['refresh_interval'];
				if ( ! $is_first && ! empty( $refresh_interval ) && ! in_array( $transition_mode, array( 'horizontal', 'vertical' ) ) ) {
					$class .= ' display-none';
				}
			} else {
				if ( $is_first ) {
					$class .= ' active';
				} else {
					$class .= ' display-none';
				}
			}
		}

		if ( $keep_whitespace )
			$class .= ' whitespace';

		$post_id = $testimonial['post_id'];
		if ( ! empty( $post_id ) )
			$class = join( ' ', get_post_class( $class, $post_id ) );
		else
			$class = 'testimonials-widget type-testimonials-widget status-publish hentry ' . $class;

		$class     = apply_filters( 'testimonials_widget_get_testimonial_html_class', $class, $testimonial, $atts, $is_list, $is_first, $widget_number );
		$div_open  = '<div class="' . $class . '">';
		$div_open .= '<!-- ' . self::ID . ":{$post_id}: -->";

		if ( $do_schema ) {
			$div_open .= "\n";
			$div_open .= sprintf( self::$schema_div_open, self::$review_schema );
		}

		if ( $remove_hentry )
			$div_open = str_replace( ' hentry', '', $div_open );

		$image = '';
		if ( $do_image ) {
			$pic = $testimonial['testimonial_image'];

			$image .= '<span class="image">';
			$image .= $pic;
			$image .= '</span>';
		}

		if ( ! $do_image_single && 'get_single' == $atts['type'] )
			$image = '';

		$quote = self::get_quote( $testimonial, $atts, $widget_number );

		$cite = '';
		if ( 1 < count( $testimonial ) ) {
			$cite = self::get_cite( $testimonial, $atts );

			if ( $do_schema ) {
				$schema = self::get_schema( $testimonial, $atts );
				$cite  .= $schema;
			}
		}

		$extra = '';
		if ( ! empty( $testimonial['testimonial_extra'] ) ) {
			$extra .= '<div class="extra">';
			$extra .= $testimonial['testimonial_extra'];
			$extra .= '</div>';
			$extra .= "\n";
		}

		$bottom_text = '';
		if ( ! empty( $atts['bottom_text'] ) ) {
			$bottom_text  = '<div class="bottom_text">';
			$bottom_text .= $atts['bottom_text'];
			$bottom_text .= '</div>';
			$bottom_text .= "\n";
		}

		$div_close = '';
		if ( $do_schema ) {
			$div_close .= '</div>';
			$div_close .= "\n";
		}

		$div_close .= '</div>';
		$div_close .= "\n";

		$html = $div_open
			. $image
			. $quote
			. $cite
			. $extra
			. $bottom_text
			. $div_close;

		$html = apply_filters( 'testimonials_widget_get_testimonial_html', $html, $testimonial, $atts, $is_list, $is_first, $widget_number, $div_open, $image, $quote, $cite, $extra, $bottom_text, $div_close );

		// not done sooner as tag_close_quote is used for Premium
		if ( $disable_quotes ) {
			$html = str_replace( self::$tag_open_quote, '', $html );
			$html = str_replace( self::$tag_close_quote, '', $html );
		}

		return $html;
	}


	public static function get_quote( $testimonial, $atts, $widget_number ) {
		$char_limit    = $atts['char_limit'];
		$content_more  = apply_filters( 'testimonials_widget_content_more', esc_html__( '…', 'testimonials-widget' ) );
		$content_more .= self::$tag_close_quote;
		$do_content    = ! $atts['hide_content'] && ! empty( $testimonial['testimonial_content'] );
		$use_quote_tag = $atts['use_quote_tag'];

		$quote = '';
		if ( $do_content ) {
			$content = $testimonial['testimonial_content'];
			$content = self::format_content( $content, $widget_number, $atts );
			if ( $char_limit ) {
				$content = self::testimonials_truncate( $content, $char_limit, $content_more );
				$content = force_balance_tags( $content );
			}

			$content = apply_filters( 'testimonials_widget_content', $content, $widget_number, $testimonial, $atts );
			$content = make_clickable( $content );

			if ( ! $use_quote_tag ) {
				$quote  = '<blockquote>';
				$quote .= $content;
				$quote .= '</blockquote>';
			} else {
				$quote  = '<q>';
				$quote .= $content;
				$quote .= '</q>';
			}
			
			$quote = "\n" . $quote;
		}

		return $quote;
	}


	public static function get_cite( $testimonial, $atts ) {
		extract( $testimonial );

		$do_company    = ! $atts['hide_company'] && ! empty( $testimonial_company );
		$do_email      = ! $atts['hide_email'] && ! empty( $testimonial_email ) && is_email( $testimonial_email );
		$do_location   = ! $atts['hide_location'] && ! empty( $testimonial_location );
		$do_source     = ! $atts['hide_source'] && ! empty( $testimonial_source );
		$do_title      = ! $atts['hide_title'] && ! empty( $testimonial_title );
		$do_url        = ! $atts['hide_url'] && ! empty( $testimonial_url );
		$use_quote_tag = $atts['use_quote_tag'];

		$cite = '';

		$done_url = false;
		if ( $do_source && $do_email ) {
			$cite .= '<span class="author">';
			$cite .= '<a href="mailto:' . $testimonial_email . '">';
			$cite .= $testimonial_source;
			$cite .= '</a>';
			$cite .= '</span>';
		} elseif ( $do_source && ! $do_company && $do_url ) {
			$done_url = true;

			$cite .= '<span class="author">';
			$cite .= '<a href="' . $testimonial_url . '" rel="nofollow">';
			$cite .= $testimonial_source;
			$cite .= '</a>';
			$cite .= '</span>';
		} elseif ( $do_source ) {
			$cite .= '<span class="author">';
			$cite .= $testimonial_source;
			$cite .= '</span>';
		} elseif ( $do_email ) {
			$cite .= '<span class="email">';
			$cite .= make_clickable( $testimonial_email );
			$cite .= '</span>';
		}

		if ( $do_title && $cite )
			$cite .= '<span class="join-title"></span>';

		if ( $do_title ) {
			$cite .= '<span class="job-title">';
			$cite .= $testimonial_title;
			$cite .= '</span>';
		}

		if ( $do_location && $cite )
			$cite .= '<span class="join-location"></span>';

		if ( $do_location ) {
			$cite .= '<span class="location">';
			$cite .= $testimonial_location;
			$cite .= '</span>';
		}

		if ( ( $do_company || ( $do_url && ! $done_url ) ) && $cite )
			$cite .= '<span class="join"></span>';

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

		$cite = apply_filters( 'testimonials_widget_cite_html', $cite, $testimonial, $atts );

		if ( ! empty( $cite ) ) {
			if ( ! $use_quote_tag ) {
				$temp  = '<div class="credit">';
				$temp .= $cite;
				$temp .= '</div>';

				$cite = "\n" . $temp . "\n";
			} else {
				$cite = '<cite>' . $cite . '</cite>';
			}
		}

		return $cite;
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


	public static function format_content( $content, $widget_number, $atts ) {
		if ( empty ( $content ) )
			return $content;

		$keep_whitespace = $atts['keep_whitespace'];
		$do_shortcode    = $atts['do_shortcode'];
		$enable_video    = $atts['enable_video'];

		// wrap our own quote class around the content before any formatting
		// happens

		$temp_content  = self::$tag_open_quote;
		$temp_content .= $content;
		$temp_content .= self::$tag_close_quote;

		$content = $temp_content;
		$content = trim( $content );
		$content = wptexturize( $content );
		$content = convert_smilies( $content );
		$content = convert_chars( $content );

		if ( $enable_video && ! empty( $GLOBALS['wp_embed'] ) ) {
			$content = $GLOBALS['wp_embed']->run_shortcode( $content );
		}

		if ( $do_shortcode ) {
			$content = do_shortcode( $content );
		} else {
			$content = strip_shortcodes( $content );
		}

		if ( is_null( $widget_number ) || $keep_whitespace ) {
			$content = wpautop( $content );
		}

		$content = shortcode_unautop( $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = trim( $content );

		return $content;
	}


	public static function get_testimonials_paging( $atts, $prepend = true ) {
		$html = '';

		if ( is_home() || 1 === self::$max_num_pages )
			return $html;

		$html .= '<div class="paging';

		if ( $prepend )
			$html .= ' prepend';
		else
			$html .= ' append';

		$html .= '">';
		$html .= "\n";

		if ( $atts['paged'] )
			$paged = $atts['paged'];
		else
			$paged = 1;

		if ( ! function_exists( 'wp_pagenavi' ) ) {
			$html .= '<div class="alignleft">';

			if ( 1 < $paged ) {
				$laquo = apply_filters( 'testimonials_widget_previous_posts_link_text', esc_html__( '&laquo;', 'testimonials-widget' ) );
				$html .= get_previous_posts_link( $laquo, $paged );
			}

			$html .= '</div>';
			$html .= "\n";
			$html .= '<div class="alignright">';
			if ( $paged != self::$max_num_pages ) {
				$raquo = apply_filters( 'testimonials_widget_next_posts_link_text', esc_html__( '&raquo;', 'testimonials-widget' ) );
				$html .= get_next_posts_link( $raquo, self::$max_num_pages );
			}

			$html .= '</div>';
			$html .= "\n";
		} else {
			$args = array(
				'echo' => false,
				'query' => self::$wp_query,
			);
			$args = apply_filters( 'testimonials_widget_wp_pagenavi', $args );

			$html .= wp_pagenavi( $args );
			$html .= "\n";
		}

		$html .= '</div>';
		$html .= "\n";

		return $html;
	}


	public static function get_testimonials_css() {
		if ( empty( self::$css_called ) ) {
			foreach ( self::$css as $css )
				echo $css;

			self::$css_called = true;
		}
	}


	public static function get_testimonials_scripts() {
		if ( empty( self::$scripts_called ) ) {
			foreach ( self::$scripts as $script )
				echo $script;

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

		if ( has_filter( 'posts_orderby', 'CPTOrderPosts' ) )
			remove_filter( 'posts_orderby', 'CPTOrderPosts', 99, 2 );

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

		if ( $paging && ! empty( $atts['paged'] ) && is_singular() )
			$args['paged'] = $atts['paged'];

		if ( ! $random && $meta_key ) {
			$args['meta_key'] = $meta_key;
			$args['orderby']  = 'meta_value';
		}

		if ( $order )
			$args['order'] = $order;

		if ( $ids ) {
			$ids = explode( ',', $ids );

			$args['post__in'] = $ids;

			if ( 'none' == $args['orderby'] )
				add_filter( 'posts_results', array( __CLASS__, 'posts_results_sort_none' ), 10, 2 );
		}

		if ( $exclude ) {
			$exclude              = explode( ',', $exclude );
			$args['post__not_in'] = $exclude;
		}

		$use_cpt_taxonomy = tw_get_option( 'use_cpt_taxonomy', false );
		if ( ! $use_cpt_taxonomy ) {
			if ( $category )
				$args['category_name'] = $category;

			if ( $tags ) {
				$tags = explode( ',', $tags );

				if ( $tags_all )
					$args['tag_slug__and'] = $tags;
				else
					$args['tag_slug__in'] = $tags;
			}
		} else {
			if ( $category )
				$args[ self::$cpt_category ] = $category;

			if ( $tags ) {
				if ( $tags_all ) {
					$args[ 'tax_query' ] = array(
						'relation' => 'AND',
					);

					$tags = explode( ',', $tags );
					foreach ( $tags as $term ) {
						$args[ 'tax_query' ][] = array(
							'taxonomy' => self::$cpt_tags,
							'terms' => array( $term ),
							'field' => 'slug',
						);
					}
				} else {
					$args[ self::$cpt_tags ] = $tags;
				}
			}
		}

		$args = apply_filters( 'testimonials_widget_query_args', $args, $atts );

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

		$testimonials = apply_filters( 'testimonials_widget_cache_get', false, $args );
		if ( false === $testimonials ) {
			$testimonials = new WP_Query( $args );
			$testimonials = apply_filters( 'testimonials_widget_cache_set', $testimonials, $args );
		}

		if ( has_filter( 'posts_results', array( __CLASS__, 'posts_results_sort_none' ) ) )
			remove_filter( 'posts_results', array( __CLASS__, 'posts_results_sort_none' ) );

		self::$max_num_pages = $testimonials->max_num_pages;
		self::$found_posts   = $testimonials->found_posts;
		self::$post_count    = $testimonials->post_count;
		self::$wp_query      = $testimonials;

		wp_reset_postdata();

		$image_size = apply_filters( 'testimonials_widget_image_size', 'thumbnail' );
		if ( ! is_array( $image_size ) ) {
			global $_wp_additional_image_sizes;
			if ( ! empty( $_wp_additional_image_sizes[ $image_size ] ) )
				$gravatar_size = $_wp_additional_image_sizes[ $image_size ]['width'];
			else
				$gravatar_size = get_option( $image_size . '_size_w' );

			$gravatar_size = apply_filters( 'testimonials_widget_gravatar_size', $gravatar_size );
		} else
			$gravatar_size = apply_filters( 'testimonials_widget_gravatar_size', $image_size );

		$testimonial_data = array();

		if ( empty( self::$post_count ) )
			return $testimonial_data;

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
			if ( ! empty( $url ) && 0 === preg_match( '#https?://#', $url ) )
				$url = 'http://' . $url;

			$data = array(
				'post_id' => $post_id,
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

		$testimonial_data = apply_filters( 'testimonials_widget_data', $testimonial_data, $atts );

		return $testimonial_data;
	}


	public static function posts_results_sort_none( $posts, $query ) {
		$order = $query->query_vars['post__in'];
		if ( empty( $order ) )
			return $posts;

		$posts_none_sorted = array();
		// put posts in same orders as post__in
		foreach ( $order as $id ) {
			foreach ( $posts as $key => $post ) {
				if ( $id == $post->ID ) {
					$posts_none_sorted[] = $post;
					unset( $posts[$key] );
				}
			}
		}

		return $posts_none_sorted;
	}


	public static function widgets_init() {
		register_widget( 'Testimonials_Widget_Widget' );
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	public static function add_meta_box_testimonials_widget() {
		$fields = array(
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
				'name' => esc_html__( 'Email', 'testimonials-widget' ),
				'id' => 'testimonials-widget-email',
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
				'name' => esc_html__( 'URL', 'testimonials-widget' ),
				'id' => 'testimonials-widget-url',
				'type' => 'text',
				'desc' => '',
			),
		);

		$fields = apply_filters( 'testimonials_widget_meta_box', $fields );

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
	 * Revise default new testimonial text
	 *
	 * Original author: Travis Ballard http://www.travisballard.com
	 *
	 * @param string  $translation
	 * @return string $translation
	 */
	public static function gettext_testimonials( $translation ) {
		remove_action( 'gettext', array( __CLASS__, 'gettext_testimonials' ) );

		global $post;

		if ( is_object( $post ) && self::PT == $post->post_type ) {
			switch ( $translation ) {
				case esc_html__( 'Enter title here', 'testimonials-widget' ):
					return esc_html__( 'Enter testimonial source here', 'testimonials-widget' );
					break;
			}
		}

		add_action( 'gettext', array( __CLASS__, 'gettext_testimonials' ) );

		return $translation;
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

		if ( current_user_can( 'edit_others_posts' ) )
			$result = sprintf( $content, self::PT, $count_f, $name, $a_open, $a_close );
		else
			$result = sprintf( $content, self::PT, $count_f, $name, '', '' );

		echo $result;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	public static function get_schema( $testimonial, $atts ) {
		foreach ( $testimonial as $key => $value ) {
			if ( 'testimonial_image' != $key )
				$testimonial[ $key ] = self::clean_string( $value );
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

		$schema  = '<div style="display: none;">';
		$schema .= "\n";

		$agg_meta      = array();
		$author_meta   = array();
		$item_meta     = array();
		$location_meta = array();
		$org_meta      = array();
		$review_meta   = array();

		if ( $do_source )
			$author_meta[ self::$thing_name ] = $testimonial_source;

		if ( $do_title )
			$author_meta[ self::$person_job_title ] = $testimonial_title;

		if ( $do_email )
			$author_meta[ self::$person_email ] = $testimonial_email;

		if ( ! $do_company ) {
			if ( $do_url )
				$author_meta[ self::$thing_url ] = $testimonial_url;
		} else {
			if ( $do_url )
				$org_meta[ self::$thing_url ] = $testimonial_url;

			$org_meta[ self::$thing_name ] = $testimonial_company;
		}

		if ( $do_location ) {
			$location_meta[ self::$thing_name ] = $testimonial_location;

			if ( ! $do_company )
				$author_meta[ self::$person_home ] = array( self::$place_schema, $location_meta );
			else
				$org_meta[ self::$org_location ] = array( self::$place_schema, $location_meta );
		}

		if ( ! empty( $author_meta ) && ! empty( $org_meta ) )
			$author_meta[ self::$person_member ] = array( self::$org_schema, $org_meta );
		elseif ( ! empty( $org_meta ) )
			$author_meta[ self::$cw_source_org ] = array( self::$org_schema, $org_meta );

		$author_meta = apply_filters( 'testimonials_widget_schema_author', $author_meta, $testimonial, $atts );
		$author      = self::create_schema_div_prop( self::$cw_author, self::$person_schema, $author_meta );
		$schema     .= $author;
		$schema     .= "\n";

		$post         = get_post( $testimonial['post_id'] );
		$the_date     = mysql2date( 'Y-m-d', $post->post_date );
		$the_date_mod = mysql2date( 'Y-m-d', $post->post_modified );

		$review_name_length = apply_filters( 'testimonials_widget_review_name_length', 156 );

		if ( $do_content )
			$review_meta[ self::$review_body ] = $testimonial['testimonial_content'];

		$review_meta[ self::$cw_date ]     = $the_date;
		$review_meta[ self::$cw_date_mod ] = $the_date_mod;
		$review_meta[ self::$thing_name ]  = self::testimonials_truncate( $testimonial_content, $review_name_length );
		$review_meta[ self::$thing_url ]   = post_permalink( $post->ID );

		if ( $do_image ) {
			$src = self::get_image_src( $testimonial_image );

			$review_meta[ self::$thing_image ] = $src;
		}

		$review_meta = apply_filters( 'testimonials_widget_schema_review', $review_meta, $testimonial, $atts );
		$review      = self::create_schema_meta( $review_meta );
		$schema     .= $review;
		$schema     .= "\n";

		$agg_meta[ self::$agg_count ] = self::$found_posts;

		$agg_meta  = apply_filters( 'testimonials_widget_schema_aggregate', $agg_meta, $testimonial, $atts );
		$aggregate = self::create_schema_div_prop( self::$cw_aggregate, self::$agg_schema, $agg_meta );
		$schema   .= $aggregate;
		$schema   .= "\n";

		$item_meta[ self::$thing_name ] = $item_reviewed;
		$item_meta[ self::$thing_url ]  = $item_reviewed_url;

		$item_meta = apply_filters( 'testimonials_widget_schema_item', $item_meta, $testimonial, $atts );
		$item      = self::create_schema_div_prop( self::$review_item, self::$thing_schema, $item_meta );
		$schema   .= $item;
		$schema   .= "\n";

		$schema .= '</div>';
		$schema .= "\n";

		$schema = apply_filters( 'testimonials_widget_schema', $schema, $testimonial, $atts );

		return $schema;
	}


	public static function create_schema_meta( $meta_data ) {
		$meta = '';

		if ( empty( $meta_data ) )
			return $meta;

		foreach ( $meta_data as $key => $value ) {
			if ( is_array( $value ) )
				$meta .= self::create_schema_div_prop( $key, $value[ 0 ], $value[ 1 ] );
			else
				$meta .= sprintf( self::$schema_meta, $key, $value );

			$meta .= "\n";
		}

		return $meta;
	}


	public static function create_schema_span( $property_name, $span_data ) {
		$span = '';

		if ( empty( $span_data ) )
			return $span;

		$span = sprintf( self::$schema_span, $property_name, $span_data );

		return $span;
	}


	public static function create_schema_div_prop( $property_name, $schema_name, $meta_data ) {
		$meta   = '';
		$schema = '';

		if ( empty( $meta_data ) )
			return $schema;

		if ( is_array( $meta_data ) ) {
			foreach ( $meta_data as $key => $value ) {
				if ( is_array( $value ) )
					$meta .= self::create_schema_div_prop( $key, $value[ 0 ], $value[ 1 ] );
				else
					$meta .= sprintf( self::$schema_meta, $key, $value );
				
				$meta .= "\n";
			}

			$schema = sprintf( self::$schema_div_prop, $property_name, $schema_name, $meta );
		} else
			$schema = sprintf( self::$schema_div_prop, $property_name, $schema_name, $meta_data );

		return $schema;
	}


	public static function generate_css( $atts, $widget_number = null ) {
		$atts['subtype'] = 'css';

		$css = apply_filters( 'testimonials_widget_cache_get', false, $atts );
		if ( false === $css ) {
			$css = self::get_testimonials_html_css( $atts, $widget_number );
			$css = apply_filters( 'testimonials_widget_cache_set', $css, $atts );
		}

		if ( ! empty( $css ) ) {
			self::$css = array_merge( $css, self::$css );
			add_action( 'wp_footer', array( __CLASS__, 'get_testimonials_css' ), 20 );
		}
	}


	public static function generate_js( $testimonials, $atts, $widget_number = null ) {
		$atts['subtype'] = 'js';

		$js = apply_filters( 'testimonials_widget_cache_get', false, $atts );
		if ( false === $js ) {
			$js = self::get_testimonials_html_js( $testimonials, $atts, $widget_number );
			$js = apply_filters( 'testimonials_widget_cache_set', $js, $atts );
		}

		if ( ! empty( $js ) ) {
			self::$scripts = array_merge( $js, self::$scripts );
			add_action( 'wp_footer', array( __CLASS__, 'get_testimonials_scripts' ), 20 );
		}
	}


	public static function call_scripts_styles( $testimonials, $atts, $widget_number = null ) {
		if ( is_null( $widget_number ) )
			$widget_number = self::get_instance();

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


	public static function show_shortcodes() {
		echo '<div class="wrap">';
		echo '<div class="icon32" id="icon-options-general"></div>';
		echo '<h2>' . esc_html__( 'Testimonials Shortcode Examples', 'testimonials-widget' ) . '</h2>';

		$shortcodes = <<<EOD
<h3>[testimonials]</h3>

<ul>
<li><code>[testimonials category="category-name"]</code> - Testimonial list by category</li>
<li><code>[testimonials category=product hide_not_found=true]</code> - Testimonial list by category and hide "No testimonials found" message</li>
<li><code>[testimonials category=product tags=widget limit=5]</code> - Testimonial list by tag, showing 5 at most</li>
<li><code>[testimonials char_limit=0 limit=-1]</code> - Show all testimonials on one page</li>
<li><code>[testimonials char_limit=0 target=_new limit=3 disable_quotes=true]</code> - Show 3 full-length testimonials, with opening and closing quote marks removed</li>
<li><code>[testimonials hide_source=true hide_url=true]</code> - Show testimonial list with source and urls hidden</li>
<li><code>[testimonials ids="1,11,111" paging=false]</code> - Show only these 3 testimonials</li>
<li><code>[testimonials meta_key=testimonials-widget-company order=asc limit=15]</code> - Show 15 testimonials, in company order</li>
<li><code>[testimonials order=ASC orderby=title]</code> - List testimonials by post title</li>
<li><code>[testimonials tags="test,fun" random=true exclude="2,22,333"]</code> - Select testimonials tagged with either "test" or "fun", in random order, but ignore those of the excluded ids</li>
</ul>

<h3>[testimonials_slider]</h3>

<ul>
<li><code>[testimonials_slider category=product order=asc]</code> - Show rotating testimonials, of the product category, lowest post ids first</li>
<li><code>[testimonials_slider tags=sometag random=true]</code> - Show rotating, random testimonials having tag "sometag"</li>
</ul>
EOD;

		$shortcodes = apply_filters( 'testimonials_widget_shortcodes', $shortcodes );

		echo $shortcodes;
		echo '</div>';
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
			if ( $category )
				$term = get_term( $term_id, 'category' );
			else
				$term = get_term( $term_id, 'post_tag' );
		} else {
			if ( $category )
				$term = get_term( $term_id, self::$cpt_category );
			else
				$term = get_term( $term_id, self::$cpt_tags );
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
		if ( ! current_user_can( 'edit_others_posts' ) )
			return $array;

		$count = apply_filters( 'testimonials_widget_cache_get', false, 'dashboard_count' );
		if ( false === $count ) {
			$posts = wp_count_posts( self::PT );
			$count = $posts->publish;
			$count = apply_filters( 'testimonials_widget_cache_set', $count, 'dashboard_count' );
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
}


?>
