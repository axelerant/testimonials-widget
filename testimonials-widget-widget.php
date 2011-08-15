<?php

function testimonialswidget_widget_init()
{
	if(function_exists('load_plugin_textdomain'))
		load_plugin_textdomain('testimonials-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;
	
	function testimonialswidget_widget() {
		$options = get_option('testimonialswidget');
		$title = isset($options['title'])?apply_filters('the_title', $options['title']):__('Testimonials', 'testimonials-widget');
		$min_height = isset($options['min_height'])?$options['min_height']:150;
		$show_author = isset($options['show_author'])?$options['show_author']:1;
		$show_source = isset($options['show_source'])?$options['show_source']:1;
		$random_order = isset($options['random_order'])?$options['random_order']:1;
		$refresh_interval = isset($options['refresh_interval'])?$options['refresh_interval']:5;
		$char_limit = $options['char_limit'];
		$tags = $options['tags'];
		if($testimonials = testimonialswidget_display_testimonials($title, $random_order, $min_height, $refresh_interval, $show_source, $show_author, $tags, $char_limit)) {
			echo $before_widget;
			if($title) echo $before_title . $title . $after_title . "\n";
			echo $testimonials;
			echo $after_widget;
		}
	}
	
	function testimonialswidget_widget_control()
	{
		// default values for options
		$options = array(
			'title' => __('Testimonials', 'testimonials-widget'), 
			'min_height' => 150,
			'show_author' => 1,
			'show_source' => 0, 
			'random_order' => 1,
			'refresh_interval' => 5,
			'tags' => '',
			'char_limit' => 500
		);

		if($options_saved = get_option('testimonialswidget'))
			$options = array_merge($options, $options_saved);
			
		// Update options in db when user updates options in the widget page
		if(isset($_REQUEST['testimonialswidget-submit']) && $_REQUEST['testimonialswidget-submit']) {
			$options['title'] = strip_tags(stripslashes($_REQUEST['testimonialswidget-title']));
			$options['min_height'] = strip_tags(stripslashes($_REQUEST['testimonialswidget-min_height']));
			$options['show_author'] = (isset($_REQUEST['testimonialswidget-show_author']) && $_REQUEST['testimonialswidget-show_author'])?1:0;
			$options['show_source'] = (isset($_REQUEST['testimonialswidget-show_source']) && $_REQUEST['testimonialswidget-show_source'])?1:0;
			$options['refresh_interval'] = strip_tags(stripslashes($_REQUEST['testimonialswidget-refresh_interval']));
			$options['random_order'] = (isset($_REQUEST['testimonialswidget-random_order']) && $_REQUEST['testimonialswidget-random_order'])?1:0;
			$options['tags'] = strip_tags(stripslashes($_REQUEST['testimonialswidget-tags']));
			$options['char_limit'] = strip_tags(stripslashes($_REQUEST['testimonialswidget-char_limit']));
			if(!$options['char_limit'])
				$options['char_limit'] = __('none', 'testimonials-widget');
			update_option('testimonialswidget', $options);
		}

		// Now we define the display of widget options menu
		$show_author_checked = $show_source_checked	= $random_order_checked = '';
		$int_select = array ( '5' => '', '10' => '', '15' => '', '20' => '');
        if($options['show_author'])
        	$show_author_checked = ' checked="checked"';
        if($options['show_source'])
        	$show_source_checked = ' checked="checked"';
        if($options['random_order'])
        	$random_order_checked = ' checked="checked"';
		echo "<p style=\"text-align:left;\"><label for=\"testimonialswidget-title\">".__('Title', 'testimonials-widget')." </label><input class=\"widefat\" type=\"text\" id=\"testimonialswidget-title\" name=\"testimonialswidget-title\" value=\"".htmlspecialchars($options['title'], ENT_QUOTES)."\" /></p>";
		echo "<p style=\"text-align:left;\"><label for=\"testimonialswidget-min_height\">".__('Minimum Height', 'testimonials-widget')." </label><input class=\"widefat\" type=\"text\" id=\"testimonialswidget-min_height\" name=\"testimonialswidget-min_height\" value=\"".htmlspecialchars($options['min_height'], ENT_QUOTES)."\" /><br/><span class=\"setting-description\"><small>".__('Minimum height in px, this must be set to a value that suits your logest testimonial (increase this value if you find that your testimonials are getting cut off).', 'testimonials-widget')."</small></span></p>";
		echo "<p style=\"text-align:left;\"><input type=\"checkbox\" id=\"testimonialswidget-show_author\" name=\"testimonialswidget-show_author\" value=\"1\"{$show_author_checked} /> <label for=\"testimonialswidget-show_author\">".__('Show author?', 'testimonials-widget')."</label></p>";
		echo "<p style=\"text-align:left;\"><input type=\"checkbox\" id=\"testimonialswidget-show_source\" name=\"testimonialswidget-show_source\" value=\"1\"{$show_source_checked} /> <label for=\"testimonialswidget-show_source\">".__('Show source?', 'testimonials-widget')."</label></p>";
		echo "<p style=\"text-align:left;\"><small><a id=\"testimonialswidget-adv_key\" style=\"cursor:pointer;\" onclick=\"jQuery('div#testimonialswidget-adv_opts').slideToggle();\">".__('Advanced options', 'testimonials-widget')." &raquo;</a></small></p>";
		echo "<div id=\"testimonialswidget-adv_opts\" style=\"display:none\">";
		echo "<p style=\"text-align:left;\"><label for=\"testimonialswidget-refresh_interval\">".__('Refresh Interval', 'testimonials-widget')." </label><input class=\"widefat\" type=\"text\" id=\"testimonialswidget-refresh_interval\" name=\"testimonialswidget-refresh_interval\" value=\"".htmlspecialchars($options['refresh_interval'], ENT_QUOTES)."\" /><br/><span class=\"setting-description\"><small>".__('In seconds.', 'testimonials-widget')."</small></span></p>";
		echo "<p style=\"text-align:left;\"><input type=\"checkbox\" id=\"testimonialswidget-random_order\" name=\"testimonialswidget-random_order\" value=\"1\"{$random_order_checked} /> <label for=\"testimonialswidget-random_order\">".__('Random order', 'testimonials-widget')."</label><br/><span class=\"setting-description\"><small>".__('Unchecking this will rotate testimonials in the order added, latest first.', 'testimonials-widget')."</small></span></p>";
		echo "<p style=\"text-align:left;\"><label for=\"testimonialswidget-tags\">".__('Tags filter', 'testimonials-widget')." </label><input class=\"widefat\" type=\"text\" id=\"testimonialswidget-tags\" name=\"testimonialswidget-tags\" value=\"".htmlspecialchars($options['tags'], ENT_QUOTES)."\" /><br/><span class=\"setting-description\"><small>".__('Comma separated', 'testimonials-widget')."</small></span></p>";
		echo "<p style=\"text-align:left;\"><label for=\"testimonialswidget-char_limit\">".__('Character limit', 'testimonials-widget')." </label><input class=\"widefat\" type=\"text\" id=\"testimonialswidget-char_limit\" name=\"testimonialswidget-char_limit\" value=\"".htmlspecialchars($options['char_limit'], ENT_QUOTES)."\" /></p>";
		echo "</div>";
		echo "<input type=\"hidden\" id=\"testimonialswidget-submit\" name=\"testimonialswidget-submit\" value=\"1\" />";
	}

	if ( function_exists( 'wp_register_sidebar_widget' ) ) {
		wp_register_sidebar_widget( 'testimonialswidget', 'Testimonials', 'testimonialswidget_widget' );
		wp_register_widget_control( 'testimonialswidget', 'Testimonials', 'testimonialswidget_widget_control', 250, 350 );
	} else {
		register_sidebar_widget(array('Testimonials', 'widgets'), 'testimonialswidget_widget');
		register_widget_control('Testimonials', 'testimonialswidget_widget_control', 250, 350);
	}
}

add_action('plugins_loaded', 'testimonialswidget_widget_init');
?>
