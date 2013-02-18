<?php

/**
 * Testimonials Widget settings class
 *
 * @ref http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-1/
 * @since 1.0
 */
class TW_Settings {
	
	private $sections			= null;
	private $reset				= null;
	private $settings			= null;
	
	/**
	 * Construct
	 *
	 * @since 1.0
	 */
	public function __construct() {
		
		// This will keep track of the checkbox options for the validate_settings function.
		$this->reset					= array();
		$this->settings					= array();
		$this->get_settings();
		
		$this->sections['general']      = __( 'General' , 'testimonials-widget');
		$this->sections['testing']		= __( 'Testing' , 'testimonials-widget');
		$this->sections['reset']        = __( 'Reset' , 'testimonials-widget');
		$this->sections['about']        = __( 'About Testimonials Widget' , 'testimonials-widget');
		
		add_action( 'admin_menu', array( &$this, 'add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );

		load_plugin_textdomain( 'testimonials-widget', false, '/testimonials-widget/languages/' );
		
		if ( ! get_option( 'tw_options' ) )
			$this->initialize_settings();
	}
	
	/**
	 * Add options page
	 *
	 * @since 1.0
	 */
	public function add_pages() {
		$admin_page = add_options_page( __( 'Testimonials Widget Settings' , 'testimonials-widget'), __( 'Testimonials' , 'testimonials-widget'), 'manage_options', 'tw-options', array( &$this, 'display_page' ) );
		
		add_action( 'admin_print_scripts-' . $admin_page, array( &$this, 'scripts' ) );
		add_action( 'admin_print_styles-' . $admin_page, array( &$this, 'styles' ) );

		add_screen_meta_link(
        	'tw-importer-link',
			__('Testimonials', 'testimonials-widget'),
			admin_url('edit.php?post_type=testimonials-widget'),
			$admin_page,
			array('style' => 'font-weight: bold;')
		);
	}
	
	/**
	 * Create settings field
	 *
	 * @since 1.0
	 */
	public function create_setting( $args = array() ) {
		
		$defaults = array(
			'id'      => 'default_field',
			'title'   => __( 'Default Field' , 'testimonials-widget'),
			'desc'    => __( 'This is a default description.' , 'testimonials-widget'),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general',
			'choices' => array(),
			'class'   => ''
		);
			
		extract( wp_parse_args( $args, $defaults ) );
		
		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'std'       => $std,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class
		);
		
		$this->reset[$id] = $std;
		
		add_settings_field( $id, $title, array( $this, 'display_setting' ), 'tw-options', $section, $field_args );
	}
	
	/**
	 * Display options page
	 *
	 * @since 1.0
	 */
	public function display_page() {
		
		echo '<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2>' . __( 'Testimonials Widget Settings' , 'testimonials-widget') . '</h2>';
	
		echo '<form action="options.php" method="post">';
	
		settings_fields( 'tw_options' );
		echo '<div class="ui-tabs">
			<ul class="ui-tabs-nav">';
		
		foreach ( $this->sections as $section_slug => $section )
			echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';
		
		echo '</ul>';
		do_settings_sections( $_GET['page'] );
		
		echo '</div>
		<p class="submit"><input name="Submit" type="submit" class="button-primary" value="' . __( 'Save Changes' , 'testimonials-widget') . '" /></p>

		<p>When ready, <a href="'.get_admin_url().'edit.php?post_type=testimonials-widget">'.__('view testimonials', 'testimonials-widget').'</a>
		or <a href="'.get_admin_url().'post-new.php?post_type=testimonials-widget">'.__('add a testimonial', 'testimonials-widget').'</a>.
		
	</form>';

		$copyright				= '<div class="copyright">Copyright %s <a href="http://aihr.us">Aihr.us.</a></div>';
		$copyright				= sprintf( $copyright, date( 'Y' ) );
		echo					<<<EOD
				$copyright
EOD;
	
	echo '<script type="text/javascript">
		jQuery(document).ready(function($) {
			var sections = [];';
			
			foreach ( $this->sections as $section_slug => $section )
				echo "sections['$section'] = '$section_slug';";
			
			echo 'var wrapped = $(".wrap h3").wrap("<div class=\"ui-tabs-panel\">");
			wrapped.each(function() {
				$(this).parent().append($(this).parent().nextUntil("div.ui-tabs-panel"));
			});
			$(".ui-tabs-panel").each(function(index) {
				$(this).attr("id", sections[$(this).children("h3").text()]);
				if (index > 0)
					$(this).addClass("ui-tabs-hide");
			});
			$(".ui-tabs").tabs({
				fx: { opacity: "toggle", duration: "fast" }
			});
			
			$("input[type=text], textarea").each(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
					$(this).css("color", "#999");
			});
			
			$("input[type=text], textarea").focus(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
					$(this).val("");
					$(this).css("color", "#000");
				}
			}).blur(function() {
				if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
					$(this).val($(this).attr("placeholder"));
					$(this).css("color", "#999");
				}
			});
			
			$(".wrap h3, .wrap table").show();
			
			// This will make the "warning" checkbox class really stand out when checked.
			// I use it here for the Reset checkbox.
			$(".warning").change(function() {
				if ($(this).is(":checked"))
					$(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");
				else
					$(this).parent().css("background", "none").css("color", "inherit").css("fontWeight", "normal");
			});
			
			// Browser compatibility
			if ($.browser.mozilla) 
			         $("form").attr("autocomplete", "off");
		});
	</script>
</div>';
		
	}
	
	/**
	 * Description for section
	 *
	 * @since 1.0
	 */
	public function display_section() {
		// code
	}
	
	/**
	 * Description for About section
	 *
	 * @since 1.0
	 */
	public function display_about_section() {
		
		echo					<<<EOD
			<div style="width: 50%;">
				<p><img class="alignright size-medium" title="Michael in Red Square, Moscow, Russia" src="/wp-content/plugins/testimonials-widget/media/michael-cannon-red-square-300x2251.jpg" alt="Michael in Red Square, Moscow, Russia" width="300" height="225" /><a href="http://wordpress.org/extend/plugins/testimonials-widget/">Testimonials Widget</a> is by <a href="http://aihr.us/about-aihrus/michael-cannon-resume/">Michael Cannon</a>.</p>
				<p>Hello, I’m Michael Cannon, <a title="Lot's of stuff about Peichi Liu..." href="http://peimic.com/t/peichi-liu/">Peichi’s</a> smiling man, an&nbsp;adventurous <a title="Water rat" href="http://www.chinesehoroscope.org/chinese_zodiac/rat/" target="_blank">water-rat</a>,&nbsp;<a title="Aihrus –&nbsp;website support made easy since 1999" href="http://aihrus.localhost/">chief technology officer</a>,&nbsp;<a title="Road biker, cyclist, biking; whatever you call, I love to ride" href="http://peimic.com/c/biking/">cyclist</a>,&nbsp;<a title="Michael's poetic like literary ramblings" href="http://peimic.com/t/poetry/">poet</a>,&nbsp;<a title="World Wide Opportunities on Organic Farms" href="http://peimic.com/t/WWOOF/">WWOOF’er</a>&nbsp;and&nbsp;<a title="My traveled to country list, is more than my age." href="http://peimic.com/c/travel/">world traveler</a>.</p>
				<p>If you like this plugin, please donate.</p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_donations"><input type="hidden" name="business" value="mc@aihr.us"><input type="hidden" name="return" value="http://aihrus.localhost/about-aihrus/donate/thank-you/"><input type="hidden" name="item_name" value="Sponsor software development"><input type="hidden" name="currency_code" value="USD"><input type="image" alt="PayPal - The safer, easier way to pay online." name="submit" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif"></form>
			</div>
EOD;
		
	}
	
	/**
	 * HTML output for text field
	 *
	 * @since 1.0
	 */
	public function display_setting( $args = array() ) {
		
		extract( $args );
		
		$options = get_option( 'tw_options' );
		
		if ( ! isset( $options[$id] ) && $type != 'checkbox' )
			$options[$id] = $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id] = 0;
		
		$field_class = '';
		if ( $class != '' )
			$field_class = ' ' . $class;
		
		switch ( $type ) {
			
			case 'heading':
				echo '</td></tr><tr valign="top"><td colspan="2"><h4>' . $desc . '</h4>';
				break;
			
			case 'checkbox':
				
				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="tw_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> ';
				
				if ( $desc != '' )
					echo '<label for="' . $id . '"><span class="description">' . $desc . '</span></label>';

				break;
			
			case 'select':
				echo '<select class="select' . $field_class . '" name="tw_options[' . $id . ']">';
				
				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';
				
				echo '</select>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="tw_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="tw_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="tw_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'text':
			default:
		 		echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="tw_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
		 		
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		
		 		break;
		 	
		}
		
	}
	
	/**
	 * Settings and defaults
	 * 
	 * @since 1.0
	 */
	public function get_settings() {
		
		/* General Settings
		===========================================*/
		
		
		/* Debug
		===========================================*/
		$this->settings['debug_mode'] = array(
			'section' => 'testing',
			'title'   => __( 'Debug Mode?' , 'testimonials-widget'),
			'desc'	  => __( 'Not implemented yet', 'testimonials-widget' ),
			'type'    => 'checkbox',
			'std'     => 0
		);
		
		/* Reset
		===========================================*/
		
		$this->settings['reset_plugin'] = array(
			'section' => 'reset',
			'title'   => __( 'Reset plugin' , 'testimonials-widget'),
			'type'    => 'checkbox',
			'std'     => 0,
			'class'   => 'warning', // Custom class for CSS
			'desc'    => __( 'Check this box and click "Save Changes" below to reset plugin options to their defaults.' , 'testimonials-widget')
		);

		// Here for reference
		if ( true ) {
		$this->settings['example_text'] = array(
			'title'   => __( 'Example Text Input' , 'testimonials-widget'),
			'desc'    => __( 'This is a description for the text input.' , 'testimonials-widget'),
			'std'     => 'Default value',
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['example_textarea'] = array(
			'title'   => __( 'Example Textarea Input' , 'testimonials-widget'),
			'desc'    => __( 'This is a description for the textarea input.' , 'testimonials-widget'),
			'std'     => 'Default value',
			'type'    => 'textarea',
			'section' => 'general'
		);
		
		$this->settings['example_checkbox'] = array(
			'section' => 'general',
			'title'   => __( 'Example Checkbox' , 'testimonials-widget'),
			'desc'    => __( 'This is a description for the checkbox.' , 'testimonials-widget'),
			'type'    => 'checkbox',
			'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);
		
		$this->settings['example_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'    => 'Example Heading',
			'type'    => 'heading'
		);
		
		$this->settings['example_radio'] = array(
			'section' => 'general',
			'title'   => __( 'Example Radio' , 'testimonials-widget'),
			'desc'    => __( 'This is a description for the radio buttons.' , 'testimonials-widget'),
			'type'    => 'radio',
			'std'     => '',
			'choices' => array(
				'choice1' => 'Choice 1',
				'choice2' => 'Choice 2',
				'choice3' => 'Choice 3'
			)
		);
		
		$this->settings['example_select'] = array(
			'section' => 'general',
			'title'   => __( 'Example Select' , 'testimonials-widget'),
			'desc'    => __( 'This is a description for the drop-down.' , 'testimonials-widget'),
			'type'    => 'select',
			'std'     => '',
			'choices' => array(
				'choice1' => 'Other Choice 1',
				'choice2' => 'Other Choice 2',
				'choice3' => 'Other Choice 3'
			)
		);
		}
		
	}
	
	/**
	 * Initialize settings to their default values
	 * 
	 * @since 1.0
	 */
	public function initialize_settings() {
		
		$default_settings = array();
		foreach ( $this->settings as $id => $setting ) {
			if ( $setting['type'] != 'heading' )
				$default_settings[$id] = $setting['std'];
		}
		
		update_option( 'tw_options', $default_settings );
		
	}
	
	/**
	* Register settings
	*
	* @since 1.0
	*/
	public function register_settings() {
		
		register_setting( 'tw_options', 'tw_options', array ( &$this, 'validate_settings' ) );
		
		foreach ( $this->sections as $slug => $title ) {
			if ( $slug == 'about' )
				add_settings_section( $slug, $title, array( &$this, 'display_about_section' ), 'tw-options' );
			else
				add_settings_section( $slug, $title, array( &$this, 'display_section' ), 'tw-options' );
		}
		
		$this->get_settings();
		
		foreach ( $this->settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}
		
	}
	
	/**
	* jQuery Tabs
	*
	* @since 1.0
	*/
	public function scripts() {
		
		wp_print_scripts( 'jquery-ui-tabs' );
		
	}
	
	/**
	* Styling for the plugin options page
	*
	* @since 1.0
	*/
	public function styles() {
		
		wp_register_style( 'tw-admin', plugins_url( 'settings.css', __FILE__ ) );
		wp_enqueue_style( 'tw-admin' );
		
	}
	
	/**
	* Validate settings
	*
	* @since 1.0
	*/
	public function validate_settings( $input ) {
		
		if ( '' != $input['posts_to_import'] ) {
			$posts_to_import		= $input['posts_to_import'];
			$posts_to_import		= preg_replace( '#\s+#', '', $posts_to_import);

			$input['posts_to_import']	= $posts_to_import;
		}
		
		if ( '' != $input['skip_importing_post_ids'] ) {
			$skip_importing_post_ids		= $input['skip_importing_post_ids'];
			$skip_importing_post_ids		= preg_replace( '#\s+#', '', $skip_importing_post_ids);

			$input['skip_importing_post_ids']	= $skip_importing_post_ids;
		}

		if ( $input['reset_plugin'] ) {
			foreach ( $this->reset as $id => $std ) {
				$input[$id]	= $std;
			}
			
			unset( $input['reset_plugin'] );
		}

		return $input;
		
	}
	
}

$TW_Settings					= new TW_Settings();

function tw_get_options( $option, $default = false ) {
	$options					= get_option( 'tw_options', $default );

	if ( isset( $options[$option] ) )
		return $options[$option];
	else
		return false;
}

function update_tw_options( $option, $value = null ) {
	$options					= get_option( 'tw_options' );

	if ( ! is_array( $options ) ) {
		$options				= array();
	}

	$options[$option]			= $value;
	update_option( 'tw_options', $options );
}
?>