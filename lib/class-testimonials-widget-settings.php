<?php
/*
	Copyright 2013 Michael Cannon (email: mc@aihr.us)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Testimonials settings class
 *
 * Based upon http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-1/
 */


require_once TW_PLUGIN_DIR_LIB . '/aihrus/class-aihrus-settings.php';


class Testimonials_Widget_Settings extends Aihrus_Settings {
	const ID        = 'testimonialswidget_settings';
	const ITEM_NAME = 'Testimonials Settings';

	public static $admin_page  = '';
	public static $class       = __CLASS__;
	public static $defaults    = array();
	public static $plugin_path = array();
	public static $plugin_url  = 'http://wordpress.org/plugins/testimonials-widget/';
	public static $sections    = array();
	public static $settings    = array();
	public static $suggest_id  = 0;
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
		self::$version = apply_filters( 'testimonials_widget_version', self::$version );

		if ( $version != self::$version )
			self::initialize_settings();

		if ( ! Testimonials_Widget::do_load() )
			return;

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

		self::$plugin_path = plugins_url( '', dirname( __FILE__ ) );
	}


	public static function sections() {
		self::$sections['general']   = esc_html__( 'General', 'testimonials-widget' );
		self::$sections['selection'] = esc_html__( 'Selection', 'testimonials-widget' );
		self::$sections['ordering']  = esc_html__( 'Ordering', 'testimonials-widget' );
		self::$sections['widget']    = esc_html__( 'Widget', 'testimonials-widget' );
		self::$sections['post_type'] = esc_html__( 'Post Type', 'testimonials-widget' );

		parent::sections();

		self::$sections = apply_filters( 'testimonials_widget_sections', self::$sections );
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
			'title' => esc_html__( 'Widget Title', 'testimonials-widget' ),
			'std' => esc_html__( 'Testimonials', 'testimonials-widget' ),
			'validate' => 'wp_kses_post',
		);

		self::$settings['title_link'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Title Link', 'testimonials-widget' ),
			'desc' => esc_html__( 'URL, path, or post ID to link widget title to. Ex: http://example.com/stuff, /testimonials, 123 or ', 'testimonials-widget' ),
			'validate' => 'wp_kses_data',
		);

		self::$settings['char_limit'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Character Limit', 'testimonials-widget' ),
			'desc' => esc_html__( 'Number of characters to limit non-single testimonial views to', 'testimonials-widget' ),
			'validate' => 'absint',
		);

		self::$settings['refresh_interval'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Rotation Speed', 'testimonials-widget' ),
			'desc' => esc_html__( 'Number of seconds between testimonial rotations or 0 for no rotation at all refresh', 'testimonials-widget' ),
			'std' => 5,
			'validate' => 'absint',
		);

		self::$settings['widget_expand_all'] = array(
			'section' => 'widget',
			'type' => 'expand_all',
		);

		self::$settings['widget_expand_begin'] = array(
			'section' => 'widget',
			'desc' => esc_html__( 'Additional Widget Options', 'testimonials-widget' ),
			'type' => 'expand_begin',
		);

		self::$settings['transition_mode'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Transition Mode?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Type of transition between slides', 'testimonials-widget' ),
			'type' => 'select',
			'choices' => array(
				'fade' => esc_html__( 'Fade', 'testimonials-widget' ),
				'horizontal' => esc_html__( 'Horizontal', 'testimonials-widget' ),
				'vertical' => esc_html__( 'Vertical', 'testimonials-widget' ),
			),
			'std' => 'fade',
		);

		self::$settings['show_start_stop'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Show Play/Pause?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Display start and stop buttons underneath the testimonial slider.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
		);

		self::$settings['enable_video'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Enable Video?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Only enable when displaying video content.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['keep_whitespace'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Keep Whitespace?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Keeps testimonials looking as entered than sans auto-formatting', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['bottom_text'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Testimonial Bottom Text', 'testimonials-widget' ),
			'desc' => esc_html__( 'Custom text or HTML for bottom of testimonials', 'testimonials-widget' ),
			'type' => 'textarea',
			'validate' => 'wp_kses_post',
		);

		self::$settings['widget_expand_end'] = array(
			'section' => 'widget',
			'type' => 'expand_end',
		);

		// General
		self::$settings['general_expand_begin'] = array(
			'desc' => esc_html__( 'General Options', 'testimonials-widget' ),
			'type' => 'expand_begin',
		);

		$desc = __( 'Adds HTML tag markup per the <a href="%s">Review schema</a> to testimonials. Search engines including Bing, Google, Yahoo! and Yandex rely on this markup to improve the display of search results.', 'testimonials-widget' );

		self::$settings['enable_schema'] = array(
			'title' => esc_html__( 'Enable Review Schema?', 'testimonials-widget' ),
			'desc' => sprintf( $desc, 'http://schema.org/Review' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
		);

		self::$settings['item_reviewed'] = array(
			'title' => esc_html__( 'Reviewed Item?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Name of thing being referenced in testimonials', 'testimonials-widget' ),
			'std' => get_option( 'blogname' ),
			'widget' => 0,
		);

		self::$settings['item_reviewed_url'] = array(
			'title' => esc_html__( 'Reviewed Item URL?', 'testimonials-widget' ),
			'desc' => esc_html__( 'URL of thing being referenced in testimonials', 'testimonials-widget' ),
			'std' => network_site_url(),
			'validate' => 'url',
			'widget' => 0,
		);

		self::$settings['disable_quotes'] = array(
			'title' => esc_html__( 'Hide built-in quotes?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Remove open and close quote span tags surrounding testimonial content', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['hide_not_found'] = array(
			'title' => esc_html__( 'Hide "Testimonials Not Found"?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['hide_gravatar'] = array(
			'title' => esc_html__( 'Hide Gravatar Image?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['hide_image'] = array(
			'title' => esc_html__( 'Hide Image?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['hide_image_single'] = array(
			'title' => esc_html__( 'Hide Image in Single View?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'widget' => 0,
		);

		self::$settings['hide_content'] = array(
			'title' => esc_html__( 'Hide Testimonial Content?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['hide_source'] = array(
			'title' => esc_html__( 'Hide Author/Source?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'Don\'t display "Post Title" in cite', 'testimonials-widget' ),
		);

		self::$settings['hide_email'] = array(
			'title' => esc_html__( 'Hide Email?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
		);

		self::$settings['hide_title'] = array(
			'title' => esc_html__( 'Hide Job Title?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['hide_location'] = array(
			'title' => esc_html__( 'Hide Location?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['hide_company'] = array(
			'title' => esc_html__( 'Hide Company?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['hide_url'] = array(
			'title' => esc_html__( 'Hide URL?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['target'] = array(
			'title' => esc_html__( 'URL Target', 'testimonials-widget' ),
			'desc' => esc_html__( 'Add target to all URLs; leave blank if none', 'testimonials-widget' ),
			'validate' => 'term',
		);

		self::$settings['paging'] = array(
			'title' => esc_html__( 'Enable Paging?', 'testimonials-widget' ),
			'desc' => esc_html__( 'For `[testimonialswidget_list]`', 'testimonials-widget' ),
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

		self::$settings['do_shortcode'] = array(
			'title' => esc_html__( 'Do [shortcodes]?', 'testimonials-widget' ),
			'desc' => esc_html__( 'If unchecked, shortcodes are stripped.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
		);

		self::$settings['general_expand_end'] = array(
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
			'desc' => esc_html__( 'Comma separated category names. Ex: Category A, Another category', 'testimonials-widget' ),
			'validate' => 'terms',
			'suggest' => true,
		);

		self::$settings['tags'] = array(
			'section' => 'selection',
			'title' => esc_html__( 'Tags Filter', 'testimonials-widget' ),
			'desc' => esc_html__( 'Comma separated tag names. Ex: Tag A, Another tag', 'testimonials-widget' ),
			'validate' => 'terms',
			'suggest' => true,
		);

		self::$settings['tags_all'] = array(
			'section' => 'selection',
			'title' => esc_html__( 'Require All Tags?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Select only testimonials with all of the given tags', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['ids'] = array(
			'section' => 'selection',
			'title' => esc_html__( 'Include IDs Filter', 'testimonials-widget' ),
			'desc' => esc_html__( 'Comma separated testimonial IDs. Ex: 3,1,2', 'testimonials-widget' ),
			'validate' => 'ids',
		);

		self::$settings['exclude'] = array(
			'section' => 'selection',
			'title' => esc_html__( 'Exclude IDs Filter', 'testimonials-widget' ),
			'desc' => esc_html__( 'Comma separated testimonial IDs. Ex: 3,1,2', 'testimonials-widget' ),
			'validate' => 'ids',
		);

		self::$settings['limit'] = array(
			'section' => 'selection',
			'title' => esc_html__( 'Limit', 'testimonials-widget' ),
			'desc' => esc_html__( 'Number of testimonials to select per instance', 'testimonials-widget' ),
			'std' => 10,
			'validate' => 'nozero',
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

		self::$settings['random'] = array(
			'section' => 'ordering',
			'title' => esc_html__( 'Random Order?', 'testimonials-widget' ),
			'desc' => esc_html__( 'If checked, ignores ORDER BY, ORDER BY meta_key, and ORDER BY Order. Widgets are random by default automatically', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['orderby'] = array(
			'section' => 'ordering',
			'title' => esc_html__( 'ORDER BY', 'testimonials-widget' ),
			'desc' => esc_html__( 'Used when "Random Order" is disabled', 'testimonials-widget' ),
			'type' => 'select',
			'choices' => array(
				'ID' => esc_html__( 'Testimonial ID', 'testimonials-widget' ),
				'author' => esc_html__( 'Author', 'testimonials-widget' ),
				'date' => esc_html__( 'Date', 'testimonials-widget' ),
				'menu_order' => esc_html__( 'Menu Order', 'testimonials-widget' ),
				'title' => esc_html__( 'Source', 'testimonials-widget' ),
				'none' => esc_html__( 'No order', 'testimonials-widget' ),
			),
			'std' => 'ID',
			'validate' => 'term',
		);

		self::$settings['meta_key'] = array(
			'section' => 'ordering',
			'title' => esc_html__( 'ORDER BY meta_key', 'testimonials-widget' ),
			'desc' => esc_html__( 'Used when "Random Order" is disabled and sorting by a testimonials meta key is needed. Overrides ORDER BY', 'testimonials-widget' ),
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
		);

		$desc        = __( 'URL slug-name for <a href="%1s">testimonials archive</a> page.', 'testimonials-widget' );
		$has_archive = tw_get_option( 'has_archive', '' );
		$site_url    = network_site_url( '/' . $has_archive . '/' );

		self::$settings['has_archive'] = array(
			'section' => 'post_type',
			'title' => esc_html__( 'Archive Page URL', 'testimonials-widget' ),
			'desc' => sprintf( $desc, $site_url ),
			'std' => 'testimonials-archive',
			'validate' => 'sanitize_title',
			'widget' => 0,
		);

		$desc = esc_html__( 'URL slug-name for testimonial view pages. Shouldn\'t be the same as the Archive Page URL nor should it match a page URL slug.', 'testimonials-widget' );

		self::$settings['rewrite_slug'] = array(
			'section' => 'post_type',
			'title' => esc_html__( 'Testimonial Page URL', 'testimonials-widget' ),
			'desc' => $desc,
			'std' => 'testimonial',
			'validate' => 'sanitize_title',
			'widget' => 0,
		);

		// Reset
		self::$settings['reset_expand_begin'] = array(
			'section' => 'reset',
			'desc' => esc_html__( 'Reset & Compatiblity Options', 'testimonials-widget' ),
			'type' => 'expand_begin',
		);

		self::$settings['use_cpt_taxonomy'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Don\'t Use Default Taxonomies?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'If checked, use Testimonials\' own category and tag taxonomies instead', 'testimonials-widget' ),
			'widget' => 0,
		);

		parent::settings();

		self::$settings['version_options_heading'] = array(
			'section' => 'reset',
			'desc' => esc_html__( 'Version Based Options', 'testimonials-widget' ),
			'type' => 'heading',
		);

		self::$settings['use_bxslider'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Use bxSlider?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Pre 2.15.0, Testimonials\' used custom JavaScript for transitions.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'backwards' => array(
				'version' => '2.15.0',
				'std' => 0,
			),
			'std' => 1,
		);

		self::$settings['disable_animation'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Disable Animation?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Pre 2.15.0, Disable animation between testimonial transitions. Useful when stacking widgets.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
		);

		self::$settings['fade_out_speed'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Fade Out Speed', 'testimonials-widget' ),
			'desc' => esc_html__( 'Pre 2.15.0, Transition duration in milliseconds; higher values indicate slower animations, not faster ones.', 'testimonials-widget' ),
			'std' => 1250,
			'validate' => 'absint',
		);

		self::$settings['fade_in_speed'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Fade In Speed', 'testimonials-widget' ),
			'desc' => esc_html__( 'Pre 2.15.0, Transition duration in milliseconds; higher values indicate slower animations, not faster ones.', 'testimonials-widget' ),
			'std' => 500,
			'validate' => 'absint',
		);

		self::$settings['height'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Height', 'testimonials-widget' ),
			'desc' => esc_html__( 'Pre 2.15.0, Testimonials height, in pixels. Overrides minimum and maximum height', 'testimonials-widget' ),
			'validate' => 'min1',
		);

		self::$settings['min_height'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Minimum Height', 'testimonials-widget' ),
			'desc' => esc_html__( 'Pre 2.15.0, Set for minimum display height, in pixels', 'testimonials-widget' ),
			'validate' => 'min1',
		);

		self::$settings['max_height'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Maximum Height', 'testimonials-widget' ),
			'desc' => esc_html__( 'Pre 2.15.0, Set for maximum display height, in pixels', 'testimonials-widget' ),
			'validate' => 'min1',
		);

		self::$settings['force_css_loading'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Always Load CSS?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Pre 2.14.0, Testimonials\' CSS was always loaded, whether needed or not', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'backwards' => array(
				'version' => '2.14.0',
				'std' => 1,
			),
		);

		self::$settings['include_ie7_css'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Include IE7 CSS?', 'testimonials-widget' ),
			'desc' => esc_html__( 'IE7 specific CSS moved to separate CSS file in version 2.13.6.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'backwards' => array(
				'version' => '2.13.6',
				'std' => 1,
			),
			'widget' => 1,
		);

		self::$settings['use_quote_tag'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Use `&lt;q&gt;` tag?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Pre 2.11.0, not HTML5 compliant', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'backwards' => array(
				'version' => '2.11.0',
				'std' => 1,
			),
		);

		self::$settings['remove_hentry'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Remove `.hentry` CSS?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Pre 2.6.4, some themes use class `.hentry` in a manner that breaks Testimonials\' CSS', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'backwards' => array(
				'version' => '2.6.4',
				'std' => 1,
			),
		);

		self::$settings['reset_expand_end'] = array(
			'section' => 'reset',
			'type' => 'expand_end',
		);

		self::$settings = apply_filters( 'testimonials_widget_settings', self::$settings );

		foreach ( self::$settings as $id => $parts )
			self::$settings[ $id ] = wp_parse_args( $parts, self::$default );
	}


	public static function get_defaults( $mode = null, $old_version = null ) {
		$old_version = tw_get_option( 'version' );

		return parent::get_defaults( $mode, $old_version );
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
			case 'text':
				extract( $args );

				if ( is_null( $input ) )
					$options = get_option( self::ID );
				else {
					$options      = array();
					$options[$id] = $input;
				}

				if ( ! isset( $options[$id] ) )
					$options[$id] = $std;

				$field_class = '';
				if ( ! empty( $class ) )
					$field_class = ' ' . $class;

				$field_class = esc_attr( $field_class );

				$suggest_id = 'suggest_' . self::$suggest_id++;

				$content .= '<input class="regular-text' . $field_class . ' ' . $suggest_id . '" type="text" id="' . $id . '" name="' . self::ID . '[' . $id . ']" placeholder="' . $std . '" value="' . $options[$id] . '" />';

				if ( ! empty( $suggest ) )
					$content .= self::get_suggest( $id, $suggest_id );

				if ( ! empty( $desc ) )
					$content .= '<br /><span class="description">' . $desc . '</span>';

				if ( $show_code )
					$content .= '<br /><code>' . $id . '</code>';
				break;

			default:
				$content = apply_filters( 'testimonials_widget_display_setting', $content, $args, $input );
				break;
		}

		if ( empty( $content ) )
			$content = parent::display_setting( $args, false, $input );

		if ( ! $do_echo )
			return $content;

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

		if ( ! empty( $input['has_archive'] ) )
			$input['has_archive'] = self::prevent_slug_conflict( $input['has_archive'] );
		else
			$input['has_archive'] = $defaults['has_archive'];

		if ( ! empty( $input['rewrite_slug'] ) )
			$input['rewrite_slug'] = self::prevent_slug_conflict( $input['rewrite_slug'] );
		else
			$input['rewrite_slug'] = $defaults['rewrite_slug'];

		$flush_rewrite_rules = false;
		// same has_archive and rewrite_slug causes problems
		if ( $input['has_archive'] == $input['rewrite_slug'] ) {
			$input['has_archive']  = $defaults['has_archive'];
			$input['rewrite_slug'] = $defaults['rewrite_slug'];

			$flush_rewrite_rules = true;
		}

		// did URL slugs change?
		$has_archive  = tw_get_option( 'has_archive' );
		$rewrite_slug = tw_get_option( 'rewrite_slug' );
		if ( $has_archive != $input['has_archive'] || $rewrite_slug != $input['rewrite_slug'] )
			$flush_rewrite_rules = true;

		if ( $flush_rewrite_rules )
			flush_rewrite_rules();

		$input['version']        = self::$version;
		$input['donate_version'] = Testimonials_Widget::VERSION;

		$input = apply_filters( 'testimonials_widget_validate_settings', $input, $errors );
		if ( empty( $do_errors ) )
			$validated = $input;
		else {
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
		if ( ! $post_parent && self::is_cpt_slug( $slug ) )
			return true;

		return $is_bad_hierarchical_slug;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function is_bad_flat_slug( $is_bad_flat_slug, $slug, $post_type ) {
		if ( self::is_cpt_slug( $slug ) )
			return true;

		return $is_bad_flat_slug;
	}


	public static function is_cpt_slug( $slug ) {
		$has_archive  = tw_get_option( 'has_archive' );
		$rewrite_slug = tw_get_option( 'rewrite_slug' );

		return in_array( $slug, array( $has_archive, $rewrite_slug ) );
	}


	public static function settings_add_help_tabs() {
		$screen = get_current_screen();
		if ( self::$admin_page != $screen->id )
			return;

		$screen->set_help_sidebar(
			'<p>' .
			esc_html__( 'These Testimonials Settings establish the default option values for shortcodes, theme functions, and widget instances. Widgets, once created no longer inherit these global settings. Therefore, you\'ll need to update each widget with the new settings. It might be easier to delete the widget and then recreate it.', 'testimonials-widget' ) .
			'</p><p>' .
			esc_html__( 'Shortcode option names are listed below each entry.', 'testimonials-widget' ) .
			'</p><p>' .
			sprintf(
				__( 'View the <a href="%s">Testimonials documentation</a>.', 'testimonials-widget' ),
				esc_url( 'http://wordpress.org/plugins/testimonials-widget/' )
			) .
			'</p>'
		);

		$screen->add_help_tab(
			array(
				'id'     => 'tw-general',
				'title'     => esc_html__( 'General', 'testimonials-widget' ),
				'content' => '<p>' . esc_html__( 'Show or hide optional fields.', 'testimonials-widget' ) . '</p>'
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
				'id'     => 'tw-widget',
				'title'     => esc_html__( 'Widget', 'testimonials-widget' ),
				'content' => '<p>' . esc_html__( 'Options related to showing testimonials in widgets.', 'testimonials-widget' ) . '</p>'
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
				'id'     => 'tw-reset',
				'title'     => esc_html__( 'Compatibility & Reset', 'testimonials-widget' ),
				'content' => '<p>' . esc_html__( 'Backwards compatibility, import/export options, and reset options.', 'testimonials-widget' ) . '</p>'
			)
		);

		do_action( 'testimonials_widget_settings_add_help_tabs', $screen );
	}


	public static function get_suggest( $id, $suggest_id ) {
		wp_enqueue_script( 'suggest' );

		$use_cpt_taxonomy = tw_get_option( 'use_cpt_taxonomy', false );

		switch ( $id ) {
			case 'category':
				if ( ! $use_cpt_taxonomy )
					$taxonomy = 'category';
				else
					$taxonomy = Testimonials_Widget::$cpt_category;

				break;

			case 'tags':
				if ( ! $use_cpt_taxonomy )
					$taxonomy = 'post_tag';
				else
					$taxonomy = Testimonials_Widget::$cpt_tags;

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

	if ( isset( $options[$option] ) )
		return $options[$option];
	else
		return $default;
}


function tw_set_option( $option, $value = null ) {
	$options = get_option( Testimonials_Widget_Settings::ID );

	if ( ! is_array( $options ) )
		$options = array();

	$options[$option] = $value;
	update_option( Testimonials_Widget_Settings::ID, $options );
}


?>
