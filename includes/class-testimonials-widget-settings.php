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

/**
 * Testimonials settings class
 *
 * Based upon http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-1/
 */

require_once AIHR_DIR_INC . 'class-aihrus-settings.php';

if ( class_exists( 'Testimonials_Widget_Settings' ) ) {
	return;
}


class Testimonials_Widget_Settings extends Aihrus_Settings {
	const ID   = 'testimonialswidget_settings';
	const NAME = 'Testimonials Settings';

	public static $admin_page;
	public static $class    = __CLASS__;
	public static $defaults = array();
	public static $plugin_assets;
	public static $plugin_url = 'http://wordpress.org/plugins/testimonials-widget/';
	public static $sections   = array();
	public static $settings   = array();
	public static $version;

	public static $default = array(
		'backwards' => array(
			'version' => null, // below this version number, use std
			'std' => null,
		),
		'choices' => array(), // key => value
		'class' => null, // warning, etc.
		'desc' => null,
		'id' => null,
		'maxlength' => null,
		'placeholder' => null,
		'section' => 'general',
		'show_code' => true,
		'std' => null, // default key or value
		'suggest' => false, // attempt for auto-suggest on inputs
		'title' => null,
		'type' => 'text', // textarea, checkbox, radio, select, hidden, heading, password, expand_begin, expand_end
		'validate' => null, // required, term, slug, slugs, ids, order, single paramater PHP functions
		'widget' => 1, // show in widget options, 0 off
	);

	public function __construct() {
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		add_action( 'init', array( __CLASS__, 'init' ) );
	}


	public static function admin_init() {
		add_filter( 'wp_unique_post_slug_is_bad_hierarchical_slug', array( __CLASS__, 'is_bad_hierarchical_slug' ), 10, 4 );
		add_filter( 'wp_unique_post_slug_is_bad_flat_slug', array( __CLASS__, 'is_bad_flat_slug' ), 10, 3 );

		$version       = tw_get_option( 'version' );
		self::$version = Testimonials_Widget::VERSION;
		self::$version = apply_filters( 'tw_version', self::$version );

		if ( $version != self::$version ) {
			self::initialize_settings();
		}

		if ( ! Testimonials_Widget::do_load() ) {
			return;
		}

		self::load_options();
		self::register_settings();
	}


	public static function admin_menu() {
		self::$admin_page = add_submenu_page( 'edit.php?post_type=' . Testimonials_Widget::PT, esc_html__( 'Testimonials Settings', 'testimonials-widget' ), esc_html__( 'Settings', 'testimonials-widget' ), 'manage_options', self::ID, array( __CLASS__, 'display_page' ) );

		add_action( 'admin_print_scripts-' . self::$admin_page, array( __CLASS__, 'scripts' ) );
		add_action( 'admin_print_styles-' . self::$admin_page, array( __CLASS__, 'styles' ) );
		add_action( 'load-' . self::$admin_page, array( __CLASS__, 'settings_add_help_tabs' ) );
	}


	public static function init() {
		load_plugin_textdomain( 'testimonials-widget', false, '/testimonials-widget/languages/' );

		self::$plugin_assets = Testimonials_Widget::$plugin_assets;
	}


	public static function sections() {
		self::$sections['general']   = esc_html__( 'General', 'testimonials-widget' );
		self::$sections['fields']    = esc_html__( 'Fields', 'testimonials-widget' );
		self::$sections['selection'] = esc_html__( 'Selection', 'testimonials-widget' );
		self::$sections['ordering']  = esc_html__( 'Ordering', 'testimonials-widget' );
		self::$sections['columns']   = esc_html__( 'Columns', 'testimonials-widget' );
		self::$sections['post_type'] = esc_html__( 'Post Type', 'testimonials-widget' );
		self::$sections['widget']    = esc_html__( 'Slider Widget', 'testimonials-widget' );

		parent::sections();

		self::$sections['examples'] = esc_html__( 'Shortcode Examples', 'testimonials-widget' );
		self::$sections['options']  = esc_html__( 'Shortcode Attributes', 'testimonials-widget' );

		self::$sections = apply_filters( 'tw_sections', self::$sections );
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function settings() {
		// Widget
		self::$settings['title'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Title', 'testimonials-widget' ),
			'std' => esc_html__( 'Testimonials', 'testimonials-widget' ),
			'validate' => 'wp_kses_post',
		);

		self::$settings['title_link'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Title Link', 'testimonials-widget' ),
			'desc' => esc_html__( 'URL, path, or post ID to link widget title to. Ex: http://example.com/stuff, /testimonials, or 123', 'testimonials-widget' ),
			'validate' => 'wp_kses_data',
		);

		self::$settings['adaptive_height'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Adaptive Slider Height?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Dynamically adjust slider height based on each slide\'s height.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
		);

		self::$settings['bottom_text'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Bottom Text', 'testimonials-widget' ),
			'desc' => esc_html__( 'Common text or HTML for bottom of testimonials.', 'testimonials-widget' ),
			'type' => 'textarea',
			'validate' => 'wp_kses_post',
		);

		self::$settings['char_limit'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Character Limit', 'testimonials-widget' ),
			'desc' => esc_html__( 'Number of characters to limit non-single testimonial views to.', 'testimonials-widget' ),
			'validate' => 'absint',
		);

		self::$settings['keep_whitespace'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Keep Whitespace?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Keeps testimonials looking as entered than sans auto-formatting.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['refresh_interval'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Rotation Speed', 'testimonials-widget' ),
			'desc' => esc_html__( 'Number of seconds between testimonial rotations or 0 for no rotation at all refresh.', 'testimonials-widget' ),
			'std' => 5,
			'validate' => 'absint',
		);

		self::$settings['show_start_stop'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Show Play/Pause?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Display start and stop buttons underneath the testimonial slider.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
		);

		self::$settings['transition_mode'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Transition Mode?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Type of transition between slides.', 'testimonials-widget' ),
			'type' => 'select',
			'choices' => array(
				'fade' => esc_html__( 'Fade', 'testimonials-widget' ),
				'horizontal' => esc_html__( 'Horizontal', 'testimonials-widget' ),
				'vertical' => esc_html__( 'Vertical', 'testimonials-widget' ),
			),
			'std' => 'fade',
		);

		self::$settings['widget_expand_all'] = array(
			'section' => 'widget',
			'type' => 'expand_all',
		);

		// General
		self::$settings['general_expand_begin'] = array(
			'desc' => esc_html__( 'General Options', 'testimonials-widget' ),
			'type' => 'expand_begin',
		);

		self::$settings['item_reviewed'] = array(
			'title' => esc_html__( 'Default Reviewed Item?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Name of thing being referenced in testimonials.', 'testimonials-widget' ),
			'std' => get_option( 'blogname' ),
			'widget' => 0,
			'validate' => 'wp_kses_post',
		);

		self::$settings['item_reviewed_url'] = array(
			'title' => esc_html__( 'Default Reviewed Item URL?', 'testimonials-widget' ),
			'desc' => esc_html__( 'URL of thing being referenced in testimonials.', 'testimonials-widget' ),
			'std' => network_site_url(),
			'validate' => 'url',
			'widget' => 0,
		);

		self::$settings['disable_quotes'] = array(
			'title' => esc_html__( 'Disable built-in quotes?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Remove open and close quote span tags surrounding testimonial content.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['hide_not_found'] = array(
			'title' => esc_html__( 'Disable "Testimonials Not Found"?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Remove "Testimonials Not Found" content when no testimonials are found to be displayed.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['paging'] = array(
			'title' => esc_html__( 'Enable Paging?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Show paging controls for `[testimonials]` listing.', 'testimonials-widget' ),
			'type' => 'select',
			'choices' => array(
				'' => esc_html__( 'Disable', 'testimonials-widget' ),
				1 => esc_html__( 'Enable', 'testimonials-widget' ),
				'before' => esc_html__( 'Before testimonials', 'testimonials-widget' ),
				'after' => esc_html__( 'After testimonials', 'testimonials-widget' ),
			),
			'std' => 1,
			'widget' => 0,
		);

		$desc = __( 'Adds HTML tag markup per the <a href="%s">Review schema</a> to testimonials. Search engines including Bing, Google, Yahoo! and Yandex rely on this markup to improve the display of search results.', 'testimonials-widget' );

		self::$settings['enable_schema'] = array(
			'title' => esc_html__( 'Enable Review Schema?', 'testimonials-widget' ),
			'desc' => sprintf( $desc, 'http://schema.org/Review' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
		);

		self::$settings['do_shortcode'] = array(
			'title' => esc_html__( 'Enable [shortcodes]?', 'testimonials-widget' ),
			'desc' => esc_html__( 'If unchecked, shortcodes are stripped.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
		);

		self::$settings['enable_video'] = array(
			'title' => esc_html__( 'Enable Video?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Only enable when displaying video content.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['exclude_bxslider_css'] = array(
			'title' => esc_html__( 'Exclude bxSlider CSS?', 'testimonials-widget' ),
			'desc' => esc_html__( 'For a bare-bones, unthemed slider.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'widget' => 0,
			'show_code' => false,
		);

		self::$settings['exclude_css'] = array(
			'title' => esc_html__( 'Exclude default CSS?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Prevent default CSS from being loaded.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'widget' => 0,
			'show_code' => false,
		);

		self::$settings['remove_hentry'] = array(
			'title' => esc_html__( 'Remove `.hentry` CSS?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Some themes use class `.hentry` in a manner that breaks Testimonials\' CSS.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
		);

		self::$settings['target'] = array(
			'title' => esc_html__( 'URL Target', 'testimonials-widget' ),
			'desc' => esc_html__( 'Add target to all URLs; leave blank if none.', 'testimonials-widget' ),
			'validate' => 'term',
		);

		self::$settings['use_quote_tag'] = array(
			'title' => esc_html__( 'Use `&lt;q&gt;` tag?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Not HTML5 compliant.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['general_expand_end'] = array(
			'type' => 'expand_end',
		);

		// Fields
		self::$settings['fields_expand_begin'] = array(
			'section' => 'fields',
			'desc' => esc_html__( 'Field Options', 'testimonials-widget' ),
			'type' => 'expand_begin',
		);

		self::$settings['hide_source'] = array(
			'section' => 'fields',
			'title' => esc_html__( 'Hide Author?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'Don\'t display testimonial title in cite.', 'testimonials-widget' ),
		);

		self::$settings['hide_company'] = array(
			'section' => 'fields',
			'title' => esc_html__( 'Hide Company?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'Don\'t display testimonial company in cite.', 'testimonials-widget' ),
		);

		self::$settings['hide_content'] = array(
			'section' => 'fields',
			'title' => esc_html__( 'Hide Content?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'Don\'t display testimonial content in a view.', 'testimonials-widget' ),
		);

		self::$settings['hide_email'] = array(
			'section' => 'fields',
			'title' => esc_html__( 'Hide Email?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'desc' => esc_html__( 'Don\'t display or link to testimonial email in cite.', 'testimonials-widget' ),
		);

		self::$settings['hide_gravatar'] = array(
			'section' => 'fields',
			'title' => esc_html__( 'Hide Gravatar?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'Don\'t display Gravatar image with testimonial.', 'testimonials-widget' ),
		);

		self::$settings['hide_image'] = array(
			'section' => 'fields',
			'title' => esc_html__( 'Hide Image?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'Don\'t display featured image with testimonial.', 'testimonials-widget' ),
		);

		self::$settings['hide_image_single'] = array(
			'section' => 'fields',
			'title' => esc_html__( 'Hide Image in Single View?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'widget' => 0,
		);

		self::$settings['hide_title'] = array(
			'section' => 'fields',
			'title' => esc_html__( 'Hide Job Title?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'Don\'t display testimonial job title in cite.', 'testimonials-widget' ),
		);

		self::$settings['hide_location'] = array(
			'section' => 'fields',
			'title' => esc_html__( 'Hide Location?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'Don\'t display testimonial location in cite.', 'testimonials-widget' ),
		);

		self::$settings['hide_url'] = array(
			'section' => 'fields',
			'title' => esc_html__( 'Hide URL?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'Don\'t display or link to testimonial URL in cite.', 'testimonials-widget' ),
		);

		self::$settings['fields_expand_end'] = array(
			'type' => 'expand_end',
		);

		// Selection
		self::$settings['selection_expand_begin'] = array(
			'section' => 'selection',
			'desc' => esc_html__( 'Selection Options', 'testimonials-widget' ),
			'type' => 'expand_begin',
		);

		self::$settings['category'] = array(
			'section' => 'selection',
			'title' => esc_html__( 'Category Filter', 'testimonials-widget' ),
			'desc' => esc_html__( 'Comma separated category names or IDs.', 'testimonials-widget' ),
			'validate' => 'terms',
			'suggest' => true,
		);

		self::$settings['exclude'] = array(
			'section' => 'selection',
			'title' => esc_html__( 'Exclude IDs Filter', 'testimonials-widget' ),
			'desc' => esc_html__( 'Comma separated testimonial IDs.', 'testimonials-widget' ),
			'validate' => 'ids',
		);

		self::$settings['ids'] = array(
			'section' => 'selection',
			'title' => esc_html__( 'Include IDs Filter', 'testimonials-widget' ),
			'desc' => esc_html__( 'Comma separated testimonial IDs.', 'testimonials-widget' ),
			'validate' => 'ids',
		);

		self::$settings['limit'] = array(
			'section' => 'selection',
			'title' => esc_html__( 'Limit', 'testimonials-widget' ),
			'desc' => esc_html__( 'Number of testimonials to select per instance.', 'testimonials-widget' ),
			'std' => 10,
			'validate' => 'nozero',
		);

		self::$settings['tags_all'] = array(
			'section' => 'selection',
			'title' => esc_html__( 'Require All Tags?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Select only testimonials with all of the given tags.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['tags'] = array(
			'section' => 'selection',
			'title' => esc_html__( 'Tags Filter', 'testimonials-widget' ),
			'desc' => esc_html__( 'Comma separated tag names or IDs.', 'testimonials-widget' ),
			'validate' => 'terms',
			'suggest' => true,
		);

		self::$settings['selection_expand_end'] = array(
			'section' => 'selection',
			'type' => 'expand_end',
		);

		// Ordering
		self::$settings['ordering_expand_begin'] = array(
			'section' => 'ordering',
			'desc' => esc_html__( 'Ordering Options', 'testimonials-widget' ),
			'type' => 'expand_begin',
		);

		self::$settings['orderby'] = array(
			'section' => 'ordering',
			'title' => esc_html__( 'ORDER BY', 'testimonials-widget' ),
			'desc' => esc_html__( 'Used when "Random Order" is disabled.', 'testimonials-widget' ),
			'type' => 'select',
			'choices' => array(
				'ID' => esc_html__( 'Testimonial ID', 'testimonials-widget' ),
				'author' => esc_html__( 'Author', 'testimonials-widget' ),
				'date' => esc_html__( 'Date', 'testimonials-widget' ),
				'menu_order' => esc_html__( 'Menu Order', 'testimonials-widget' ),
				'title' => esc_html__( 'Author', 'testimonials-widget' ),
				'none' => esc_html__( 'No order', 'testimonials-widget' ),
			),
			'std' => 'ID',
			'validate' => 'term',
		);

		self::$settings['meta_key'] = array(
			'section' => 'ordering',
			'title' => esc_html__( 'ORDER BY meta_key', 'testimonials-widget' ),
			'desc' => esc_html__( 'Used when "Random Order" is disabled and sorting by a testimonials meta key is needed. Overrides ORDER BY.', 'testimonials-widget' ),
			'type' => 'select',
			'choices' => array(
				'' => esc_html__( 'None', 'testimonials-widget' ),
				'testimonials-widget-title' => esc_html__( 'Job Title', 'testimonials-widget' ),
				'testimonials-widget-email' => esc_html__( 'Email', 'testimonials-widget' ),
				'testimonials-widget-location' => esc_html__( 'Location', 'testimonials-widget' ),
				'testimonials-widget-company' => esc_html__( 'Company', 'testimonials-widget' ),
				'testimonials-widget-url' => esc_html__( 'URL', 'testimonials-widget' ),
			),
			'validate' => 'slug',
		);

		self::$settings['order'] = array(
			'section' => 'ordering',
			'title' => esc_html__( 'ORDER BY Order', 'testimonials-widget' ),
			'type' => 'select',
			'choices' => array(
				'DESC' => esc_html__( 'Descending', 'testimonials-widget' ),
				'ASC' => esc_html__( 'Ascending', 'testimonials-widget' ),
			),
			'std' => 'DESC',
			'validate' => 'order',
		);

		self::$settings['random'] = array(
			'section' => 'ordering',
			'title' => esc_html__( 'Random Order?', 'testimonials-widget' ),
			'desc' => esc_html__( 'If checked, ignores ORDER BY, ORDER BY meta_key, and ORDER BY Order. Widgets are random by default automatically.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['ordering_expand_end'] = array(
			'section' => 'ordering',
			'type' => 'expand_end',
		);

		// Post Type
		self::$settings['allow_comments'] = array(
			'section' => 'post_type',
			'title' => esc_html__( 'Allow Comments?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Only affects the Testimonials post edit page. Your theme controls the front-end view.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'widget' => 0,
			'show_code' => false,
		);

		$desc        = __( 'URL slug-name for <a href="%1s">testimonials archive</a> page.', 'testimonials-widget' );
		$has_archive = tw_get_option( 'has_archive', '' );
		$site_url    = network_site_url( '/' . $has_archive . '/' );

		self::$settings['has_archive'] = array(
			'section' => 'post_type',
			'title' => esc_html__( 'Archive Page URL', 'testimonials-widget' ),
			'desc' => sprintf( $desc, $site_url ),
			'std' => 'testimonials-archive',
			'validate' => 'slash_sanitize_title',
			'widget' => 0,
			'show_code' => false,
		);

		self::$settings['use_cpt_taxonomy'] = array(
			'section' => 'post_type',
			'title' => esc_html__( 'Disable Default Taxonomies?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'If checked, use Testimonials\' own category and tag taxonomies than WordPress\' defaults.', 'testimonials-widget' ),
			'widget' => 0,
			'show_code' => false,
		);

		self::$settings['enable_archives'] = array(
			'desc' => esc_html__( 'Include testimonials in archive and category views.', 'testimonials-widget' ),
			'section' => 'post_type',
			'show_code' => false,
			'title' => esc_html__( 'Enable archives view?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'widget' => 0,
		);

		$desc = esc_html__( 'URL slug-name for testimonial view pages. Shouldn\'t be the same as the Archive Page URL nor should it match a page URL slug.', 'testimonials-widget' );

		self::$settings['rewrite_slug'] = array(
			'section' => 'post_type',
			'title' => esc_html__( 'Testimonial Page URL', 'testimonials-widget' ),
			'desc' => $desc,
			'std' => 'testimonial',
			'validate' => 'slash_sanitize_title',
			'widget' => 0,
			'show_code' => false,
		);

		// Columns
		self::$settings['columns_author'] = array(
			'section' => 'columns',
			'title' => esc_html__( 'Hide Author?', 'testimonials-widget', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'widget' => 0,
			'show_code' => false,
		);

		self::$settings['columns_company'] = array(
			'section' => 'columns',
			'title' => esc_html__( 'Hide Company?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'widget' => 0,
			'show_code' => false,
		);

		self::$settings['columns_email'] = array(
			'section' => 'columns',
			'title' => esc_html__( 'Hide Email?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'widget' => 0,
			'show_code' => false,
		);

		self::$settings['columns_id'] = array(
			'section' => 'columns',
			'title' => esc_html__( 'Hide ID?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'widget' => 0,
			'show_code' => false,
		);

		self::$settings['columns_thumbnail'] = array(
			'section' => 'columns',
			'title' => esc_html__( 'Hide Image?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'widget' => 0,
			'show_code' => false,
		);

		self::$settings['columns_job_title'] = array(
			'section' => 'columns',
			'title' => esc_html__( 'Hide Job Title?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'widget' => 0,
			'show_code' => false,
		);

		self::$settings['columns_location'] = array(
			'section' => 'columns',
			'title' => esc_html__( 'Hide Location?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'widget' => 0,
			'show_code' => false,
		);

		self::$settings['columns_shortcode'] = array(
			'section' => 'columns',
			'title' => esc_html__( 'Hide Shortcode?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'widget' => 0,
			'show_code' => false,
		);

		self::$settings['columns_url'] = array(
			'section' => 'columns',
			'title' => esc_html__( 'Hide URL?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
			'widget' => 0,
			'show_code' => false,
		);

		// Reset
		self::$settings['reset_expand_begin'] = array(
			'section' => 'reset',
			'desc' => esc_html__( 'Reset', 'testimonials-widget' ),
			'type' => 'expand_begin',
		);

		parent::settings();

		self::$settings['reset_expand_end'] = array(
			'section' => 'reset',
			'type' => 'expand_end',
		);

		self::$settings = apply_filters( 'tw_settings', self::$settings );
		foreach ( self::$settings as $id => $parts ) {
			self::$settings[ $id ] = wp_parse_args( $parts, self::$default );
		}

		if ( ! empty( $_REQUEST['page'] ) && 'testimonialswidget_settings' == $_REQUEST['page'] ) {
			// Examples
			self::$settings['examples'] = array(
				'section' => 'examples',
				'desc' => Testimonials_Widget::testimonials_examples(),
				'type' => 'content',
				'widget' => 0,
			);
			self::$settings['examples'] = wp_parse_args( self::$settings['examples'], self::$default );

			// Shortcode Attributes
			self::$settings['options'] = array(
				'section' => 'options',
				'type' => 'content',
				'desc' => Testimonials_Widget::testimonials_options(),
				'widget' => 0,
			);
			self::$settings['options'] = wp_parse_args( self::$settings['options'], self::$default );
		}
	}


	public static function get_defaults( $mode = null, $old_version = null ) {
		$old_version = tw_get_option( 'version' );

		$defaults = parent::get_defaults( $mode, $old_version );
		$defaults = apply_filters( 'tw_settings_defaults', $defaults );

		return $defaults;
	}


	public static function display_page( $disable_donate = false ) {
		$disable_donate = tw_get_option( 'disable_donate' );

		parent::display_page( $disable_donate );
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	public static function display_setting( $args = array(), $do_echo = true, $input = null ) {
		$content = '';
		switch ( $args['type'] ) {
			default:
				$content = apply_filters( 'tw_display_setting', $content, $args, $input );
				break;
		}

		if ( empty( $content ) ) {
			$content = parent::display_setting( $args, false, $input );
		}

		if ( ! $do_echo ) {
			return $content;
		}

		echo $content;
	}


	public static function initialize_settings( $version = null ) {
		$version = tw_get_option( 'version', self::$version );

		parent::initialize_settings( $version );
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function validate_settings( $input, $options = null, $do_errors = false ) {
		$validated = parent::validate_settings( $input, $options, $do_errors );

		if ( empty( $do_errors ) ) {
			$input  = $validated;
			$errors = array();
		} else {
			$input  = $validated['input'];
			$errors = $validated['errors'];
		}

		$defaults = self::get_defaults();

		if ( ! empty( $input['has_archive'] ) ) {
			$input['has_archive'] = self::prevent_slug_conflict( $input['has_archive'] );
		} else {
			$input['has_archive'] = $defaults['has_archive'];
		}

		if ( ! empty( $input['rewrite_slug'] ) ) {
			$input['rewrite_slug'] = self::prevent_slug_conflict( $input['rewrite_slug'] );
		} else {
			$input['rewrite_slug'] = $defaults['rewrite_slug'];
		}

		$flush_rewrite_rules = false;
		// same has_archive and rewrite_slug causes problems
		if ( $input['has_archive'] == $input['rewrite_slug'] ) {
			$input['has_archive']  = $defaults['has_archive'];
			$input['rewrite_slug'] = $defaults['rewrite_slug'];

			$flush_rewrite_rules = true;
		}

		// did URL slugs or taxonomy change?
		$has_archive      = tw_get_option( 'has_archive' );
		$rewrite_slug     = tw_get_option( 'rewrite_slug' );
		$use_cpt_taxonomy = tw_get_option( 'use_cpt_taxonomy' );
		if ( $has_archive != $input['has_archive'] || $rewrite_slug != $input['rewrite_slug'] || $use_cpt_taxonomy != $input['use_cpt_taxonomy'] ) {
			$flush_rewrite_rules = true;
		}

		if ( $flush_rewrite_rules ) {
			flush_rewrite_rules();
		}

		$input['version']        = self::$version;
		$input['donate_version'] = Testimonials_Widget::VERSION;

		$input = apply_filters( 'tw_validate_settings', $input, $errors );
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


	public static function prevent_slug_conflict( $slug ) {
		global $wpdb;

		// slugs must be unique within their own trees
		$check_sql  = "SELECT post_name FROM $wpdb->posts WHERE post_name = %s AND post_parent = 0 LIMIT 1";
		$slug_check = $wpdb->get_var( $wpdb->prepare( $check_sql, $slug ) );
		if ( $slug_check ) {
			$suffix = 2;
			do {
				$alt_slug   = _truncate_post_slug( $slug, 200 - ( strlen( $suffix ) + 1 ) ) . "-$suffix";
				$slug_check = $wpdb->get_var( $wpdb->prepare( $check_sql, $alt_slug ) );
				$suffix++;
			} while ( $slug_check );

			$slug = $alt_slug;
		}

		return $slug;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.LongVariable)
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function is_bad_hierarchical_slug( $is_bad_hierarchical_slug, $slug, $post_type, $post_parent ) {
		// This post has no parent and is a "base" post
		if ( ! $post_parent && self::is_cpt_slug( $slug ) ) {
			return true;
		}

		return $is_bad_hierarchical_slug;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function is_bad_flat_slug( $is_bad_flat_slug, $slug, $post_type ) {
		if ( self::is_cpt_slug( $slug ) ) {
			return true;
		}

		return $is_bad_flat_slug;
	}


	public static function is_cpt_slug( $slug ) {
		$has_archive  = tw_get_option( 'has_archive' );
		$rewrite_slug = tw_get_option( 'rewrite_slug' );

		return in_array( $slug, array( $has_archive, $rewrite_slug ) );
	}


	public static function settings_add_help_tabs() {
		$screen = get_current_screen();
		if ( self::$admin_page != $screen->id ) {
			return;
		}

		$screen->set_help_sidebar(
			'<p>' .
			esc_html__( 'These Testimonials Settings establish the default option values for shortcodes, theme functions, and widget instances. Widgets, once created no longer inherit these global settings. Therefore, you\'ll need to update each widget with the new settings. It might be easier to delete the widget and then recreate it.', 'testimonials-widget' ) .
			'</p><p>' .
			esc_html__( 'Shortcode option names are listed below each entry.', 'testimonials-widget' ) .
			'</p><p>' .
			sprintf(
				__( 'View the <a href="%s">Testimonials documentation</a>.', 'testimonials-widget' ),
				esc_url( self::$plugin_url . '/faq/' )
			) .
			'</p>'
		);

		$screen->add_help_tab(
			array(
				'id'     => 'tw-general',
				'title'     => esc_html__( 'General', 'testimonials-widget' ),
				'content' => '<p>' . esc_html__( 'General options.', 'testimonials-widget' ) . '</p>'
			)
		);

		$screen->add_help_tab(
			array(
				'id'     => 'tw-fields',
				'title'     => esc_html__( 'Fields', 'testimonials-widget' ),
				'content' => '<p>' . esc_html__( 'Show or hide fields.', 'testimonials-widget' ) . '</p>'
			)
		);

		$screen->add_help_tab(
			array(
				'id'     => 'tw-selection',
				'title'     => esc_html__( 'Selection', 'testimonials-widget' ),
				'content' => '<p>' . esc_html__( 'Options used to select testimonials.', 'testimonials-widget' ) . '</p>'
			)
		);

		$screen->add_help_tab(
			array(
				'id'     => 'tw-ordering',
				'title'     => esc_html__( 'Ordering', 'testimonials-widget' ),
				'content' => '<p>' . esc_html__( 'Options used to determine displayed testimonials ordering.', 'testimonials-widget' ) . '</p>'
			)
		);

		$screen->add_help_tab(
			array(
				'id'     => 'tw-columns',
				'title'     => esc_html__( 'Columns', 'testimonials-widget' ),
				'content' => '<p>' . esc_html__( 'Allowed columns to display on edit page.', 'testimonials-widget' ) . '</p>'
			)
		);

		$screen->add_help_tab(
			array(
				'id'     => 'tw-post_type',
				'title'     => esc_html__( 'Post Type', 'testimonials-widget' ),
				'content' => '<p>' . esc_html__( 'Archive and singular page URL related testimonials options.', 'testimonials-widget' ) . '</p>'
			)
		);

		$screen->add_help_tab(
			array(
				'id'     => 'tw-widget',
				'title'     => esc_html__( 'Slider Widget', 'testimonials-widget' ),
				'content' => '<p>' . esc_html__( 'Options related to showing testimonials in widgets.', 'testimonials-widget' ) . '</p>'
			)
		);

		$screen->add_help_tab(
			array(
				'id'     => 'tw-reset',
				'title'     => esc_html__( 'Reset', 'testimonials-widget' ),
				'content' => '<p>' . esc_html__( 'Backwards compatibility, import/export options, and reset options.', 'testimonials-widget' ) . '</p>'
			)
		);

		do_action( 'tw_settings_add_help_tabs', $screen );
	}


	public static function get_suggest( $id, $suggest_id ) {
		wp_enqueue_script( 'suggest' );

		$use_cpt_taxonomy = tw_get_option( 'use_cpt_taxonomy', false );

		switch ( $id ) {
			case 'category':
				if ( ! $use_cpt_taxonomy ) {
					$taxonomy = 'category';
				} else {
					$taxonomy = Testimonials_Widget::$cpt_category;
				}

				break;

			case 'tags':
				if ( ! $use_cpt_taxonomy ) {
					$taxonomy = 'post_tag';
				} else {
					$taxonomy = Testimonials_Widget::$cpt_tags;
				}

				break;
		}

		$ajax_url   = site_url() . '/wp-admin/admin-ajax.php';
		$suggest_js = "suggest( '{$ajax_url}?action=ajax-tag-search&tax={$taxonomy}', { delay: 500, minchars: 2, multiple: true, multipleSep: ', ' } )";

		$scripts = <<<EOD
<script type="text/javascript">
jQuery(document).ready( function() {
	jQuery( '.{$suggest_id}' ).{$suggest_js};
});
</script>
EOD;

		return $scripts;
	}


}


function tw_get_options() {
	$options = get_option( Testimonials_Widget_Settings::ID );

	if ( false === $options ) {
		$options = Testimonials_Widget_Settings::get_defaults();
		update_option( Testimonials_Widget_Settings::ID, $options );
	}

	return $options;
}


function tw_get_option( $option, $default = null ) {
	$options = get_option( Testimonials_Widget_Settings::ID, null );

	if ( isset( $options[ $option ] ) ) {
		return $options[ $option ];
	} else {
		return $default;
	}
}


function tw_set_option( $option, $value = null ) {
	$options = get_option( Testimonials_Widget_Settings::ID );

	if ( ! is_array( $options ) ) {
		$options = array();
	}

	$options[ $option ] = $value;
	update_option( Testimonials_Widget_Settings::ID, $options );
}


?>
