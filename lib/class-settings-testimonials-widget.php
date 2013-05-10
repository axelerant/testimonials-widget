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

	public static $default  = array(
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
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		add_action( 'init', array( &$this, 'init' ) );
		load_plugin_textdomain( 'testimonials-widget', false, '/testimonials-widget/languages/' );
	}


	public function init() {
		self::sections();
		self::settings();
	}


	public static function sections() {
		self::$sections['general']   = __( 'General', 'testimonials-widget' );
		self::$sections['selection'] = __( 'Selection', 'testimonials-widget' );
		self::$sections['ordering']  = __( 'Ordering', 'testimonials-widget' );
		self::$sections['widget']    = __( 'Widget', 'testimonials-widget' );
		self::$sections['post_type'] = __( 'Post Type', 'testimonials-widget' );
		self::$sections['reset']     = __( 'Compatibility & Reset', 'testimonials-widget' );
		self::$sections['about']     = __( 'About Testimonials Widget', 'testimonials-widget' );

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
			'title' => __( 'Widget Title', 'testimonials-widget' ),
			'std' => __( 'Testimonials', 'testimonials-widget' ),
			'validate' => 'wp_kses_post',
		);

		self::$settings['title_link'] = array(
			'section' => 'widget',
			'title' => __( 'Title Link', 'testimonials-widget' ),
			'desc' => __( 'URL or Post ID to link widget title to. Ex: 123 or http://example.com', 'testimonials-widget' ),
			'validate' => 'wp_kses_data',
		);

		self::$settings['char_limit'] = array(
			'section' => 'widget',
			'title' => __( 'Character Limit', 'testimonials-widget' ),
			'desc' => __( 'Number of characters to limit non-single testimonial views to', 'testimonials-widget' ),
			'validate' => 'absint',
		);

		self::$settings['height'] = array(
			'section' => 'widget',
			'title' => __( 'Height', 'testimonials-widget' ),
			'desc' => __( 'Testimonials height, in pixels. Overrides minimum and maximum height', 'testimonials-widget' ),
			'validate' => 'min1',
		);

		self::$settings['refresh_interval'] = array(
			'section' => 'widget',
			'title' => __( 'Rotation Speed', 'testimonials-widget' ),
			'desc' => __( 'Number of seconds between testimonial rotations or 0 for no rotation at all refresh', 'testimonials-widget' ),
			'std' => 5,
			'validate' => 'absint',
		);

		self::$settings['widget_expand_begin'] = array(
			'section' => 'widget',
			'desc' => __( 'Additional Widget Options', 'testimonials-widget' ),
			'type' => 'expand_begin',
		);

		self::$settings['keep_whitespace'] = array(
			'section' => 'widget',
			'title' => __( 'Keep Whitespace?', 'testimonials-widget' ),
			'desc' => __( 'Keeps testimonials looking as entered than sans auto-formatting', 'testimonials-widget' ),
			'type' => 'checkbox',
		);

		self::$settings['min_height'] = array(
			'section' => 'widget',
			'title' => __( 'Minimum Height', 'testimonials-widget' ),
			'desc' => __( 'Set for minimum display height, in pixels', 'testimonials-widget' ),
			'validate' => 'min1',
		);

		self::$settings['max_height'] = array(
			'section' => 'widget',
			'title' => __( 'Maximum Height', 'testimonials-widget' ),
			'desc' => __( 'Set for maximum display height, in pixels', 'testimonials-widget' ),
			'validate' => 'min1',
		);

		self::$settings['bottom_text'] = array(
			'section' => 'widget',
			'title' => __( 'Testimonial Bottom Text', 'testimonials-widget' ),
			'desc' => __( 'Custom text or HTML for bottom of testimonials', 'testimonials-widget' ),
			'type' => 'textarea',
			'validate' => 'wp_kses_post',
		);

		self::$settings['widget_expand_end'] = array(
			'section' => 'widget',
			'type' => 'expand_end',
		);

		// General
		self::$settings['general_expand_begin'] = array(
			'desc' => __( 'General Options', 'testimonials-widget' ),
			'type' => 'expand_begin',
		);

		self::$settings['disable_quotes'] = array(
			'title' => __( 'Hide built-in quotes?', 'testimonials-widget' ),
			'desc' => __( 'Remove open and close quote span tags surrounding testimonial content', 'testimonials-widget' ),
			'type' => 'checkbox',
		);

		self::$settings['hide_not_found'] = array(
			'title' => __( 'Hide "Testimonials Not Found"?', 'testimonials-widget' ),
			'type' => 'checkbox',
		);

		self::$settings['hide_gravatar'] = array(
			'title' => __( 'Hide Gravatar Image?', 'testimonials-widget' ),
			'type' => 'checkbox',
		);

		self::$settings['hide_image'] = array(
			'title' => __( 'Hide Image?', 'testimonials-widget' ),
			'type' => 'checkbox',
		);

		self::$settings['hide_image_single'] = array(
			'title' => __( 'Hide Image in Single View?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'widget' => 0,
		);

		self::$settings['hide_content'] = array(
			'title' => __( 'Hide Testimonial Content?', 'testimonials-widget' ),
			'type' => 'checkbox',
		);

		self::$settings['hide_source'] = array(
			'title' => __( 'Hide Author/Source?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'desc' => __( 'Don\'t display "Post Title" in cite', 'testimonials-widget' ),
		);

		self::$settings['hide_email'] = array(
			'title' => __( 'Hide Email?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'std' => 1,
		);

		self::$settings['hide_title'] = array(
			'title' => __( 'Hide Title?', 'testimonials-widget' ),
			'type' => 'checkbox',
		);

		self::$settings['hide_location'] = array(
			'title' => __( 'Hide Location?', 'testimonials-widget' ),
			'type' => 'checkbox',
		);

		self::$settings['hide_company'] = array(
			'title' => __( 'Hide Company?', 'testimonials-widget' ),
			'type' => 'checkbox',
		);

		self::$settings['hide_url'] = array(
			'title' => __( 'Hide URL?', 'testimonials-widget' ),
			'type' => 'checkbox',
		);

		self::$settings['target'] = array(
			'title' => __( 'URL Target', 'testimonials-widget' ),
			'desc' => __( 'Add target to all URLs; leave blank if none', 'testimonials-widget' ),
			'validate' => 'term',
		);

		self::$settings['paging'] = array(
			'title' => __( 'Enable Paging?', 'testimonials-widget' ),
			'desc' => __( 'For `[testimonialswidget_list]`', 'testimonials-widget' ),
			'type' => 'select',
			'choices' => array(
				'' => __( 'Disable', 'testimonials-widget' ),
				1 => __( 'Enable', 'testimonials-widget' ),
				'before' => __( 'Before testimonials', 'testimonials-widget' ),
				'after' => __( 'After testimonials', 'testimonials-widget' ),
			),
			'std' => 1,
			'widget' => 0,
		);

		self::$settings['general_expand_end'] = array(
			'type' => 'expand_end',
		);

		// Selection
		self::$settings['selection_expand_begin'] = array(
			'section' => 'selection',
			'desc' => __( 'Selection Options', 'testimonials-widget' ),
			'type' => 'expand_begin',
		);

		self::$settings['category'] = array(
			'section' => 'selection',
			'title' => __( 'Category Filter', 'testimonials-widget' ),
			'desc' => __( 'Comma separated category slug-names. Ex: category-a, another-category', 'testimonials-widget' ),
			'validate' => 'slugs',
		);

		self::$settings['tags'] = array(
			'section' => 'selection',
			'title' => __( 'Tags Filter', 'testimonials-widget' ),
			'desc' => __( 'Comma separated tag slug-names. Ex: tag-a, another-tag', 'testimonials-widget' ),
			'validate' => 'slugs',
		);

		self::$settings['tags_all'] = array(
			'section' => 'selection',
			'title' => __( 'Require All Tags?', 'testimonials-widget' ),
			'desc' => __( 'Select only testimonials with all of the given tags', 'testimonials-widget' ),
			'type' => 'checkbox',
		);

		self::$settings['ids'] = array(
			'section' => 'selection',
			'title' => __( 'Include IDs Filter', 'testimonials-widget' ),
			'desc' => __( 'Comma separated testimonial IDs. Ex: 3,1,2', 'testimonials-widget' ),
			'validate' => 'ids',
		);

		self::$settings['exclude'] = array(
			'section' => 'selection',
			'title' => __( 'Exclude IDs Filter', 'testimonials-widget' ),
			'desc' => __( 'Comma separated testimonial IDs. Ex: 3,1,2', 'testimonials-widget' ),
			'validate' => 'ids',
		);

		self::$settings['limit'] = array(
			'section' => 'selection',
			'title' => __( 'Limit', 'testimonials-widget' ),
			'desc' => __( 'Number of testimonials to select per instance', 'testimonials-widget' ),
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
			'desc' => __( 'Ordering Options', 'testimonials-widget' ),
			'type' => 'expand_begin',
		);

		self::$settings['random'] = array(
			'section' => 'ordering',
			'title' => __( 'Random Order?', 'testimonials-widget' ),
			'desc' => __( 'If checked, ignores ORDER BY, ORDER BY meta_key, and ORDER BY Order. Widgets are random by default automatically', 'testimonials-widget' ),
			'type' => 'checkbox',
		);

		self::$settings['orderby'] = array(
			'section' => 'ordering',
			'title' => __( 'ORDER BY', 'testimonials-widget' ),
			'desc' => __( 'Used when "Random Order" is disabled', 'testimonials-widget' ),
			'type' => 'select',
			'choices' => array(
				'ID' => __( 'Testimonial ID', 'testimonials-widget' ),
				'author' => __( 'Author', 'testimonials-widget' ),
				'date' => __( 'Date', 'testimonials-widget' ),
				'menu_order' => __( 'Menu Order', 'testimonials-widget' ),
				'title' => __( 'Source', 'testimonials-widget' ),
				'none' => __( 'No order', 'testimonials-widget' ),
			),
			'std' => 'ID',
			'validate' => 'term',
		);

		self::$settings['meta_key'] = array(
			'section' => 'ordering',
			'title' => __( 'ORDER BY meta_key', 'testimonials-widget' ),
			'desc' => __( 'Used when "Random Order" is disabled and sorting by a testimonials meta key is needed. Overrides ORDER BY', 'testimonials-widget' ),
			'type' => 'select',
			'choices' => array(
				'' => __( 'None', 'testimonials-widget' ),
				'testimonials-widget-title' => __( 'Title', 'testimonials-widget' ),
				'testimonials-widget-email' => __( 'Email', 'testimonials-widget' ),
				'testimonials-widget-location' => __( 'Location', 'testimonials-widget' ),
				'testimonials-widget-company' => __( 'Company', 'testimonials-widget' ),
				'testimonials-widget-url' => __( 'URL', 'testimonials-widget' ),
			),
			'validate' => 'slug',
		);

		self::$settings['order'] = array(
			'section' => 'ordering',
			'title' => __( 'ORDER BY Order', 'testimonials-widget' ),
			'type' => 'select',
			'choices' => array(
				'DESC' => __( 'Descending', 'testimonials-widget' ),
				'ASC' => __( 'Ascending', 'testimonials-widget' ),
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
			'title' => __( 'Allow Comments?', 'testimonials-widget' ),
			'desc' => __( 'Only affects the Testimonials Widget post edit page. Your theme controls the front-end view.', 'testimonials-widget' ),
			'type' => 'checkbox',
			'widget' => 0,
		);

		$desc        = __( 'URL slug-name for <a href="%1s">testimonials archive</a> page.', 'testimonials-widget' );
		$has_archive = tw_get_option( 'has_archive', '' );
		$site_url    = site_url( '/' . $has_archive );

		self::$settings['has_archive'] = array(
			'section' => 'post_type',
			'title' => __( 'Archive Page URL', 'testimonials-widget' ),
			'desc' => sprintf( $desc, $site_url ),
			'std' => 'testimonials-archive',
			'validate' => 'sanitize_title',
			'widget' => 0,
		);

		$desc = __( 'URL slug-name for testimonial view pages. Shouldn\'t be the same as Archive Page URL.', 'testimonials-widget' );

		self::$settings['rewrite_slug'] = array(
			'section' => 'post_type',
			'title' => __( 'Testimonial Page URL', 'testimonials-widget' ),
			'desc' => $desc,
			'std' => 'testimonial',
			'validate' => 'sanitize_title',
			'widget' => 0,
		);

		// Reset
		self::$settings['reset_expand_begin'] = array(
			'section' => 'reset',
			'desc' => __( 'Compatiblity Options', 'testimonials-widget' ),
			'type' => 'expand_begin',
		);

		self::$settings['remove_hentry'] = array(
			'section' => 'reset',
			'title' => __( 'Remove `.hentry` CSS?', 'testimonials-widget' ),
			'desc' => __( 'Pre 2.6.4. Some themes use class `.hentry` in a manner that breaks Testimonials Widgets CSS', 'testimonials-widget' ),
			'type' => 'checkbox',
			'backwards' => array(
				'version' => '2.6.4',
				'std' => 1,
			),
		);

		self::$settings['use_quote_tag'] = array(
			'section' => 'reset',
			'title' => __( 'Use `&lt;q&gt;` tag?', 'testimonials-widget' ),
			'desc' => __( 'Pre 2.11.0. Not HTML5 compliant', 'testimonials-widget' ),
			'type' => 'checkbox',
			'backwards' => array(
				'version' => '2.11.0',
				'std' => 1,
			),
		);

		self::$settings['use_cpt_taxonomy'] = array(
			'section' => 'reset',
			'title' => __( 'Don\'t Use Default Taxonomies?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'desc' => __( 'If checked, use Testimonials Widget\'s own category and tag taxonomies instead', 'testimonials-widget' ),
			'widget' => 0,
		);

		$options = get_option( self::ID );
		if ( ! empty( $options ) ) {
			$serialized_options = serialize( $options );
			$_SESSION['export'] = $serialized_options;

			self::$settings['export'] = array(
				'section' => 'reset',
				'title' => __( 'Export Settings', 'testimonials-widget' ),
				'type' => 'readonly',
				'desc' => __( 'These are your current settings in a serialized format. Copy the contents to make a backup of your settings.', 'testimonials-widget' ),
				'std' => $serialized_options,
				'widget' => 0,
			);
		}

		self::$settings['import'] = array(
			'section' => 'reset',
			'title' => __( 'Import Settings', 'testimonials-widget' ),
			'type' => 'textarea',
			'desc' => __( 'Paste new serialized settings here to overwrite your current configuration.', 'testimonials-widget' ),
			'widget' => 0,
		);

		self::$settings['delete_data'] = array(
			'section' => 'reset',
			'title' => __( 'Remove Plugin Data on Deletion?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'class' => 'warning', // Custom class for CSS
			'desc' => __( 'Delete all Testimonials Widget data and options from database on plugin deletion', 'testimonials-widget' ),
			'widget' => 0,
		);

		self::$settings['reset_defaults'] = array(
			'section' => 'reset',
			'title' => __( 'Reset to Defaults?', 'testimonials-widget' ),
			'type' => 'checkbox',
			'class' => 'warning', // Custom class for CSS
			'desc' => __( 'Check this box to reset options to their defaults', 'testimonials-widget' ),
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


	public function admin_init() {
		$version       = tw_get_option( 'version' );
		self::$version = Testimonials_Widget::VERSION;
		self::$version = apply_filters( 'testimonials_widget_version', self::$version );

		if ( $version != self::$version )
			$this->initialize_settings();

		$this->register_settings();
	}


	public function admin_menu() {
		$admin_page = add_submenu_page( 'edit.php?post_type=' . Testimonials_Widget::PT, __( 'Testimonials Widget Settings', 'testimonials-widget' ), __( 'Settings', 'testimonials-widget' ), 'manage_options', self::ID, array( 'Testimonials_Widget_Settings', 'display_page' ) );

		add_action( 'admin_print_scripts-' . $admin_page, array( &$this, 'scripts' ) );
		add_action( 'admin_print_styles-' . $admin_page, array( &$this, 'styles' ) );
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

		add_settings_field( $id, $title, array( &$this, 'display_setting' ), self::ID, $section, $field_args );
	}


	public static function display_page() {
		echo '<div class="wrap">
			<div class="icon32" id="icon-options-general"></div>
			<h2>' . __( 'Testimonials Widget Settings', 'testimonials-widget' ) . '</h2>';

		echo '<form action="options.php" method="post">';

		settings_fields( self::ID );

		echo '<div id="' . self::ID . '">
			<ul>';

		foreach ( self::$sections as $section_slug => $section )
			echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';

		echo '</ul>';

		self::do_settings_sections( self::ID );

		echo '
			<p class="submit"><input name="Submit" type="submit" class="button-primary" value="' . __( 'Save Changes', 'testimonials-widget' ) . '" /></p>
			</form>
		</div>
		';

		echo '
			<p>When ready, <a href="'.get_admin_url().'edit.php?post_type=testimonials-widget">view</a>
			or <a href="'.get_admin_url().'post-new.php?post_type=testimonials-widget">add</a> testimonials.</p>

			<p>If you like this plugin, please <a href="http://aihr.us/about-aihrus/donate/" title="Donate for Good Karma"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" alt="Donate for Good Karma" /></a> or <a href="http://aihr.us/testimonials-widget-premium/" title="purchase Testimonials Widget Premium">purchase Testimonials Widget Premium</a> to help fund further development and <a href="http://wordpress.org/support/plugin/testimonials-widget" title="Support forums">support</a>.</p>
			';

		$text      = __( 'Copyright &copy;%1$s %2$s.', 'testimonials-widget' );
		$link      = '<a href="http://aihr.us">Aihrus</a>';
		$copyright = '<p class="copyright">' . sprintf( $text, date( 'Y' ), $link ) . '</p>';
		echo $copyright;

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


	public function display_section() {
		// code
	}


	public function display_about_section() {

		echo     <<<EOD
			<div id="about" style="width: 70%; min-height: 225px;">
				<p><img class="alignright size-medium" title="Michael in Red Square, Moscow, Russia" src="/wp-content/plugins/testimonials-widget/media/michael-cannon-red-square-300x2251.jpg" alt="Michael in Red Square, Moscow, Russia" width="300" height="225" /><a href="http://wordpress.org/extend/plugins/testimonials-widget/">Testimonials Widget</a> is by <a href="http://aihr.us/about-aihrus/michael-cannon-resume/">Michael Cannon</a>. He's <a title="Lot's of stuff about Peichi Liu…" href="http://peimic.com/t/peichi-liu/">Peichi’s</a> smiling man, an adventurous <a title="Water rat" href="http://www.chinesehoroscope.org/chinese_zodiac/rat/" target="_blank">water-rat</a>, <a title="Axelerant – Open Source. Engineered." href="http://axelerant.com/who-we-are">chief people officer</a>, <a title="Aihrus – website support made easy since 1999" href="http://aihr.us/about-aihrus/">chief technology officer</a>, <a title="Road biker, cyclist, biking; whatever you call, I love to ride" href="http://peimic.com/c/biking/">cyclist</a>, <a title="Michael's poetic like literary ramblings" href="http://peimic.com/t/poetry/">poet</a>, <a title="World Wide Opportunities on Organic Farms" href="http://peimic.com/t/WWOOF/">WWOOF’er</a> and <a title="My traveled to country list, is more than my age." href="http://peimic.com/c/travel/">world traveler</a>.</p>
			</div>
EOD;

	}


	public static function display_setting( $args = array(), $do_echo = true, $input = null ) {
		$content = '';

		extract( $args );

		if ( is_null( $input ) ) {
			$options = get_option( self::ID );
		} else {
			$options      = array();
			$options[$id] = $input;
		}

		if ( ! isset( $options[$id] ) && $type != 'checkbox' ) {
			$options[$id] = $std;
		} elseif ( ! isset( $options[$id] ) ) {
			$options[$id] = 0;
		}

		$field_class = '';
		if ( ! empty( $class ) ) {
			$field_class = ' ' . $class;
		}

		switch ( $type ) {
		case 'checkbox':
			$content .= '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="' . self::ID . '[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> ';

			if ( ! empty( $desc ) )
				$content .= '<label for="' . $id . '"><span class="description">' . $desc . '</span></label>';

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
			$content .= '<input type="hidden" id="' . $id . '" name="' . self::ID . '[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';

			break;

		case 'password':
			$content .= '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="' . self::ID . '[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			break;

		case 'radio':
			$i             = 1;
			$count_choices = count( $choices );
			foreach ( $choices as $value => $label ) {
				$content .= '<input class="radio' . $field_class . '" type="radio" name="' . self::ID . '[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';

				if ( $i < $count_choices )
					$content .= '<br />';

				$i++;
			}

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			break;

		case 'readonly':
			$content .= '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="' . self::ID . '[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" readonly="readonly" />';

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			break;

		case 'select':
			$content .= '<select class="select' . $field_class . '" name="' . self::ID . '[' . $id . ']">';

			foreach ( $choices as $value => $label )
				$content .= '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';

			$content .= '</select>';

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			break;

		case 'text':
			$content .= '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="' . self::ID . '[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			break;

		case 'textarea':
			$content .= '<textarea class="' . $field_class . '" id="' . $id . '" name="' . self::ID . '[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';

			if ( ! empty( $desc ) )
				$content .= '<br /><span class="description">' . $desc . '</span>';

			break;

		default:
			break;
		}

		if ( $do_echo ) {
			echo $content;
		} else {
			return $content;
		}
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
		register_setting( self::ID, self::ID, array( &$this, 'validate_settings' ) );

		foreach ( self::$sections as $slug => $title ) {
			if ( $slug == 'about' )
				add_settings_section( $slug, $title, array( &$this, 'display_about_section' ), self::ID );
			else
				add_settings_section( $slug, $title, array( &$this, 'display_section' ), self::ID );
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
		wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
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
				if ( 'checkbox' != $type ) {
					$input[ $id ] = $default;
				} else {
					$input[ $id ] = 0;
				}
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

		// same has_archive and rewrite_slug causes problems
		if ( $input['has_archive'] == $input['rewrite_slug'] ) {
			$input['rewrite_slug'] = $defaults['rewrite_slug'];
		}

		// did URL slugs change?
		$has_archive  = tw_get_option( 'has_archive' );
		$rewrite_slug = tw_get_option( 'rewrite_slug' );
		if ( $has_archive != $input['has_archive'] || $rewrite_slug != $input['rewrite_slug'] ) {
			flush_rewrite_rules();
		}

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


	public static function validators( $validate, $id, &$input, $default, &$errors ) {
		switch ( $validate ) {
		case 'absint':
		case 'intval':
			if ( '' != $input[ $id ] )
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
			if ( empty( $input[ $id ] ) ) {
				$errors[ $id ] = __( 'Required', 'testimonials-widget' );
			}
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

		default:
			$input[ $id ] = $validate( $input[ $id ] );
			break;
		}
	}


	public static function validate_ids( $input, $default ) {
		if ( preg_match( '#^\d+(,\s?\d+)*$#', $input['exclude'] ) )
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


	/**
	 * allows for true, 'true', 1, 'yes' to be true
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

	if ( ! is_array( $options ) ) {
		$options = array();
	}

	$options[$option] = $value;
	update_option( Testimonials_Widget_Settings::ID, $options );
}


?>
