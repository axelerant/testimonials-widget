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
 * Testimonials Widget settings class
 *
 * Based upon http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-1/
 */


class Testimonials_Widget_Settings {
	const ID = 'testimonialswidget_settings';

	public static $admin_page = '';
	public static $default    = array(
		'backwards' => array(
			'version' => '', // below this version number, use std
			'std' => '',
		),
		'choices' => array(), // key => value
		'class' => '',
		'desc' => '',
		'id' => 'default_field',
		'section' => 'general',
		'std' => '', // default key or value
		'title' => '',
		'type' => 'text', // textarea, checkbox, radio, select, hidden, heading, password, expand_begin, expand_end
		'validate' => '', // required, term, slug, slugs, ids, order, single paramater PHP functions
		'widget' => 1, // show in widget options, 0 off
	);

	public static $defaults = array();
	public static $sections = array();
	public static $settings = array();
	public static $version  = null;


	public function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		load_plugin_textdomain( 'testimonials-widget', false, '/testimonials-widget/languages/' );
	}


	public function admin_init() {
		add_filter( 'wp_unique_post_slug_is_bad_hierarchical_slug', array( $this, 'is_bad_hierarchical_slug' ), 10, 4 );
		add_filter( 'wp_unique_post_slug_is_bad_flat_slug', array( $this, 'is_bad_flat_slug' ), 10, 3 );

		$version       = tw_get_option( 'version' );
		self::$version = Testimonials_Widget::VERSION;
		self::$version = apply_filters( 'testimonials_widget_version', self::$version );

		if ( $version != self::$version )
			$this->initialize_settings();

		if ( ! self::do_load() )
			return;

		self::sections();
		self::settings();

		$this->register_settings();
	}


	public function admin_menu() {
		self::$admin_page = add_submenu_page( 'edit.php?post_type=' . Testimonials_Widget::PT, esc_html__( 'Testimonials Widget Settings', 'testimonials-widget' ), esc_html__( 'Settings', 'testimonials-widget' ), 'manage_options', self::ID, array( 'Testimonials_Widget_Settings', 'display_page' ) );

		add_action( 'admin_print_scripts-' . self::$admin_page, array( $this, 'scripts' ) );
		add_action( 'admin_print_styles-' . self::$admin_page, array( $this, 'styles' ) );
		add_action( 'load-' . self::$admin_page, array( $this, 'settings_add_help_tabs' ) );
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function do_load() {
		$do_load = false;
		if ( ! empty( $GLOBALS['pagenow'] ) && in_array( $GLOBALS['pagenow'], array( 'options.php' ) ) ) {
			$do_load = true;
		} elseif ( ! empty( $_REQUEST['post_type'] ) && Testimonials_Widget::PT == $_REQUEST['post_type'] ) {
			if ( ! empty( $GLOBALS['pagenow'] ) && in_array( $GLOBALS['pagenow'], array( 'edit.php' ) ) ) {
				$do_load = true;
			} elseif ( ! empty( $_REQUEST['page'] ) && self::ID == $_REQUEST['page'] ) {
				$do_load = true;
			}
		}

		return $do_load;
	}


	public static function sections() {
		self::$sections['general']   = esc_html__( 'General', 'testimonials-widget' );
		self::$sections['selection'] = esc_html__( 'Selection', 'testimonials-widget' );
		self::$sections['ordering']  = esc_html__( 'Ordering', 'testimonials-widget' );
		self::$sections['widget']    = esc_html__( 'Widget', 'testimonials-widget' );
		self::$sections['post_type'] = esc_html__( 'Post Type', 'testimonials-widget' );
		self::$sections['reset']     = esc_html__( 'Compatibility & Reset', 'testimonials-widget' );
		self::$sections['about']     = esc_html__( 'About Testimonials Widget', 'testimonials-widget' );

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

		self::$settings['height'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Height', 'testimonials-widget' ),
			'desc' => esc_html__( 'Testimonials height, in pixels. Overrides minimum and maximum height', 'testimonials-widget' ),
			'validate' => 'min1',
		);

		self::$settings['refresh_interval'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Rotation Speed', 'testimonials-widget' ),
			'desc' => esc_html__( 'Number of seconds between testimonial rotations or 0 for no rotation at all refresh', 'testimonials-widget' ),
			'std' => 5,
			'validate' => 'absint',
		);

		self::$settings['widget_expand_begin'] = array(
			'section' => 'widget',
			'desc' => esc_html__( 'Additional Widget Options', 'testimonials-widget' ),
			'type' => 'expand_begin',
		);

		self::$settings['keep_whitespace'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Keep Whitespace?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Keeps testimonials looking as entered than sans auto-formatting', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
		);

		self::$settings['disable_animation'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Disable Animation?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Disable animation between testimonial transitions. Useful when stacking widgets.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 1,
		);

		self::$settings['fade_out_speed'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Fade Out Speed', 'testimonials-widget' ),
			'desc' => esc_html__( 'Transition duration in milliseconds; higher values indicate slower animations, not faster ones.', 'testimonials-widget' ),
			'std' => 1250,
			'validate' => 'absint',
		);

		self::$settings['fade_in_speed'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Fade In Speed', 'testimonials-widget' ),
			'desc' => esc_html__( 'Transition duration in milliseconds; higher values indicate slower animations, not faster ones.', 'testimonials-widget' ),
			'std' => 500,
			'validate' => 'absint',
		);

		self::$settings['min_height'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Minimum Height', 'testimonials-widget' ),
			'desc' => esc_html__( 'Set for minimum display height, in pixels', 'testimonials-widget' ),
			'validate' => 'min1',
		);

		self::$settings['max_height'] = array(
			'section' => 'widget',
			'title' => esc_html__( 'Maximum Height', 'testimonials-widget' ),
			'desc' => esc_html__( 'Set for maximum display height, in pixels', 'testimonials-widget' ),
			'validate' => 'min1',
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
		);

		self::$settings['item_reviewed_url'] = array(
			'title' => esc_html__( 'Reviewed Item URL?', 'testimonials-widget' ),
			'desc' => esc_html__( 'URL of thing being referenced in testimonials', 'testimonials-widget' ),
			'std' => network_site_url(),
			'validate' => 'url',
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
			'desc' => esc_html__( 'Comma separated category slug-names. Ex: category-a, another-category', 'testimonials-widget' ),
			'validate' => 'slugs',
		);

		self::$settings['tags'] = array(
			'section' => 'selection',
			'title' => esc_html__( 'Tags Filter', 'testimonials-widget' ),
			'desc' => esc_html__( 'Comma separated tag slug-names. Ex: tag-a, another-tag', 'testimonials-widget' ),
			'validate' => 'slugs',
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
			'desc' => esc_html__( 'Only affects the Testimonials Widget post edit page. Your theme controls the front-end view.', 'testimonials-widget' ),
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
			'desc' => esc_html__( 'Compatiblity Options', 'testimonials-widget' ),
			'type' => 'expand_begin',
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

		self::$settings['remove_hentry'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Remove `.hentry` CSS?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Pre 2.6.4. Some themes use class `.hentry` in a manner that breaks Testimonials Widgets CSS', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'backwards' => array(
				'version' => '2.6.4',
				'std' => 1,
			),
		);

		self::$settings['use_quote_tag'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Use `&lt;q&gt;` tag?', 'testimonials-widget' ),
			'desc' => esc_html__( 'Pre 2.11.0. Not HTML5 compliant', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'backwards' => array(
				'version' => '2.11.0',
				'std' => 1,
			),
		);

		self::$settings['use_cpt_taxonomy'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Don\'t Use Default Taxonomies?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'desc' => esc_html__( 'If checked, use Testimonials Widget\'s own category and tag taxonomies instead', 'testimonials-widget' ),
			'widget' => 0,
		);

		$options = get_option( self::ID );
		if ( ! empty( $options ) ) {
			$serialized_options = serialize( $options );
			$_SESSION['export'] = $serialized_options;

			self::$settings['export'] = array(
				'section' => 'reset',
				'title' => esc_html__( 'Export Settings', 'testimonials-widget' ),
				'type' => 'readonly',
				'desc' => esc_html__( 'These are your current settings in a serialized format. Copy the contents to make a backup of your settings.', 'testimonials-widget' ),
				'std' => $serialized_options,
				'widget' => 0,
			);
		}

		self::$settings['import'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Import Settings', 'testimonials-widget' ),
			'type' => 'textarea',
			'desc' => esc_html__( 'Paste new serialized settings here to overwrite your current configuration.', 'testimonials-widget' ),
			'widget' => 0,
		);

		self::$settings['delete_data'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Remove Plugin Data on Deletion?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'class' => 'warning', // Custom class for CSS
			'desc' => esc_html__( 'Delete all Testimonials Widget data and options from database on plugin deletion', 'testimonials-widget' ),
			'widget' => 0,
		);

		self::$settings['reset_defaults'] = array(
			'section' => 'reset',
			'title' => esc_html__( 'Reset to Defaults?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'class' => 'warning', // Custom class for CSS
			'desc' => esc_html__( 'Check this box to reset options to their defaults', 'testimonials-widget' ),
			'widget' => 0,
		);

		self::$settings['reset_expand_end'] = array(
			'section' => 'reset',
			'type' => 'expand_end',
		);

		self::$settings = apply_filters( 'testimonials_widget_settings', self::$settings );

		foreach ( self::$settings as $id => $parts ) {
			self::$settings[ $id ] = wp_parse_args( $parts, self::$default );
		}
	}


	public static function get_defaults( $mode = null ) {
		if ( empty( self::$defaults ) )
			self::settings();

		$do_backwards = false;
		if ( 'backwards' == $mode ) {
			$old_version = tw_get_option( 'version' );
			if ( ! empty( $old_version ) )
				$do_backwards = true;
		}

		foreach ( self::$settings as $id => $parts ) {
			$std = isset( $parts['std'] ) ? $parts['std'] : '';
			if ( $do_backwards ) {
				$version = ! empty( $parts['backwards']['version'] ) ? $parts['backwards']['version'] : false;
				if ( ! empty( $version ) ) {
					if ( $old_version < $version )
						$std = $parts['backwards']['std'];
				}
			}

			self::$defaults[ $id ] = $std;
		}

		return self::$defaults;
	}


	public static function get_settings() {
		if ( empty( self::$settings ) )
			self::settings();

		return self::$settings;
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	public function create_setting( $args = array() ) {
		extract( $args );

		if ( preg_match( '#(_expand_begin|_expand_end)#', $id ) )
			return;

		$field_args = array(
			'type' => $type,
			'id' => $id,
			'desc' => $desc,
			'std' => $std,
			'choices' => $choices,
			'label_for' => $id,
			'class' => $class,
		);

		self::$defaults[$id] = $std;

		add_settings_field( $id, $title, array( $this, 'display_setting' ), self::ID, $section, $field_args );
	}


	public static function display_page() {
		echo '<div class="wrap">
			<div class="icon32" id="icon-options-general"></div>
			<h2>' . esc_html__( 'Testimonials Widget Settings', 'testimonials-widget' ) . '</h2>';

		echo '<form action="options.php" method="post">';

		settings_fields( self::ID );

		echo '<div id="' . self::ID . '">
			<ul>';

		foreach ( self::$sections as $section_slug => $section )
			echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';

		echo '</ul>';

		self::do_settings_sections( self::ID );

		echo '
			<p class="submit"><input name="Submit" type="submit" class="button-primary" value="' . esc_html__( 'Save Changes', 'testimonials-widget' ) . '" /></p>
			</form>
			</div>
			';

		echo '<p>' .
			sprintf(
			__( 'When ready, <a href="%1$s">view</a> or <a href="%2$s">add</a> testimonials.', 'testimonials-widget' ),
			esc_url( get_admin_url() . 'edit.php?post_type=testimonials-widget' ),
			esc_url( get_admin_url() . 'post-new.php?post_type=testimonials-widget' )
		) .
			'</p>';

		$disable_donate = tw_get_option( 'disable_donate' );
		if ( ! $disable_donate ) {
			echo '<p>' .
				sprintf(
				__( 'If you like this plugin, please <a href="%1$s" title="Donate for Good Karma"><img src="%2$s" border="0" alt="Donate for Good Karma" /></a> or <a href="%3$s" title="purchase Testimonials Widget Premium">purchase Testimonials Widget Premium</a> to help fund further development and <a href="%4$s" title="Support forums">support</a>.', 'testimonials-widget' ),
				esc_url( 'http://aihr.us/about-aihrus/donate/' ),
				esc_url( 'https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif' ),
				esc_url( 'http://aihr.us/downloads/testimonials-widget-premium-wordpress-plugin/' ),
				esc_url( 'https://aihrus.zendesk.com/categories/20104507-Testimonials-Widget' )
			) .
				'</p>';
		}

		echo '<p class="copyright">' .
			sprintf(
			__( 'Copyright &copy;%1$s <a href="%2$s">Aihrus</a>.', 'testimonials-widget' ),
			date( 'Y' ),
			esc_url( 'http://aihr.us' )
		) .
			'</p>';

		self::section_scripts();

		echo '</div>';
	}


	public static function section_scripts() {
		echo '
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$( "#' . self::ID . '" ).tabs();
		// This will make the "warning" checkbox class really stand out when checked.
		$(".warning").change(function() {
			if ($(this).is(":checked"))
				$(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");
			else
				$(this).parent().css("background", "inherit").css("color", "inherit").css("fontWeight", "inherit");
		});
	});
</script>
';
	}


	public static function do_settings_sections( $page ) {
		global $wp_settings_sections, $wp_settings_fields;

		if ( ! isset( $wp_settings_sections ) || !isset( $wp_settings_sections[$page] ) )
			return;

		foreach ( (array) $wp_settings_sections[$page] as $section ) {
			if ( $section['callback'] )
				call_user_func( $section['callback'], $section );

			if ( ! isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section['id']] ) )
				continue;

			echo '<table id=' . $section['id'] . ' class="form-table">';
			do_settings_fields( $page, $section['id'] );
			echo '</table>';
		}
	}


	public function display_section() {}


	public function display_about_section() {
		echo '
			<div id="about" style="width: 70%; min-height: 225px;">
				<p><img class="alignright size-medium" title="Michael in Red Square, Moscow, Russia" src="' . WP_PLUGIN_URL . '/testimonials-widget/media/michael-cannon-red-square-300x2251.jpg" alt="Michael in Red Square, Moscow, Russia" width="300" height="225" /><a href="http://wordpress.org/extend/plugins/testimonials-widget/">Testimonials Widget</a> is by <a href="http://aihr.us/about-aihrus/michael-cannon-resume/">Michael Cannon</a>. He\'s <a title="Lot\'s of stuff about Peichi Liu…" href="http://peimic.com/t/peichi-liu/">Peichi’s</a> smiling man, an adventurous <a title="Water rat" href="http://www.chinesehoroscope.org/chinese_zodiac/rat/" target="_blank">water-rat</a>, <a title="Axelerant – Open Source. Engineered." href="http://axelerant.com/who-we-are">chief people officer</a>, <a title="Road biker, cyclist, biking; whatever you call, I love to ride" href="http://peimic.com/c/biking/">cyclist</a>, <a title="Aihrus – website support made easy since 1999" href="http://aihr.us/about-aihrus/">full stack developer</a>, <a title="Michael\'s poetic like literary ramblings" href="http://peimic.com/t/poetry/">poet</a>, <a title="World Wide Opportunities on Organic Farms" href="http://peimic.com/t/WWOOF/">WWOOF’er</a> and <a title="My traveled to country list, is more than my age." href="http://peimic.com/c/travel/">world traveler</a>.</p>
			</div>
		';
	}


	public static function display_setting( $args = array(), $do_echo = true, $input = null ) {
		$content = '';

		extract( $args );

		if ( ! isset( $no_code ) )
			$no_code = false;

		if ( is_null( $input ) ) {
			$options = get_option( self::ID );
		} else {
			$options      = array();
			$options[$id] = $input;
		}

		if ( ! isset( $options[$id] ) && $type != 'checkbox' )
			$options[$id] = $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id] = 0;

		$field_class = '';
		if ( ! empty( $class ) )
			$field_class = ' ' . $class;

		// desc isn't escaped because it's might contain allowed html
		$choices      = array_map( 'esc_attr', $choices );
		$field_class  = esc_attr( $field_class );
		$id           = esc_attr( $id );
		$options[$id] = esc_attr( $options[$id] );
		$std          = esc_attr( $std );

		switch ( $type ) {
		case 'checkbox':
			$content .= '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="' . self::ID . '[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> ';

			if ( ! empty( $desc ) )
				$content .= '<label for="' . $id . '"><span class="description">' . $desc . '</span></label>';

			if ( ! $no_code )
				$content .= '<br /><code>' . $id . '</code>';
			break;

		case 'file':
			$content .= '<input class="regular-text' . $field_class . '" type="file" id="' . $id . '" name="' . self::ID . '[' . $id . ']" />';

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			break;

		case 'heading':
			$content .= '</td></tr><tr valign="top"><td colspan="2"><h4>' . $desc . '</h4>';
			break;

		case 'hidden':
			$content .= '<input type="hidden" id="' . $id . '" name="' . self::ID . '[' . $id . ']" value="' . $options[$id] . '" />';

			break;

		case 'password':
			$content .= '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="' . self::ID . '[' . $id . ']" value="' . $options[$id] . '" />';

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			break;

		case 'radio':
			$i             = 1;
			$count_choices = count( $choices );
			foreach ( $choices as $value => $label ) {
				$content .= '<input class="radio' . $field_class . '" type="radio" name="' . self::ID . '[' . $id . ']" id="' . $id . $i . '" value="' . $value . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';

				if ( $i < $count_choices )
					$content .= '<br />';

				$i++;
			}

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			if ( ! $no_code )
				$content .= '<br /><code>' . $id . '</code>';
			break;

		case 'readonly':
			$content .= '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="' . self::ID . '[' . $id . ']" value="' . $options[$id] . '" readonly="readonly" />';

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			break;

		case 'select':
			$content .= '<select class="select' . $field_class . '" name="' . self::ID . '[' . $id . ']">';

			foreach ( $choices as $value => $label )
				$content .= '<option value="' . $value . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';

			$content .= '</select>';

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			if ( ! $no_code )
				$content .= '<br /><code>' . $id . '</code>';
			break;

		case 'text':
			$content .= '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="' . self::ID . '[' . $id . ']" placeholder="' . $std . '" value="' . $options[$id] . '" />';

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			if ( ! $no_code )
				$content .= '<br /><code>' . $id . '</code>';
			break;

		case 'textarea':
			$content .= '<textarea class="' . $field_class . '" id="' . $id . '" name="' . self::ID . '[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			if ( ! $no_code )
				$content .= '<br /><code>' . $id . '</code>';
			break;

		default:
			$content = apply_filters( 'testimonials_widget_display_setting', '', $args, $input );
			break;
		}

		if ( ! $do_echo )
			return $content;

		echo $content;
	}


	public function initialize_settings() {
		$defaults                 = self::get_defaults( 'backwards' );
		$current                  = get_option( self::ID );
		$current                  = wp_parse_args( $current, $defaults );
		$current['admin_notices'] = tw_get_option( 'version', self::$version );
		$current['version']       = self::$version;

		update_option( self::ID, $current );
	}


	public function register_settings() {
		register_setting( self::ID, self::ID, array( $this, 'validate_settings' ) );

		foreach ( self::$sections as $slug => $title ) {
			if ( $slug == 'about' )
				add_settings_section( $slug, $title, array( $this, 'display_about_section' ), self::ID );
			else
				add_settings_section( $slug, $title, array( $this, 'display_section' ), self::ID );
		}

		foreach ( self::$settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}
	}


	public function scripts() {
		wp_enqueue_script( 'jquery-ui-tabs' );
	}


	public function styles() {
		if ( ! is_ssl() )
			wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
		else
			wp_enqueue_style( 'jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function validate_settings( $input, $options = null, $do_errors = false ) {
		$errors = array();

		if ( is_null( $options ) ) {
			$options  = self::get_settings();
			$defaults = self::get_defaults();

			if ( is_admin() ) {
				if ( ! empty( $input['reset_defaults'] ) ) {
					foreach ( $defaults as $id => $std ) {
						$input[$id] = $std;
					}

					unset( $input['reset_defaults'] );
				}

				if ( ! empty( $input['import'] ) && $_SESSION['export'] != $input['import'] ) {
					$import       = $input['import'];
					$unserialized = unserialize( $import );
					if ( is_array( $unserialized ) ) {
						foreach ( $unserialized as $id => $std )
							$input[$id] = $std;
					}
				}
			}
		}

		foreach ( $options as $id => $parts ) {
			$default     = $parts['std'];
			$type        = $parts['type'];
			$validations = ! empty( $parts['validate'] ) ? $parts['validate'] : array();
			if ( ! empty( $validations ) )
				$validations = explode( ',', $validations );

			if ( ! isset( $input[ $id ] ) ) {
				if ( 'checkbox' != $type )
					$input[ $id ] = $default;
				else
					$input[ $id ] = 0;
			}

			if ( $default == $input[ $id ] && ! in_array( 'required', $validations ) )
				continue;

			if ( 'checkbox' == $type ) {
				if ( self::is_true( $input[ $id ] ) )
					$input[ $id ] = 1;
				else
					$input[ $id ] = 0;
			} elseif ( in_array( $type, array( 'radio', 'select' ) ) ) {
				// single choices only
				$keys = array_keys( $parts['choices'] );

				if ( ! in_array( $input[ $id ], $keys ) ) {
					if ( self::is_true( $input[ $id ] ) )
						$input[ $id ] = 1;
					else
						$input[ $id ] = 0;
				}
			}

			if ( ! empty( $validations ) ) {
				foreach ( $validations as $validate )
					self::validators( $validate, $id, $input, $default, $errors );
			}
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
		$input                   = apply_filters( 'testimonials_widget_validate_settings', $input, $errors );

		unset( $input['export'] );
		unset( $input['import'] );

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
	public function is_bad_hierarchical_slug( $is_bad_hierarchical_slug, $slug, $post_type, $post_parent ) {
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
	public function is_bad_flat_slug( $is_bad_flat_slug, $slug, $post_type ) {
		if ( self::is_cpt_slug( $slug ) )
			return true;

		return $is_bad_flat_slug;
	}


	public static function is_cpt_slug( $slug ) {
		$has_archive  = tw_get_option( 'has_archive' );
		$rewrite_slug = tw_get_option( 'rewrite_slug' );

		return in_array( $slug, array( $has_archive, $rewrite_slug ) );
	}


	public static function validators( $validate, $id, &$input, $default, &$errors ) {
		switch ( $validate ) {
		case 'absint':
		case 'intval':
			if ( '' !== $input[ $id ] )
				$input[ $id ] = $validate( $input[ $id ] );
			else
				$input[ $id ] = $default;
			break;

		case 'ids':
			$input[ $id ] = self::validate_ids( $input[ $id ], $default );
			break;

		case 'min1':
			$input[ $id ] = intval( $input[ $id ] );
			if ( 0 >= $input[ $id ] )
				$input[ $id ] = $default;
			break;

		case 'nozero':
			$input[ $id ] = intval( $input[ $id ] );
			if ( 0 === $input[ $id ] )
				$input[ $id ] = $default;
			break;

		case 'order':
			$input[ $id ] = self::validate_order( $input[ $id ], $default );
			break;

		case 'required':
			if ( empty( $input[ $id ] ) )
				$errors[ $id ] = esc_html__( 'Required', 'testimonials-widget' );
			break;

		case 'slug':
			$input[ $id ] = self::validate_slug( $input[ $id ], $default );
			$input[ $id ] = strtolower( $input[ $id ] );
			break;

		case 'slugs':
			$input[ $id ] = self::validate_slugs( $input[ $id ], $default );
			$input[ $id ] = strtolower( $input[ $id ] );
			break;

		case 'term':
			$input[ $id ] = self::validate_term( $input[ $id ], $default );
			$input[ $id ] = strtolower( $input[ $id ] );
			break;

		case 'url':
			$input[ $id ] = self::validate_url( $input[ $id ], $default );
			break;

		case 'is_true':
			$input[ $id ] = self::is_true( $input[ $id ] );
			break;

		default:
			$input[ $id ] = $validate( $input[ $id ] );
			break;
		}
	}


	public static function validate_ids( $input, $default ) {
		if ( preg_match( '#^\d+(,\s?\d+)*$#', $input ) )
			return preg_replace( '#\s#', '', $input );

		return $default;
	}


	public static function validate_order( $input, $default ) {
		if ( preg_match( '#^desc|asc$#i', $input ) )
			return $input;

		return $default;
	}


	public static function validate_slugs( $input, $default ) {
		if ( preg_match( '#^[\w-]+(,\s?[\w-]+)*$#', $input ) )
			return preg_replace( '#\s#', '', $input );

		return $default;
	}


	public static function validate_slug( $input, $default ) {
		if ( preg_match( '#^[\w-]+$#', $input ) )
			return $input;

		return $default;
	}


	public static function validate_term( $input, $default ) {
		if ( preg_match( '#^\w+$#', $input ) )
			return $input;

		return $default;
	}


	public static function validate_url( $input, $default ) {
		if ( filter_var( $input, FILTER_VALIDATE_URL ) )
			return $input;

		return $default;
	}


	/**
	 * Let values like "true, 'true', 1, and 'yes'" to be true. Else, false
	 */
	public static function is_true( $value = null, $return_boolean = true ) {
		if ( true === $value || 'true' == strtolower( $value ) || 1 == $value || 'yes' == strtolower( $value ) ) {
			if ( $return_boolean )
				return true;
			else
				return 1;
		} else {
			if ( $return_boolean )
				return false;
			else
				return 0;
		}
	}


	public function settings_add_help_tabs() {
		$screen = get_current_screen();
		if ( self::$admin_page != $screen->id )
			return;

		$screen->set_help_sidebar(
			'<p>' .
			esc_html__( 'These Testimonials Widget Settings establish the default option values for shortcodes, theme functions, and widget instances. Widgets, once created no longer inherit these global settings. Therefore, you\'ll need to update each widget with the new settings. It might be easier to delete the widget and then recreate it.', 'testimonials-widget' ) .
			'</p><p>' .
			esc_html__( 'Shortcode option names are listed below each entry.', 'testimonials-widget' ) .
			'</p><p>' .
			sprintf(
				__( 'View the <a href="%s">Testimonials Widget documentation</a>.', 'testimonials-widget' ),
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
