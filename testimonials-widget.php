<?php
/*
	Plugin Name: Testimonials Widget
	Plugin URI: http://wordpress.org/extend/plugins/testimonials-widget/
	Description: Testimonials Widget plugin allows you to display rotating content, portfolio, quotes, showcase, or other text with images on your WordPress blog.
	Version: 2.2.7
	Author: Michael Cannon
	Author URI: http://typo3vagabond.com/about-typo3-vagabond/hire-michael/
	License: GPLv2 or later
 */

/*
	Copyright 2012 Michael Cannon (email: michael@typo3vagabond.com)

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


class Testimonials_Widget {
	const page_key				= 'twlpg';
	const pt					= 'testimonials-widget';

	private $max_num_pages		= 0;
	private $post_count			= 0;

	static $css					= array();
	static $defaults			= array(
			'category'			=> '',
			'char_limit'		=> '',
			'hide_author'		=> '',
			'hide_company'		=> '',
			'hide_email'		=> '',
			'hide_gravatar'		=> '',
			'hide_image'		=> '',
			'hide_not_found'	=> '',
			'hide_source'		=> '',
			'hide_title'		=> '',
			'hide_url'			=> '',
			'ids'				=> '',
			'limit'				=> 25,
			'meta_key'			=> '',
			'max_height'		=> '',
			'min_height'		=> '',
			'order'				=> 'DESC',
			'orderby'			=> 'ID',
			'paging'			=> '',
			'random'			=> '',
			'refresh_interval'	=> 5,
			'tags'				=> '',
			'tags_all'			=> '',
			'target'			=> '',
	);
	static $scripts				= array();
	static $widget_number		= 100000;

	public function __construct() {
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
		add_action( 'init', array( &$this, 'init' ) );
		add_action( 'widgets_init', array( &$this, 'init_widgets' ) );
		add_shortcode( 'testimonialswidget_list', array( &$this, 'testimonialswidget_list' ) );
		add_shortcode( 'testimonialswidget_widget', array( &$this, 'testimonialswidget_widget' ) );
		add_theme_support( 'post-thumbnails' );
		load_plugin_textdomain( self::pt, false, 'testimonials-widget/languages' );
	}


	public function init() {
		self::$defaults['title']	= __( 'Testimonials', 'testimonials-widget' );
		self::init_post_type();
		self::styles();
	}


	public function activation() {
		self::init();
		flush_rewrite_rules();
	}


	public function admin_init() {
		$this->add_meta_box_testimonials_widget();
		$this->update();
		add_action( 'gettext', array( &$this, 'gettext_testimonials' ) );
		add_action( 'manage_' . self::pt . '_posts_custom_column', array( &$this, 'manage_testimonialswidget_posts_custom_column' ), 10, 2 );
		add_filter( 'manage_' . self::pt . '_posts_columns', array( &$this, 'manage_edit_testimonialswidget_columns' ) );
		add_filter( 'post_updated_messages', array( &$this, 'post_updated_messages' ) );
		add_filter( 'pre_get_posts', array( &$this, 'pre_get_posts_author' ) );
	}


	public function update() {
		$options				= get_option( 'testimonialswidget' );

		// testimonials already migrated?
		if ( true === $options['migrated'] )
			return;

		global $wpdb;
		$table_name				= $wpdb->prefix . 'testimonialswidget';
		$meta_key				= '_' . self::pt . ':testimonial_id';

		// check that db table exists and has entries
		$query					= 'SELECT `testimonial_id`, `testimonial`, `author`, `source`, `tags`, `public`, `time_added`, `time_updated` FROM `' . $table_name . '`';

		// ignore already imported
		$done_import_query		= 'SELECT meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key = "' . $meta_key . '"';
		$done_import			= $wpdb->get_col( $done_import_query );

		if ( ! empty( $done_import ) ) {
			$done_import		= array_unique( $done_import );
			$query				.= " WHERE testimonial_id NOT IN ( " . implode( ',', $done_import ) . " )";
		}

		$results				= $wpdb->get_results( $query );
		if( ! empty( $results ) ) {
			foreach ( $results as $result ) {
				// author can contain title and company details
				$author			= $result->author;
				$company		= false;

				// ex: Catherine Upton of Elearning!
				$author			= str_replace( ' of ', ', ', $author );
				// now ex: Catherine Upton, Elearning!

				// ex: Mark Gillingham, The Great Books Foundation
				// ex: Steve Adams, Web Development Manager, Topcon Positioning Systems, Inc.
				// ex: Karen Richard, Owner, Karen Richard Photography, LLC
				$author			= str_replace( ' of ', ', ', $author );
				$temp_comma		= '^^^';
				$author			= str_replace( ', LLC', $temp_comma . ' LLC', $author );
				// now ex: Karen Richard, Owner, Karen Richard Photography^^^ LLC
				$author			= str_replace( ', Inc', $temp_comma . ' Inc', $author );
				// ex: Steve Adams, Web Development Manager, Topcon Positioning Systems^^^ Inc.
				// it's possible to have "Michael Cannon, Senior Developer" and "Senior Developer" become the company. Okay for now
				$author			= explode( ', ', $author );

				if ( 1 < count( $author ) ) {
					$company	= array_pop( $author );
					$company	= str_replace( $temp_comma, ',', $company );
				}

				$author			= implode( ', ', $author );
				$author			= str_replace( $temp_comma, ',', $author );

				$post_data		= array(
					'post_type'			=> self::pt,
					'post_status'		=> ( 'yes' == $result->public ) ? 'publish' : 'private',
					'post_date'			=> $result->time_added,
					'post_modified'		=> $result->time_updated,
					'post_title'		=> $author,
					'post_content'		=> $result->testimonial,
					'tags_input'		=> $result->tags,
				);

				$post_id		= wp_insert_post( $post_data, true );

				// track/link testimonial import to new post
				add_post_meta( $post_id, $meta_key, $result->testimonial_id );

				if ( ! empty( $company ) ) {
					add_post_meta( $post_id, 'testimonials-widget-company', $company );
				}

				$source			= $result->source;
				if ( ! empty( $source ) ) {
					if ( is_email( $source ) ) {
						add_post_meta( $post_id, 'testimonials-widget-email', $source );
					} else {
						add_post_meta( $post_id, 'testimonials-widget-url', $source );
					}
				}
			}
		}

		$options['migrated']	= true;
		delete_option( 'testimonialswidget' );
		add_option( 'testimonialswidget', $options, null, 'no' );
	}


	public function pre_get_posts_author( $query ) {
		global $user_level, $user_ID;

		// author's and below
		if( $query->is_admin && ! empty( $query->is_main_query ) && $query->is_post_type_archive( Testimonials_Widget::pt ) && $user_level < 3 )
			$query->set( 'post_author', $user_ID );

		return $query;
	}


	public function manage_testimonialswidget_posts_custom_column( $column, $post_id ) {
		$result					= false;

		switch ( $column ) {
		case 'shortcode':
			$result				= '[testimonialswidget_list ids="';
			$result				.= $post_id;
			$result				.= '"]';
			break;

		case 'testimonials-widget-company':
		case 'testimonials-widget-title':
			$result				=  get_post_meta( $post_id, $column, true );
			break;

		case 'testimonials-widget-email':
		case 'testimonials-widget-url':
			$result				=  make_clickable( get_post_meta( $post_id, $column, true ) );
			break;

		case 'thumbnail':
			$email				= get_post_meta( $post_id, 'testimonials-widget-email', true );

			if ( has_post_thumbnail( $post_id ) ) {
				$result			= get_the_post_thumbnail( $post_id, 'thumbnail' );
			} elseif ( is_email( $email ) ) {
				$result			= get_avatar( $email );
			} else {
				$result			= false;
			}
			break;
		}

		if ( $result )
			echo $result;
	}


	public function manage_edit_testimonialswidget_columns( $columns ) {
		// order of keys matches column ordering
		$columns				= array(
			'cb'							=> '<input type="checkbox" />',
			'thumbnail'						=> __( 'Image' , 'testimonials-widget'),
			'title'							=> __( 'Source' , 'testimonials-widget'),
			'shortcode'						=> __( 'Shortcode' , 'testimonials-widget'),
			'testimonials-widget-title'		=> __( 'Title' , 'testimonials-widget'),
			'testimonials-widget-email'		=> __( 'Email' , 'testimonials-widget'),
			'testimonials-widget-company'	=> __( 'Company' , 'testimonials-widget'),
			'testimonials-widget-url'		=> __( 'URL' , 'testimonials-widget'),
			'author'						=> __( 'Published by' , 'testimonials-widget'),
			'categories'					=> __( 'Category' , 'testimonials-widget'),
			'tags'							=> __( 'Tags' , 'testimonials-widget'),
			'date'							=> __( 'Date' , 'testimonials-widget'),
		);

		return $columns;
	}


	public function init_post_type() {
		global $user_level;

		$labels = array(
			'add_new'			=> __( 'New Testimonial' , 'testimonials-widget'),
			'add_new_item'		=> __( 'Add New Testimonial' , 'testimonials-widget'),
			'edit_item'			=> __( 'Edit Testimonial' , 'testimonials-widget'),
			'name'				=> __( 'Testimonials' , 'testimonials-widget'),
			'new_item'			=> __( 'Add New Testimonial' , 'testimonials-widget'),
			'not_found' 		=>  __( 'No testimonials found' , 'testimonials-widget'),
			'not_found_in_trash'	=>  __( 'No testimonials found in Trash' , 'testimonials-widget'),
			'parent_item_colon'	=> null,
			'search_items'		=> __( 'Search Testimonials' , 'testimonials-widget'),
			'singular_name'		=> __( 'Testimonial' , 'testimonials-widget'),
			'view_item'			=> __( 'View Testimonial' , 'testimonials-widget'),
		);
		
		$supports 				= array(
			'title',
			'editor',
			'thumbnail',
		);

		// editor's and up
		if( $user_level > 3 )
			$supports[] 		= 'author';

		$args = array(
			'label'				=> __( 'Testimonials' , 'testimonials-widget'),
			'capability_type' 	=> 'post',
			'has_archive'		=> true,
			'hierarchical' 		=> false,
			'labels'			=> $labels,
			'public' 			=> true,
			'publicly_queryable'	=> true,
			'query_var' 		=> true,
			'rewrite'			=> array( 'slug' => 'testimonial' ),
			'show_in_menu'		=> true,
			'show_ui' 			=> true,
			'supports' 			=> $supports,
			'taxonomies'		=> array(
				'category',
				'post_tag',
			)
		);

		register_post_type( Testimonials_Widget::pt, $args );
	}


	public function testimonialswidget_list( $atts ) {
		$atts					= wp_parse_args( $atts, self::$defaults );
		$atts['type']			= 'testimonialswidget_list';

		$content				= apply_filters( 'testimonials_widget_cache_get', false, $atts );

		if ( false === $content ) {
			$testimonials		= self::get_testimonials( $atts );
			$content			= self::get_testimonials_html( $testimonials, $atts );
			$content			= apply_filters( 'testimonials_widget_cache_set', $content, $atts );
		}

		return $content;
	}


	public function testimonialswidget_widget( $atts, $widget_number = null ) {
		self::scripts();

		if ( empty( $atts['random'] ) )
			$atts['random']		= 'true';

		if ( empty( $widget_number ) )
			$widget_number		= self::$widget_number++;

		$atts['paging']			= false;
		$atts					= wp_parse_args( $atts, self::$defaults );
		$atts['type']			= 'testimonialswidget_widget';
		$atts['widget_number']	= $widget_number;

		$content				= apply_filters( 'testimonials_widget_cache_get', false, $atts );

		if ( false === $content ) {
			$testimonials		= self::get_testimonials( $atts );
			$content			= self::get_testimonials_html( $testimonials, $atts, false, $widget_number );
			$content			= apply_filters( 'testimonials_widget_cache_set', $content, $atts );
		}

		return $content;
	}


	public function scripts() {
		wp_enqueue_script( 'jquery' );
	}


	public function styles() {
		wp_register_style( 'testimonials-widget', plugins_url( 'testimonials-widget.css', __FILE__ ) );
		wp_enqueue_style( 'testimonials-widget' );
	}


	public function get_testimonials_html( $testimonials, $atts, $is_list = true, $widget_number = null ) {
		// display attributes
		$char_limit				= ( is_numeric( $atts['char_limit'] ) && 0 <= intval( $atts['char_limit'] ) ) ? intval( $atts['char_limit'] ) : false;
		$hide_title				= ( 'true' == $atts['hide_title'] ) ? true : false;
		$hide_company			= ( 'true' == $atts['hide_company'] ) ? true : false;
		$hide_email				= ( 'true' == $atts['hide_email'] ) ? true : false;
		$hide_image				= ( 'true' == $atts['hide_image'] ) ? true : false;
		$hide_not_found			= ( 'true' == $atts['hide_not_found'] ) ? true : false;
		$hide_source			= ( 'true' == $atts['hide_source'] || 'true' == $atts['hide_author'] ) ? true : false;
		$hide_url				= ( 'true' == $atts['hide_url'] ) ? true : false;
		$max_height				= ( is_numeric( $atts['max_height'] ) && 0 <= $atts['max_height'] ) ? intval( $atts['max_height'] ) : false;
		$min_height				= ( is_numeric( $atts['min_height'] ) && 0 <= $atts['min_height'] ) ? intval( $atts['min_height'] ) : false;
		$paging					= ( 'true' == $atts['paging'] ) ? true : false;
		$refresh_interval		= ( is_numeric( $atts['refresh_interval'] ) && 0 <= intval( $atts['refresh_interval'] ) ) ? intval( $atts['refresh_interval'] ) : 5;
		$target					= ( preg_match( '#^\w+$#', $atts['target'] ) ) ? $atts['target'] : false;

		$id = 'testimonialswidget_testimonials';

		if ( is_null( $widget_number ) ) {
			$html				= '<div class="' . $id;

			if ( $is_list )
				$html			.= ' testimonialswidget_testimonials_list';

			$html				.= '">';
		} else {
			$html				= '';
			$id_base			= $id . $widget_number;

			if ( $min_height ) {
				$css			= <<<EOF
<style>
.$id_base {
	min-height: {$min_height}px;
}
</style>
EOF;
				self::$css[]	= $css;
			}

			if ( $max_height ) {
				$css			= <<<EOF
<style>
.$id_base {
	max-height: {$max_height}px;
}
</style>
EOF;
				self::$css[]	= $css;
			}

			if ( $min_height || $max_height ) {
				add_action( 'wp_footer', array( &$this, 'get_testimonials_css' ), 20 );
			}

			if ( $refresh_interval && 1 < count( $testimonials ) ) {
				$javascript		= <<<EOF
<script type="text/javascript">
	function nextTestimonial$widget_number() {
		if (!jQuery('.$id_base').first().hasClass('hovered')) {
			var active = jQuery('.$id_base .testimonialswidget_active');
			var next = (jQuery('.$id_base .testimonialswidget_active').next().length > 0) ? jQuery('.$id_base .testimonialswidget_active').next() : jQuery('.$id_base .testimonialswidget_testimonial:first');
			active.fadeOut(1250, function(){
				active.removeClass('testimonialswidget_active');
				next.fadeIn(500);
				next.removeClass('testimonialswidget_display_none');
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
				self::$scripts[]	= $javascript;
				add_action( 'wp_footer', array( &$this, 'get_testimonials_scripts' ), 20 );

			}

			$html				.= '<div class="' . $id . ' ' . $id_base . '">';
		}

		if ( empty( $testimonials ) && ! $hide_not_found ) {
			$testimonials		= array(
				array( 'testimonial_content'	=>	__( 'No testimonials found' , 'testimonials-widget') )
			);
		}
		
		if ( $paging ) {
			$html				.= self::get_testimonials_paging( $testimonials, $atts );
		} 

		$is_first				= true;

		foreach ( $testimonials as $testimonial ) {
			$do_source			= ! $hide_source && ! empty( $testimonial['testimonial_source'] );
			$do_title			= ! $hide_title && ! empty( $testimonial['testimonial_title'] );
			$do_company			= ! $hide_company && ! empty( $testimonial['testimonial_company'] );
			$do_email			= ! $hide_email && ! empty( $testimonial['testimonial_email'] ) && is_email( $testimonial['testimonial_email'] );
			$do_image			= ! $hide_image && ! empty( $testimonial['testimonial_image'] );
			$do_url				= ! $hide_url && ! empty( $testimonial['testimonial_url'] );

			$html				.= '<div class="testimonialswidget_testimonial';

			if ( $is_list ) {
				$html			.= ' testimonialswidget_testimonial_list';
			} elseif ( $is_first ) {
				$html			.= ' testimonialswidget_active';
				$is_first		= false;
			} elseif ( ! $is_first ) {
				$html			.= ' testimonialswidget_display_none';
			}

			$html				.= '">';

			if ( $do_image ) {
				$html			.= '<span class="testimonialswidget_image">';
				$html			.= $testimonial['testimonial_image'];
				$html			.= '</span>';
			}

			$content			= $testimonial['testimonial_content'];
			$content			= self::testimonials_truncate( $content, $char_limit );
			$content			= force_balance_tags( $content );
			$content			= wptexturize( $content );
			$content			= convert_smilies( $content );
			$content			= convert_chars( $content );

			if ( is_null( $widget_number ) ) {
				$content		= wpautop( $content );
				$content		= shortcode_unautop( $content );
			}

			$content			= make_clickable( $content );

			$html				.= '<q>';
			$html				.= $content;
			$html				.= '</q>';

			$cite				= '';

			if ( $do_source && $do_email ) {
				$cite			.= '<span class="testimonialswidget_author">';
				$cite			.= '<a href="mailto:' . $testimonial['testimonial_email'] . '">';
				$cite			.= $testimonial['testimonial_source'];
				$cite			.= '</a>';
				$cite			.= '</span>';
			} elseif ( $do_source ) {
				$cite			.= '<span class="testimonialswidget_author">';
				$cite			.= $testimonial['testimonial_source'];
				$cite			.= '</span>';
			} elseif ( $do_email ) {
				$cite			.= '<span class="testimonialswidget_email">';
				$cite			.= make_clickable( $testimonial['testimonial_email'] );
				$cite			.= '</span>';
			}

			if ( $do_title && $cite )
				$cite			.= '<span class="testimonialswidget_join_title"></span>';

			if ( $do_title ) {
				$cite			.= '<span class="testimonialswidget_title">';
				$cite			.= $testimonial['testimonial_title'];
				$cite			.= '</span>';
			}

			if ( ( $do_company || $do_url ) && $cite )
				$cite			.= '<span class="testimonialswidget_join"></span>';

			if ( $do_company && $do_url ) {
				$cite			.= '<span class="testimonialswidget_company">';
				$cite			.= '<a href="' . $testimonial['testimonial_url'] . '">';
				$cite			.= $testimonial['testimonial_company'];
				$cite			.= '</a>';
				$cite			.= '</span>';
			} elseif ( $do_company ) {
				$cite			.= '<span class="testimonialswidget_company">';
				$cite			.= $testimonial['testimonial_company'];
				$cite			.= '</span>';
			} elseif ( $do_url ) {
				$cite			.= '<span class="testimonialswidget_url">';
				$cite			.= make_clickable( $testimonial['testimonial_url'] );
				$cite			.= '</span>';
			}

			if ( ! empty( $cite ) )
				$cite			= '<cite>' . $cite . '</cite>';

			$html				.= $cite;

			if ( ! empty( $testimonial['testimonial_readme'] ) ) {
				$html			.= '<div class="testimonialswidget_readme';
				$html			.= $testimonial['testimonial_readme'];
				$html			.= '</div>';
			}

			if ( ! empty( $testimonial['testimonial_extra'] ) ) {
				$html			.= '<div class="testimonialswidget_extra';
				$html			.= $testimonial['testimonial_extra'];
				$html			.= '</div>';
			}

			$html				.= '</div>';
		}

		if ( $paging ) {
			$html				.= self::get_testimonials_paging( $testimonials, $atts, false );
		} 

		$html					.= '</div>';

		if ( $target )
			$html				= links_add_target( $html, $target );

		return $html;
	}


	public function get_testimonials_paging( $testimonials, $atts, $prepend = true ) {
		$html					= '';

		// if testimonials 1 or less, return
		if ( is_home() || 1 === $this->max_num_pages ) {
			return $html;
		}

		$html					.= '<div class="testimonialswidget_paging';

		if ( $prepend ) {
			$html				.= ' prepend';
		} else {
			$html				.= ' append';
		}

		$html					.= '">';

		if ( ! empty( $_REQUEST[ self::page_key ] ) ) {
			$paged				= intval( $_REQUEST[ self::page_key ] );
		} else {
			$paged				= 1;
		}

		$html					.= '	<div class="alignleft">';

		if ( 1 < $paged ) {
			$html				.= self::get_previous_posts_link( __( '&laquo;' , 'testimonials-widget'), $paged );
		} else {
			// $html				.= __( '&laquo;' );
		}

		$html					.= '	</div>';

		$html					.= '	<div class="alignright">';

		if ( $paged != $this->max_num_pages ) {
			$html				.= self::get_next_posts_link( __( '&raquo;' , 'testimonials-widget'), $paged );
		} else {
			// $html				.= __( '&raquo;' );
		}

		$html					.= '	</div>';

		$html					.= '</div>';

		return $html;
	}


	function get_pagenum_link( $pagenum = 1 ) {
		$request				= remove_query_arg( self::page_key );

		if ( 1 != $pagenum ) {
			$base				= trailingslashit( get_bloginfo( 'url' ) );
			$request			= add_query_arg( self::page_key, $pagenum, $base . $request );
		}

		return esc_url( $request );
	}


	function get_previous_posts_link( $label, $paged = 1 ) {
		if ( $paged > $this->max_num_pages ) {
			return '<a href="' . $this->get_pagenum_link() . '">' . $label . '</a>';
		} elseif ( $paged > 1 ) {
			return '<a href="' . $this->get_pagenum_link( $paged - 1 ) . '">' . $label . '</a>';
		} else {
			return '';
		}
	}


	function get_next_posts_link( $label, $paged = 1 ) {
		$next_page				= intval( $paged ) + 1;

		if ( $next_page <= $this->max_num_pages ) {
			return '<a href="' . $this->get_pagenum_link( $next_page ) . '">' . $label . '</a>';
		} else {
			return '';
		}
	}


	public function get_testimonials_css() {
		foreach( self::$css as $key => $css ) {
			echo $css;
		}
	}


	public function get_testimonials_scripts() {
		foreach( self::$scripts as $key => $script ) {
			echo $script;
		}
	}


	// Original PHP code as myTruncate2 by Chirp Internet: www.chirp.com.au
	public function testimonials_truncate( $string, $char_limit = false, $break = ' ', $pad = '…' ) {
		if ( ! $char_limit )
			return $string;

		// return with no change if string is shorter than $char_limit
		if ( strlen( $string ) <= $char_limit )
			return $string;

		$string					= substr( $string, 0, $char_limit );
		if ( false !== ( $breakpoint = strrpos( $string, $break ) ) ) {
			$string				= substr( $string, 0, $breakpoint );
		}

		return $string . $pad;
	}


	public function get_testimonials( $atts ) {
		// selection attributes
		$category				= ( preg_match( '#^[\w-]+(,[\w-]+)*$#', $atts['category'] ) ) ? $atts['category'] : false;
		$ids					= ( preg_match( '#^\d+(,\d+)*$#', $atts['ids'] ) ) ? $atts['ids'] : false;
		$limit					= ( is_numeric( $atts['limit'] ) && 0 < $atts['limit'] ) ? intval( $atts['limit'] ) : 25;
		$meta_key				= ( preg_match( '#^[\w-,]+$#', $atts['meta_key'] ) ) ? $atts['meta_key'] : false;
		$order					= ( preg_match( '#^desc|asc$#i', $atts['order'] ) ) ? $atts['order'] : 'DESC';
		$orderby				= ( preg_match( '#^\w+$#', $atts['orderby'] ) ) ? $atts['orderby'] : 'ID';
		$paging					= ( 'true' == $atts['paging'] ) ? true : false;
		$random					= ( 'true' == $atts['random'] ) ? true : false;
		$tags					= ( preg_match( '#^[\w-]+(,[\w-]+)*$#', $atts['tags'] ) ) ? $atts['tags'] : false;
		$tags_all				= ( 'true' == $atts['tags_all'] ) ? true : false;

		$hide_gravatar			= ( 'true' == $atts['hide_gravatar'] ) ? true : false;

		if ( $random ) {
			$orderby			= 'rand';
			$order				= false;
		}

		$args					= array(
			'orderby'			=> $orderby,
			'post_status'		=> 'publish',
			'post_type'			=> Testimonials_Widget::pt,
			'posts_per_page'	=> $limit,
		);

		if ( $paging && ! empty( $_REQUEST[ self::page_key ] ) ) {
			$args['paged']		= intval( $_REQUEST[ self::page_key ] );
		}

		if ( ! $random && $meta_key ) {
			$args['meta_key']	= $meta_key;
			$args['orderby']	= 'meta_value';
		}

		if ( $order ) {
			$args['order']		= $order;
		}

		if ( $ids ) {
			$ids				= explode( ',', $ids );

			$args['post__in']	= $ids;
		}

		if ( $category ) {
			$args['category_name']	= $category;
		}

		if ( $tags ) {
			$tags				= explode( ',', $tags );

			if ( $tags_all ) {
				$args['tag_slug__and']	= $tags;
			} else {
				$args['tag_slug__in']	= $tags;
			}
		}

		$testimonials			= apply_filters( 'testimonials_widget_cache_get', false, $args );

		if ( false === $testimonials ) {
			$testimonials		= new WP_Query( $args );
			$testimonials		= apply_filters( 'testimonials_widget_cache_set', $testimonials, $args );
		}

		$this->max_num_pages	= $testimonials->max_num_pages;
		$this->post_count		= $testimonials->post_count	;

		wp_reset_postdata();

		$image_size				= apply_filters( 'testimonials_widget_image_size', 'thumbnail' );
		$gravatar_size			= apply_filters( 'testimonials_widget_gravatar_size', 96 );

		$testimonial_data		= array();
		
		if ( empty( $this->post_count ) )
			return $testimonial_data;

		foreach( $testimonials->posts as $row ) {
			$post_id			= $row->ID;

			$email				= get_post_meta( $post_id, 'testimonials-widget-email', true );

			if ( has_post_thumbnail( $post_id ) ) {
				$image			= get_the_post_thumbnail( $post_id, $image_size );
			} elseif ( ! $hide_gravatar && is_email( $email ) ) {
				$image			= get_avatar( $email, $gravatar_size );
			} else {
				$image			= false;
			}

			$testimonial_data[]	= array(
				'post_id'				=> $post_id,
				'testimonial_company'	=> get_post_meta( $post_id, 'testimonials-widget-company', true ),
				'testimonial_content'	=> $row->post_content,
				'testimonial_email'		=> $email,
				'testimonial_extra'		=> '',
				'testimonial_image'		=> $image,
				'testimonial_readme'	=> '',
				'testimonial_source'	=> $row->post_title,
				'testimonial_title'		=> get_post_meta( $post_id, 'testimonials-widget-title', true ),
				'testimonial_url'		=> get_post_meta( $post_id, 'testimonials-widget-url', true ),
			);
		}

		$testimonial_data		= apply_filters( 'testimonials_widget_data', $testimonial_data );

		return $testimonial_data;
	}


	public function init_widgets() {
		require_once 'lib/testimonials-widget-widget.php';

		register_widget( 'Testimonials_Widget_Widget' );
	}


	public function add_meta_box_testimonials_widget() {
		require_once( 'lib/metabox.class.php' );

		$meta_box				= redrokk_metabox_class::getInstance(
			'testimonialswidget',
			array(
				'title'			=> __( 'Testimonial Data' , 'testimonials-widget'),
				'description'	=> __( '' , 'testimonials-widget'),
				'_object_types'	=> 'testimonials-widget',
				'priority'		=> 'high',
				'_fields'		=> array(
					array(
						'name' 	=> __( 'Title' , 'testimonials-widget'),
						'id' 	=> 'testimonials-widget-title',
						'type' 	=> 'text',
						'desc'	=> __( '' , 'testimonials-widget'),
					),
					array(
						'name' 	=> __( 'Email' , 'testimonials-widget'),
						'id' 	=> 'testimonials-widget-email',
						'type' 	=> 'text',
						'desc'	=> __( '' , 'testimonials-widget'),
					),
					array(
						'name' 	=> __( 'Company' , 'testimonials-widget'),
						'id' 	=> 'testimonials-widget-company',
						'type' 	=> 'text',
						'desc'	=> __( '' , 'testimonials-widget'),
					),
					array(
						'name' 	=> __( 'URL' , 'testimonials-widget'),
						'id' 	=> 'testimonials-widget-url',
						'type' 	=> 'text',
						'desc'	=> __( '' , 'testimonials-widget'),
					),
				)
			)
		);
	}


	/**
	 * Revise default new testimonial text
	 *
	 * Original author: Travis Ballard http://www.travisballard.com
	 *
	 * @param string $translation
	 * @return string $translation
	 */
	public function gettext_testimonials( $translation ) {
		remove_action( 'gettext', array( &$this, 'gettext_testimonials' ) );

		global $post;

		if ( is_object( $post ) && self::pt == $post->post_type ) {
			switch( $translation ) {
			case __( 'Enter title here' , 'testimonials-widget'):
				return __( 'Enter testimonial source here' , 'testimonials-widget');
				break;
			}
		}

		add_action( 'gettext', array( &$this, 'gettext_testimonials' ) );

		return $translation;
	}


	/**
	 * Update messages for custom post type
	 *
	 * Original author: Travis Ballard http://www.travisballard.com
	 *
	 * @param mixed $m
	 * @return mixed $m
	 */
	public function post_updated_messages( $m ) {
		global $post;

		$m[ self::pt ] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __( 'Testimonial updated. <a href="%s">View testimonial</a>' , 'testimonials-widget'), esc_url( get_permalink( $post->ID ) ) ),
			2 => __( 'Custom field updated.' , 'testimonials-widget'),
			3 => __( 'Custom field deleted.' , 'testimonials-widget'),
			4 => __( 'Testimonial updated.' , 'testimonials-widget'),
			/* translators: %s: date and time of the revision */
			5 => isset( $_GET['revision'] ) ? sprintf( __( 'Testimonial restored to revision from %s' , 'testimonials-widget'), wp_post_revision_title( (int)$_GET['revision'], false ) ) : false,
			6 => sprintf( __( 'Testimonial published. <a href="%s">View testimonial</a>' , 'testimonials-widget'), esc_url( get_permalink( $post->ID ) ) ),
			7 => __( 'Testimonial saved.' , 'testimonials-widget'),
			8 => sprintf( __( 'Testimonial submitted. <a target="_blank" href="%s">Preview testimonial</a>' , 'testimonials-widget'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ) ),
			9 => sprintf( __( 'Testimonial scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview testimonial</a>' , 'testimonials-widget'), date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ) ), esc_url( get_permalink( $post->ID ) ) ),
			10 => sprintf( __( 'Testimonial draft updated. <a target="_blank" href="%s">Preview testimonial</a>' , 'testimonials-widget'), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) )
		);

		return $m;
	}

}


$Testimonials_Widget			= new Testimonials_Widget();

register_activation_hook( __FILE__, array( &$Testimonials_Widget, 'activation' ) );


function testimonialswidget_list( $atts = array() ) {
	global $Testimonials_Widget;

	return $Testimonials_Widget->testimonialswidget_list( $atts );
}


function testimonialswidget_widget( $atts = array(), $widget_number = null ) {
	global $Testimonials_Widget;

	if ( empty( $atts['random'] ) )
		$atts['random']			= 'true';

	return $Testimonials_Widget->testimonialswidget_widget( $atts, $widget_number );
}

?>
