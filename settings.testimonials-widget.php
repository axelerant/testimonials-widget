<?php

/**
 * Flickr Shortcode Importer settings class
 *
 * @ref http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-1/
 * @since 1.0
 */
class FSI_Settings {
	
	private $sections;
	private $reset;
	private $settings;
	
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
		
		$this->sections['general']      = __( 'Import Settings' , 'flickr-shortcode-importer');
		$this->sections['api']   		= __( 'Flickr API' , 'flickr-shortcode-importer');
		$this->sections['selection']	= __( 'Posts Selection' , 'flickr-shortcode-importer');
		$this->sections['testing']		= __( 'Testing Options' , 'flickr-shortcode-importer');
		$this->sections['posts']		= __( 'Post Options' , 'flickr-shortcode-importer');
		$this->sections['reset']        = __( 'Reset/Restore' , 'flickr-shortcode-importer');
		$this->sections['about']        = __( 'About Flickr Shortcode Importer' , 'flickr-shortcode-importer');
		
		add_action( 'admin_menu', array( &$this, 'add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );

		load_plugin_textdomain( 'flickr-shortcode-importer', false, '/flickr-shortcode-importer/languages/' );
		
		if ( ! get_option( 'fsi_options' ) )
			$this->initialize_settings();
		
	}
	
	/**
	 * Add options page
	 *
	 * @since 1.0
	 */
	public function add_pages() {
		
		$admin_page = add_options_page( __( 'Flickr Shortcode Importer Settings' , 'flickr-shortcode-importer'), __( '[flickr] Importer' , 'flickr-shortcode-importer'), 'manage_options', 'fsi-options', array( &$this, 'display_page' ) );
		
		add_action( 'admin_print_scripts-' . $admin_page, array( &$this, 'scripts' ) );
		add_action( 'admin_print_styles-' . $admin_page, array( &$this, 'styles' ) );

		add_screen_meta_link(
        	'fsi-importer-link',
			__('[Flickr] Importer', 'flickr-shortcode-importer'),
			admin_url('tools.php?page=flickr-shortcode-importer'),
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
			'title'   => __( 'Default Field' , 'flickr-shortcode-importer'),
			'desc'    => __( 'This is a default description.' , 'flickr-shortcode-importer'),
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
		
		add_settings_field( $id, $title, array( $this, 'display_setting' ), 'fsi-options', $section, $field_args );
	}
	
	/**
	 * Display options page
	 *
	 * @since 1.0
	 */
	public function display_page() {
		
		echo '<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2>' . __( 'Flickr Shortcode Importer Settings' , 'flickr-shortcode-importer') . '</h2>';
	
		echo '<form action="options.php" method="post">';
	
		settings_fields( 'fsi_options' );
		echo '<div class="ui-tabs">
			<ul class="ui-tabs-nav">';
		
		foreach ( $this->sections as $section_slug => $section )
			echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';
		
		echo '</ul>';
		do_settings_sections( $_GET['page'] );
		
		echo '</div>
		<p class="submit"><input name="Submit" type="submit" class="button-primary" value="' . __( 'Save Changes' , 'flickr-shortcode-importer') . '" /></p>

		<p>When ready, <a href="'.get_admin_url().'tools.php?page=flickr-shortcode-importer">'.__('begin [flickr] importing', 'flickr-shortcode-importer').'</a>
		
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
				<p><img class="alignright size-medium" title="Michael in Red Square, Moscow, Russia" src="/wp-content/plugins/flickr-shortcode-importer/media/michael-cannon-red-square-300x2251.jpg" alt="Michael in Red Square, Moscow, Russia" width="300" height="225" /><a href="http://wordpress.org/extend/plugins/flickr-shortcode-importer/">Flickr Shortcode Importer</a> is by <a href="http://aihr.us/about-aihrus/michael-cannon-resume/">Michael Cannon</a>.</p>
				<p>He's <a title="Lot's of stuff about Peichi Liu..." href="http://peimic.com/t/peichi-liu/">Peichi’s</a> smiling man, an adventurous <a title="Water rat" href="http://www.chinesezodiachoroscope.com/facebook/index1.php?user_id=690714457" target="_blank">water-rat</a>, <a title="Michael's poetic like literary ramblings" href="http://peimic.com/t/poetry/">poet</a>, <a title="Road biker, cyclist, biking; whatever you call, I love to ride" href="http://peimic.com/c/biking/">road biker</a>, <a title="My traveled to country list, is more than my age." href="http://peimic.com/c/travel/">world traveler</a>, <a title="World Wide Opportunities on Organic Farms" href="http://peimic.com/t/WWOOF/">WWOOF’er</a> and is the <a title="The TYPO3 Vagabond" href="http://aihr.us/c/featured/">TYPO3 Vagabond</a>.</p>
				<p>If you like this plugin, <a href="http://aihr.us/about-aihrus/donate/">please donate</a>.</p>
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
		
		$options = get_option( 'fsi_options' );
		
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
				
				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="fsi_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> ';
				
				if ( $desc != '' )
					echo '<label for="' . $id . '"><span class="description">' . $desc . '</span></label>';

				break;
			
			case 'select':
				echo '<select class="select' . $field_class . '" name="fsi_options[' . $id . ']">';
				
				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';
				
				echo '</select>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="fsi_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="fsi_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="fsi_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'text':
			default:
		 		echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="fsi_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
		 		
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
		
		$this->settings['skip_videos'] = array(
			'section' => 'general',
			'title'   => __( 'Skip Importing Videos?' , 'flickr-shortcode-importer'),
			'desc'    => __( 'Importing videos from Flickr often fails. Shortcode is still converted to object/embed linking to Flickr.' , 'flickr-shortcode-importer'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		
		$this->settings['import_flickr_sourced_tags'] = array(
			'section' => 'general',
			'title'   => __( 'Import Flickr-sourced A/IMG tags?' , 'flickr-shortcode-importer'),
			'desc'    => __( 'Converts Flickr-sourced A/IMG tags to [flickr] and then proceeds with import.' , 'flickr-shortcode-importer'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		
		$this->settings['set_featured_image'] = array(
			'section' => 'general',
			'title'   => __( 'Set Featured Image?' , 'flickr-shortcode-importer'),
			'desc'    => __( 'Set the first [flickr] or [flickrset] image found as the Featured Image. Will not replace the current Featured Image of a post.' , 'flickr-shortcode-importer'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		
		$this->settings['force_set_featured_image'] = array(
			'section' => 'general',
			'title'   => __( 'Force Set Featured Image?' , 'flickr-shortcode-importer'),
			'desc'    => __( 'Set the Featured Image even if one already exists for a post.', 'flickr-shortcode-importer'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		
		$this->settings['remove_first_flickr_shortcode'] = array(
			'section' => 'general',
			'title'   => __( 'Remove First Flickr Shortcode?' , 'flickr-shortcode-importer'),
			'desc'    => __( 'Removes the first [flickr] from post content. If you use Featured Images as header or lead images, then this might prevent duplicate images in your post.' , 'flickr-shortcode-importer'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		
		$this->settings['make_nice_image_title'] = array(
			'section' => 'general',
			'title'   => __( 'Make Nice Image Title?' , 'flickr-shortcode-importer'),
			'desc'    => __( "Try to make a nice title if none is set. For Flickr set images, Flickr set title plus a numeric suffix is applied." , 'flickr-shortcode-importer'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		
		$this->settings['replace_file_name'] = array(
			'section' => 'general',
			'title'   => __( 'Replace Filename with Image Title?' , 'flickr-shortcode-importer'),
			'desc'    => __( 'Mainly for SEO purposes. This setting replaces the imported media filename with the media\'s title. For non-images, this is always done.' , 'flickr-shortcode-importer'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		
		$this->settings['image_import_size'] = array(
			'section' => 'general',
			'title'   => __( 'Image Import Size' , 'flickr-shortcode-importer'),
			'desc'    => __( 'Size of image to import into media library from Flickr. If requested size doesn\'t exist, then original is imported because it\'s the closest to the requested import size.' , 'flickr-shortcode-importer'),
			'type'    => 'select',
			'std'     => 'Large',
			'choices' => array(
				'Small'			=> 'Small (240px wide)',
				'Medium 640'	=> 'Medium (640px wide)',
				'Large'			=> 'Large (1024px wide)',
				'Original'		=> 'Original'
			)
		);
		
		$this->settings['default_image_alignment'] = array(
			'section' => 'general',
			'title'   => __( 'Default Image Alignment' , 'flickr-shortcode-importer'),
			'desc'    => __( 'Default alignment of image displayed in post when no alignment is found.' , 'flickr-shortcode-importer'),
			'type'    => 'select',
			'std'     => 'left',
			'choices' => array(
				'none'		=> 'None',
				'left'		=> 'Left',
				'center'	=> 'Center',
				'right'		=> 'Right',
			)
		);
		
		$this->settings['default_image_size'] = array(
			'section' => 'general',
			'title'   => __( 'Default Image Size' , 'flickr-shortcode-importer'),
			'desc'    => __( 'Default size of image displayed in post when no size is found.' , 'flickr-shortcode-importer'),
			'type'    => 'select',
			'std'     => 'medium',
			'choices' => array(
				'thumbnail'	=> 'Thumbnail',
				'medium'	=> 'Medium',
				'large'		=> 'Large',
				'full'		=> 'Full'
			)
		);
		
		$this->settings['default_a_tag_class'] = array(
			'title'   => __( 'Default A Tag Class' , 'flickr-shortcode-importer'),
			'desc'    => __( 'Inserts a class into links around imported images. Useful for lightbox\'ing.' , 'flickr-shortcode-importer'),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['link_image_to_attach_page'] = array(
			'section' => 'general',
			'title'   => __( 'Link Image to Attachment Page?' , 'flickr-shortcode-importer'),
			'desc'    => __( 'If set, post single view images are linked to attachment pages. Otherwise the image links to its source file.' , 'flickr-shortcode-importer'),
			'type'    => 'checkbox',
			'std'     => 1
		);
		
		$this->settings['image_wrap_class'] = array(
			'title'   => __( 'Image Wrap Class' , 'flickr-shortcode-importer'),
			'desc'   => __( 'If set, a span tag is wrapped around the image with the given class. Also wraps attribution if enabled. e.g. Providing `flickr-image` results in `&lt;span class="flickr-image"&gt;|&lt;/span&gt;`' , 'flickr-shortcode-importer'),
			'std'     => __( '' , 'flickr-shortcode-importer'),
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['set_caption'] = array(
			'section' => 'general',
			'title'   => __( 'Set Captions?' , 'flickr-shortcode-importer'),
			'desc'    => __( 'Uses media title as the caption.' , 'flickr-shortcode-importer'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		
		$this->settings['flickr_image_attribution'] = array(
			'section' => 'general',
			'title'   => __( 'Include Flickr Author Attribution?' , 'flickr-shortcode-importer'),
			'desc'    => __( 'Appends Flickr username, linked back to Flickr image to the imported Flickr image.' , 'flickr-shortcode-importer'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		
		$this->settings['flickr_image_attribution_text'] = array(
			'title'   => __( 'Flickr Author Attribution Text' , 'flickr-shortcode-importer'),
			'desc'    => __( '' , 'flickr-shortcode-importer'),
			'std'     => __( 'Photo by ' , 'flickr-shortcode-importer'),
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['flickr_image_attribution_wrap_class'] = array(
			'title'   => __( 'Flickr Author Attribution Wrap Class' , 'flickr-shortcode-importer'),
			'desc'   => __( 'If set, a span tag is wrapped around the attribution with the given class. e.g. Providing `flickr-attribution` results in `&lt;span class="flickr-attribution"&gt;|&lt;/span&gt;`' , 'flickr-shortcode-importer'),
			'std'     => __( '' , 'flickr-shortcode-importer'),
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['flickr_link_in_desc'] = array(
			'section' => 'general',
			'title'   => __( 'Add Flickr Attribution to Description?' , 'flickr-shortcode-importer'),
			'desc'    => __( 'Like `Include Flickr Author Attribution` but appends the image description.' , 'flickr-shortcode-importer'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		
		$this->settings['flickr_link_text'] = array(
			'title'   => __( 'Flickr Attribution Text' , 'flickr-shortcode-importer'),
			'desc'    => __( '' , 'flickr-shortcode-importer'),
			'std'     => __( 'Photo by ' , 'flickr-shortcode-importer'),
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['flickr_image_license'] = array(
			'section' => 'general',
			'title'   => __( 'Add Image License to Description?' , 'flickr-shortcode-importer'),
			'desc'    => __( 'Append image license and link to image description.' , 'flickr-shortcode-importer'),
			'type'    => 'checkbox',
			'std'     => 0
		);
		
		$this->settings['flickr_image_license_text'] = array(
			'title'   => __( 'Flickr Image License Text' , 'flickr-shortcode-importer'),
			'desc'    => __( '' , 'flickr-shortcode-importer'),
			'std'     => __( 'License ' , 'flickr-shortcode-importer'),
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['posts_to_import'] = array(
			'title'   => __( 'Posts to Import' , 'flickr-shortcode-importer'),
			'desc'    => __( "A CSV list of post ids to import, like '1,2,3'." , 'flickr-shortcode-importer'),
			'std'     => '',
			'type'    => 'text',
			'section' => 'selection'
		);
		
		$this->settings['skip_importing_post_ids'] = array(
			'title'   => __( 'Skip Importing Posts' , 'flickr-shortcode-importer'),
			'desc'    => __( "A CSV list of post ids to not import, like '1,2,3'." , 'flickr-shortcode-importer'),
			'std'     => '',
			'type'    => 'text',
			'section' => 'selection'
		);
		
		$this->settings['limit'] = array(
			'title'   => __( 'Import Limit' , 'flickr-shortcode-importer'),
			'desc'    => __( 'Useful for testing import on a limited amount of posts. 0 or blank means unlimited.' , 'flickr-shortcode-importer'),
			'std'     => '',
			'type'    => 'text',
			'section' => 'testing'
		);
		
		$this->settings['debug_mode'] = array(
			'section' => 'testing',
			'title'   => __( 'Debug Mode?' , 'flickr-shortcode-importer'),
			'desc'	  => __( 'Bypass Ajax controller to handle posts_to_import directly for testing purposes', 'flickr-shortcode-importer' ),
			'type'    => 'checkbox',
			'std'     => 0
		);
		
		$this->settings['flickr_api_key'] = array(
			'title'   => __( 'Flickr API Key' , 'flickr-shortcode-importer'),
			'desc'    => __( '<a href="http://www.flickr.com/services/api/">Flickr API Documentation</a>' , 'flickr-shortcode-importer'),
			'std'     => '9f9508c77dc554c1ee7fdc006aa1879e',
			'type'    => 'text',
			'section' => 'api'
		);
		
		$this->settings['flickr_api_secret'] = array(
			'title'   => __( 'Flickr API Secret' , 'flickr-shortcode-importer'),
			'desc'    => __( '' , 'flickr-shortcode-importer'),
			'std'     => 'e63952df7d02cc03',
			'type'    => 'text',
			'section' => 'api'
		);
		
		$this->settings['role_enable_post_widget'] = array(
			'section' => 'posts',
			'title'   => __( 'Post [flickr] Import Widget?' , 'flickr-shortcode-importer'),
			'desc'    => __( 'Minimum role to enable for [flickr] Import widget on posts and page edit screens.' , 'flickr-shortcode-importer'),
			'type'    => 'select',
			'std'     => 'level_1',
			'choices' => array(
				''			=> 'Disable',
				'level_10'	=> 'Administrator',
				'level_7'	=> 'Editor',
				'level_4'	=> 'Author',
				'level_1'	=> 'Contributor',
			)
		);
				
		$post_types				= get_post_types( array( 'public' => true ), 'objects' );
		foreach( $post_types as $post_type => $ptype_obj ) {
			$this->settings[ 'enable_post_widget_' . $post_type ] = array(
				'section' => 'posts',
				'title'   => __( 'Enable for ' . $ptype_obj->labels->name, 'flickr-shortcode-importer'),
				'desc'    => __( '' , 'flickr-shortcode-importer'),
 				'type'    => 'checkbox',
				'std'     => ( 'attachment' != $post_type ) ? 1 : 0,
			);
		}

		/* Reset
		===========================================*/
		
		$this->settings['force_reimport'] = array(
			'section' => 'reset',
			'title'   => __( 'Reimport Flickr Source Images' , 'flickr-shortcode-importer'),
			'type'    => 'checkbox',
			'std'     => 0,
			'desc'    => __( 'Needed when changing the Flickr image import size from prior imports.' , 'flickr-shortcode-importer')
		);
		
		$this->settings['reset_plugin'] = array(
			'section' => 'reset',
			'title'   => __( 'Reset plugin' , 'flickr-shortcode-importer'),
			'type'    => 'checkbox',
			'std'     => 0,
			'class'   => 'warning', // Custom class for CSS
			'desc'    => __( 'Check this box and click "Save Changes" below to reset plugin options to their defaults.' , 'flickr-shortcode-importer')
		);

		// Here for reference
		if ( false ) {
		$this->settings['example_text'] = array(
			'title'   => __( 'Example Text Input' , 'flickr-shortcode-importer'),
			'desc'    => __( 'This is a description for the text input.' , 'flickr-shortcode-importer'),
			'std'     => 'Default value',
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['example_textarea'] = array(
			'title'   => __( 'Example Textarea Input' , 'flickr-shortcode-importer'),
			'desc'    => __( 'This is a description for the textarea input.' , 'flickr-shortcode-importer'),
			'std'     => 'Default value',
			'type'    => 'textarea',
			'section' => 'general'
		);
		
		$this->settings['example_checkbox'] = array(
			'section' => 'general',
			'title'   => __( 'Example Checkbox' , 'flickr-shortcode-importer'),
			'desc'    => __( 'This is a description for the checkbox.' , 'flickr-shortcode-importer'),
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
			'title'   => __( 'Example Radio' , 'flickr-shortcode-importer'),
			'desc'    => __( 'This is a description for the radio buttons.' , 'flickr-shortcode-importer'),
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
			'title'   => __( 'Example Select' , 'flickr-shortcode-importer'),
			'desc'    => __( 'This is a description for the drop-down.' , 'flickr-shortcode-importer'),
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
		
		update_option( 'fsi_options', $default_settings );
		
	}
	
	/**
	* Register settings
	*
	* @since 1.0
	*/
	public function register_settings() {
		
		register_setting( 'fsi_options', 'fsi_options', array ( &$this, 'validate_settings' ) );
		
		foreach ( $this->sections as $slug => $title ) {
			if ( $slug == 'about' )
				add_settings_section( $slug, $title, array( &$this, 'display_about_section' ), 'fsi-options' );
			else
				add_settings_section( $slug, $title, array( &$this, 'display_section' ), 'fsi-options' );
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
		
		wp_register_style( 'fsi-admin', plugins_url( 'settings.css', __FILE__ ) );
		wp_enqueue_style( 'fsi-admin' );
		
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

$FSI_Settings					= new FSI_Settings();

function fsi_get_options( $option, $default = false ) {
	$options					= get_option( 'fsi_options', $default );

	if ( isset( $options[$option] ) )
		return $options[$option];
	else
		return false;
}

function update_fsi_options( $option, $value = null ) {
	$options					= get_option( 'fsi_options' );

	if ( ! is_array( $options ) ) {
		$options				= array();
	}

	$options[$option]			= $value;
	update_option( 'fsi_options', $options );
}
?>