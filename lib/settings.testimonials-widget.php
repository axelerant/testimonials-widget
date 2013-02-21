<?php

/**
 * Testimonials Widget settings class
 *
 * @ref http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-1/
 */
class Testimonials_Widget_Settings {
	const id					= 'testimonialswidget_settings';

	private static $reset		= array();
	private static $sections	= array();
	private static $settings	= array();

	
	public function __construct() {
		$this->load_sections();
		$this->load_settings();
		
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		load_plugin_textdomain( 'testimonials-widget', false, '/testimonials-widget/languages/' );
	}


	public function admin_init() {
		if ( ! get_option( self::id ) )
			$this->initialize_settings();

		$this->register_settings();
	}

	
	public function admin_menu() {
		$admin_page				= add_submenu_page( 'edit.php?post_type=' . Testimonials_Widget::pt, __( 'Testimonials Widget Settings', 'testimonials-widget' ), __( 'Settings', 'testimonials-widget' ), 'manage_options', self::id, array( 'Testimonials_Widget_Settings', 'display_page' ) );
		
		add_action( 'admin_print_scripts-' . $admin_page, array( &$this, 'scripts' ) );
		add_action( 'admin_print_styles-' . $admin_page, array( &$this, 'styles' ) );
	}
	

	public function create_setting( $args = array() ) {
		$defaults = array(
			'id'      => 'default_field',
			'title'   => __( 'Default Field' , 'testimonials-widget'),
			'desc'    => '',
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
		
		self::$reset[$id] = $std;

		add_settings_field( $id, $title, array( $this, 'display_setting' ), self::id, $section, $field_args );
	}
	

	public function display_page() {
		echo '<div class="wrap">
			<div class="icon32" id="icon-options-general"></div>
			<h2>' . __( 'Testimonials Widget Settings' , 'testimonials-widget') . '</h2>';

		echo '<form action="options.php" method="post">';

		settings_fields( self::id );

		echo '<div class="ui-tabs">
			<ul class="ui-tabs-nav">';

		foreach ( self::$sections as $section_slug => $section )
			echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';

		echo '</ul>';

		do_settings_sections( self::id );

		echo '</div>';

		echo '
			<p class="submit"><input name="Submit" type="submit" class="button-primary" value="' . __( 'Save Changes' , 'testimonials-widget') . '" /></p>

			<p>When ready, <a href="'.get_admin_url().'edit.php?post_type=testimonials-widget">view</a>
			or <a href="'.get_admin_url().'post-new.php?post_type=testimonials-widget">add</a> testimonials.</p>

			</form>';

		$text					= __( 'Copyright &copy;%1$s %2$s.' );
		$link					= '<a href="http://aihr.us">Aihrus</a>';
		$copyright				= '<div class="copyright">' . sprintf( $text, date( 'Y' ), $link ) . '</div>';
		echo $copyright;

		self::section_scripts();

		echo '</div>';
	}


	public static function section_scripts() {
		echo '<script type="text/javascript">
			jQuery(document).ready(function($) {
				var sections = [];';

				foreach ( self::$sections as $section_slug => $section )
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
	</script>';
	}
	

	public function display_section() {
		// code
	}
	

	public function display_about_section() {
		
		echo					<<<EOD
			<div style="width: 50%;">
				<p><img class="alignright size-medium" title="Michael in Red Square, Moscow, Russia" src="/wp-content/plugins/testimonials-widget/media/michael-cannon-red-square-300x2251.jpg" alt="Michael in Red Square, Moscow, Russia" width="300" height="225" /><a href="http://wordpress.org/extend/plugins/testimonials-widget/">Testimonials Widget</a> is by <a href="http://aihr.us/about-aihrus/michael-cannon-resume/">Michael Cannon</a>.</p>
				<p>He's, <a title="Lot's of stuff about Peichi Liu..." href="http://peimic.com/t/peichi-liu/">Peichi’s</a> smiling man, an&nbsp;adventurous <a title="Water rat" href="http://www.chinesehoroscope.org/chinese_zodiac/rat/" target="_blank">water-rat</a>,&nbsp;<a title="Aihrus –&nbsp;website support made easy since 1999" href="http://aihrus.localhost/">chief technology officer</a>,&nbsp;<a title="Road biker, cyclist, biking; whatever you call, I love to ride" href="http://peimic.com/c/biking/">cyclist</a>,&nbsp;<a title="Michael's poetic like literary ramblings" href="http://peimic.com/t/poetry/">poet</a>,&nbsp;<a title="World Wide Opportunities on Organic Farms" href="http://peimic.com/t/WWOOF/">WWOOF’er</a>&nbsp;and&nbsp;<a title="My traveled to country list, is more than my age." href="http://peimic.com/c/travel/">world traveler</a>.</p>
				<p>If you like this plugin, please donate.</p>
			</div>
EOD;
		
	}
	

	public function display_setting( $args = array() ) {
		extract( $args );
		
		$options				= get_option( self::id );
		
		if ( ! isset( $options[$id] ) && $type != 'checkbox' )
			$options[$id]		= $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id]		= 0;
		
		$field_class			= '';
		if ( ! empty( $class ) )
			$field_class		= ' ' . $class;
		
		switch ( $type ) {
			
			case 'heading':
				echo '</td></tr><tr valign="top"><td colspan="2"><h4>' . $desc . '</h4>';
				break;
			
			case 'checkbox':
				
				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="' . self::id . '[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> ';
				
				if ( ! empty( $desc ) )
					echo '<label for="' . $id . '"><span class="description">' . $desc . '</span></label>';

				break;
			
			case 'select':
				echo '<select class="select' . $field_class . '" name="' . self::id . '[' . $id . ']">';
				
				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';
				
				echo '</select>';
				
				if ( ! empty( $desc ) )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="' . self::id . '[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}
				
				if ( ! empty( $desc ) )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="' . self::id . '[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';
				
				if ( ! empty( $desc ) )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="' . self::id . '[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';
				
				if ( ! empty( $desc ) )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'text':
			default:
		 		echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="' . self::id . '[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
		 		
				if ( ! empty( $desc ) )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		
		 		break;
		 	
		}
	}
	

	public function load_sections() {
		self::$sections['general']      = __( 'General' , 'testimonials-widget');
		self::$sections['testing']		= __( 'Testing' , 'testimonials-widget');
		self::$sections['reset']        = __( 'Reset' , 'testimonials-widget');
		self::$sections['about']        = __( 'About Testimonials Widget' , 'testimonials-widget');
	}

	
	public function load_settings() {
		/* General Settings
		===========================================*/
		
		
		/* Debug
		===========================================*/
		self::$settings['debug_mode'] = array(
			'section' => 'testing',
			'title'   => __( 'Debug Mode?' , 'testimonials-widget'),
			'desc'	  => __( 'Not implemented yet', 'testimonials-widget' ),
			'type'    => 'checkbox',
			'std'     => 0
		);
		
		/* Reset
		===========================================*/
		self::$settings['reset_plugin'] = array(
			'section' => 'reset',
			'title'   => __( 'Reset plugin' , 'testimonials-widget'),
			'type'    => 'checkbox',
			'std'     => 0,
			'class'   => 'warning', // Custom class for CSS
			'desc'    => __( 'Check this box and click "Save Changes" below to reset plugin options to their defaults.' , 'testimonials-widget')
		);

		// Here for reference
		if ( false ) {
		self::$settings['example_text'] = array(
			'title'   => __( 'Example Text Input' , 'testimonials-widget'),
			'desc'    => __( 'This is a description for the text input.' , 'testimonials-widget'),
			'std'     => 'Default value',
			'type'    => 'text',
			'section' => 'general'
		);
		
		self::$settings['example_textarea'] = array(
			'title'   => __( 'Example Textarea Input' , 'testimonials-widget'),
			'desc'    => __( 'This is a description for the textarea input.' , 'testimonials-widget'),
			'std'     => 'Default value',
			'type'    => 'textarea',
			'section' => 'general'
		);
		
		self::$settings['example_checkbox'] = array(
			'section' => 'general',
			'title'   => __( 'Example Checkbox' , 'testimonials-widget'),
			'desc'    => __( 'This is a description for the checkbox.' , 'testimonials-widget'),
			'type'    => 'checkbox',
			'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);
		
		self::$settings['example_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'    => 'Example Heading',
			'type'    => 'heading'
		);
		
		self::$settings['example_radio'] = array(
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
		
		self::$settings['example_select'] = array(
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

	
	public function initialize_settings() {
		$default_settings		= array();

		foreach ( self::$settings as $id => $setting ) {
			if ( $setting['type'] != 'heading' )
				$default_settings[$id]	= $setting['std'];
		}
		
		update_option( self::id, $default_settings );
	}
	

	public function register_settings() {
		register_setting( self::id, self::id, array ( &$this, 'validate_settings' ) );
		
		foreach ( self::$sections as $slug => $title ) {
			if ( $slug == 'about' )
				add_settings_section( $slug, $title, array( &$this, 'display_about_section' ), self::id );
			else
				add_settings_section( $slug, $title, array( &$this, 'display_section' ), self::id );
		}
		
		foreach ( self::$settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}
	}
	

	public function scripts() {
		wp_print_scripts( 'jquery-ui-tabs' );
	}
	

	public function styles() {
		wp_register_style( __CLASS__ . '-admin', plugins_url( 'settings.css', __FILE__ ) );
		wp_enqueue_style( __CLASS__ . '-admin' );
	}
	

	public function validate_settings( $input ) {
		if ( ! empty( $input['reset_plugin'] ) ) {
			foreach ( self::$reset as $id => $std ) {
				$input[$id]		= $std;
			}
			
			unset( $input['reset_plugin'] );
		}

		return $input;
	}
}


$Testimonials_Widget_Settings	= new Testimonials_Widget_Settings();


function tw_get_option( $option, $default = false ) {
	$options					= get_option( Testimonials_Widget_Settings::id, $default );

	if ( isset( $options[$option] ) )
		return $options[$option];
	else
		return false;
}


function tw_set_option( $option, $value = null ) {
	$options					= get_option( Testimonials_Widget_Settings::id );

	if ( ! is_array( $options ) ) {
		$options				= array();
	}

	$options[$option]			= $value;
	update_option( Testimonials_Widget_Settings::id, $options );
}

?>