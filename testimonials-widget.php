<?php
/*
Plugin Name: Testimonials Widget
Description: Testimonial widget plugin helps you display testimonials in a sidebar on your WordPress blog.
Version: 0.2.4
Author: comprock, j0hnsmith
License: GPL2
 */

/*  Copyright 2011 j0hnsmith

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/*  This plugin borrows code from the Quotes Collection plugin by Srini G
	http://srinig.com/wordpress/plugins/quotes-collection/

	Additional contributions from comprock and ChrisCree
 */


/*  Refer http://codex.wordpress.org/Roles_and_Capabilities */
$testimonialswidget_admin_userlevel = 'edit_posts';

$testimonialswidget_db_version = '0.1';


require_once('testimonials-widget-widget.php');
require_once('testimonials-widget-admin.php');


function testimonialswidget_display_testimonials($title = '', $random = 1, $min_height, $refresh_interval = 10, $show_source = 0, $show_author = 1, $tags = '', $char_limit = 500, $widget_number = '', $tags_all = 0) {
	$conditions = " WHERE public = 'yes'";

	if(char_limit && is_numeric($char_limit)) {
		$conditions .= " AND CHAR_LENGTH(testimonial) <= ".$char_limit;
	} else {
		$options['char_limit'] = 0;
	}

	if($tags) {
		$taglist = explode(',', $tags);
		$tag_conditions = '';
		foreach($taglist as $tag) {
			$tag = mysql_real_escape_string(strip_tags(trim($tag)));
			if($tag_conditions) {
				if ( $tags_all ) {
					$tag_conditions .= ' AND ';
				} else {
					$tag_conditions .= ' OR ';
				}
			}
			$tag_conditions .= "FIND_IN_SET('{$tag}', tags)";
		}
		$conditions .= " AND ({$tag_conditions})";
	}

	if($random) {
		$conditions .= " ORDER BY RAND()";
	} else {
		$conditions .= " ORDER BY testimonial_id DESC";
	}

	if ( 0 == $refresh_interval )
		$conditions .= " LIMIT 1";

	$testimonials = testimonialswidget_get_testimonials($conditions);

	$min_height .= 'px';
	$id = 'testimonialswidget_testimonials';
	$id_base = $id . $widget_number;
	$html = <<<EOF
	<style>
		.$id_base {
		min-height: $min_height;
	}
	</style>
EOF;
	if ( 0 != $refresh_interval ) {
		$html .= <<<EOF
	<script type="text/javascript">
			function nextTestimonial$widget_number() {
				if (!jQuery('.$id_base').first().hasClass('hovered')) {
					var active = jQuery('.$id_base .testimonialswidget_active');
					var next = (jQuery('.$id_base .testimonialswidget_active').next().length > 0) ? jQuery('.$id_base .testimonialswidget_active').next() : jQuery('.$id_base .testimonialswidget_testimonial:first');
				active.fadeOut(1250, function(){
					active.removeClass('testimonialswidget_active');
					next.fadeIn(500);
					next.addClass('testimonialswidget_active');
				});
			}
		}

		jQuery(document).ready(function(){
				jQuery('.$id_base').hover(function() { jQuery(this).addClass('hovered') }, function() { jQuery(this).removeClass('hovered') });
				setInterval('nextTestimonial$widget_number()', $refresh_interval * 1000);
		});
	</script>
EOF;
	}

	$html .= '<div class="'.$id.' '.$id_base.'">';
	$first = true;

	foreach ($testimonials as $testimonial) {

		if (!$first) {
			$html .= '<div class="testimonialswidget_testimonial">';
		} else {
			$html .= '<div class="testimonialswidget_testimonial testimonialswidget_active">';
			$first = false;
		}
		$html .= "<p><q>". make_clickable( $testimonial['testimonial'] ) ."</q>";
		$cite = '';
		if($show_author && ! empty( $testimonial['author'] ) )
			$cite = '<span class="testimonialswidget_author">'. make_clickable( $testimonial['author'] ) .'</span>';

		if($show_source && ! empty( $testimonial['source'] ) ) {
			if($cite) $cite .= ', ';
			$cite .= '<span class="testimonialswidget_source">'. make_clickable( $testimonial['source'] ) .'</span>';
		}
		if($cite) $cite = " <cite>&mdash;&nbsp;{$cite}</cite>";
		$html .= $cite."</p></div>";

	}
	$html .= '</div>';

	return $html;
}


function testimonialswidget_get_testimonials($conditions = '') {
	global $wpdb;
	$sql = "SELECT testimonial_id, testimonial, author, source, tags, public
		FROM " . $wpdb->prefix . "testimonialswidget"
		. $conditions;

	if($testimonials = $wpdb->get_results($sql, ARRAY_A))
		return $testimonials;
	else
		return array();
}


function testimonialswidget_install() {
	global $wpdb;
	$table_name = $wpdb->prefix . "testimonialswidget";

	if(!defined('DB_CHARSET') || !($db_charset = DB_CHARSET))
		$db_charset = 'utf8';
	$db_charset = "CHARACTER SET ".$db_charset;
	if(defined('DB_COLLATE') && $db_collate = DB_COLLATE)
		$db_collate = "COLLATE ".$db_collate;

	// if table name already exists
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
		$wpdb->query("ALTER TABLE `{$table_name}` {$db_charset} {$db_collate}");

		$wpdb->query("ALTER TABLE `{$table_name}` MODIFY testimonial TEXT {$db_charset} {$db_collate}");

		$wpdb->query("ALTER TABLE `{$table_name}` MODIFY author VARCHAR(255) {$db_charset} {$db_collate}");

		$wpdb->query("ALTER TABLE `{$table_name}` MODIFY source VARCHAR(255) {$db_charset} {$db_collate}");

		if(!($wpdb->get_results("SHOW COLUMNS FROM {$table_name} LIKE 'tags'"))) {
			$wpdb->query("ALTER TABLE `{$table_name}` ADD `tags` VARCHAR(255) {$db_charset} {$db_collate} AFTER `source`");
		}
		if(!($wpdb->get_results("SHOW COLUMNS FROM {$table_name} LIKE 'public'"))) {
			$wpdb->query("ALTER TABLE `{$table_name}` CHANGE `visible` `public` enum('yes', 'no') DEFAULT 'yes' NOT NULL");
		}
	}
	else {
		//Creating the table ... fresh!
		$sql = "CREATE TABLE " . $table_name . " (
			testimonial_id mediumint(9) NOT NULL AUTO_INCREMENT,
			testimonial TEXT NOT NULL,
			author VARCHAR(255),
			source VARCHAR(255),
			tags VARCHAR(255),
			public enum('yes', 'no') DEFAULT 'yes' NOT NULL,
			time_added datetime NOT NULL,
			time_updated datetime,
			PRIMARY KEY  (testimonial_id)
	) {$db_charset} {$db_collate};";
		$results = $wpdb->query( $sql );
	}

	global $testimonialswidget_db_version;
	$options = get_option('testimonialswidget');
	$options['db_version'] = $testimonialswidget_db_version;
	update_option('testimonialswidget', $options);

}


function testimonialswidget_css_head() {
?>
	<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/testimonials-widget/testimonials-widget.css" />
<?php
}

function testimonialswidget_enqueue_scripts() {
	wp_enqueue_script('jquery');
}

add_action('wp_head', 'testimonialswidget_css_head' );
add_action('wp_enqueue_scripts', 'testimonialswidget_enqueue_scripts');

register_activation_hook( __FILE__, 'testimonialswidget_install' );

function testimonialswidget_init() {
	load_plugin_textdomain( 'testimonials-widget', null, '/testimonials-widget/languages/' );
}

add_action('init', 'testimonialswidget_init');

function testimonialswidget_list_shortcode($atts, $content = null) {
	$show_author = ($atts['hide_author']) ? false : true; 
	$show_source = ($atts['hide_source']) ? false : true; 

	$testimonials = testimonialswidget_get_testimonials();

	$html .= '<div class="testimonialswidget_testimonials_list">';

	foreach ($testimonials as $testimonial) {
		$html .= '<div class="testimonialswidget_testimonial_list">';
		$html .= "<p><q>". make_clickable( $testimonial['testimonial'] ) ."</q>";
		$cite = '';
		if($show_author && ! empty( $testimonial['author'] ) )
			$cite = '<span class="testimonialswidget_author">'. make_clickable( $testimonial['author'] ) .'</span>';

		if($show_source && ! empty( $testimonial['source'] ) ) {
			if($cite) $cite .= ', ';
			$cite .= '<span class="testimonialswidget_source">'. make_clickable( $testimonial['source'] ) .'</span>';
		}
		if($cite) $cite = " <cite>&mdash;&nbsp;{$cite}</cite>";
		$html .= $cite."</p></div>";
	}

	$html .= '</div>';

	return $html;

}

add_shortcode('testimonialswidget_list', 'testimonialswidget_list_shortcode'); 

?>
