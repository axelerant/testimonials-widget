<?php
/**
Testimonials Widget Premium
Copyright (C) 2015 Axelerant

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
 * Class Axl_Testimonials_Widget_Premium_Sticky
 */
class Axl_Testimonials_Widget_Premium_Sticky {

	/**
	 * Initialize the class and add scripts
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'twps_admin_enqueue_scripts' ) );
		add_filter( 'tw_testimonials_query', array( __CLASS__, 'sticky_testimonials_query' ), 10, 2 );
	}

	/**
	 * Include admin scripts for the sticky feature
	 */
	public static function twps_admin_enqueue_scripts() {

		$screen = get_current_screen();

		// Only continue if this is an edit screen for a custom post type.
		if ( ! empty( $screen ) && ( ! in_array( $screen->base, array( 'post', 'edit' ) ) || 'testimonials-widget' !== $screen->post_type ) ) {
			return;
		}

		// Editing an individual custom post.
		if ( 'post' === $screen->base ) {
			$is_sticky = is_sticky();
			$js_vars   = array(
				'screen'                 => 'post',
				'is_sticky'              => $is_sticky ? 1 : 0,
				'checked_attribute'      => checked( $is_sticky, true, false ),
				'label_text'             => __( 'Stick this testimonial to the front', 'testimonials-widget-premium' ),
				'sticky_visibility_text' => __( 'Public, Sticky', 'testimonials-widget-premium' ),
			);

			// Browsing custom posts.
		} else {
			global $wpdb;

			$sticky_posts = (array) get_option( 'sticky_posts' );
			if ( ! empty( $sticky_posts ) ) {
				$sticky_posts = implode( ', ', array_map( 'absint', $sticky_posts ) );
			}

			if ( ! empty( $sticky_posts ) ) {
				$sticky_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT( ID ) FROM $wpdb->posts WHERE post_type = %s AND post_status NOT IN ('trash', 'auto-draft') AND ID IN ($sticky_posts)", $screen->post_type ) );
			} else {
				$sticky_count = 0;
			}

			$js_vars = array(
				'screen'            => 'edit',
				'post_type'         => $screen->post_type,
				'status_label_text' => __( 'Status', 'testimonials-widget-premium' ),
				'label_text'        => __( 'Make this testimonial sticky', 'testimonials-widget-premium' ),
				'sticky_text'       => __( 'Sticky', 'testimonials-widget-premium' ),
				'sticky_count'      => $sticky_count,
			);
		}

		// Enqueue js and pass it specified variables.
		wp_register_script( 'twps-admin-sticky', Axl_Testimonials_Widget_Premium::$plugin_assets . 'js/testimonials-widget-premium-sticky.min.js', array( 'jquery' ), TWP_VERSION, true );
		wp_enqueue_script( 'twps-admin-sticky' );
		wp_localize_script( 'twps-admin-sticky', 'twps', $js_vars );

	}

	/**
	 * Bring all the sticky testimonials to beginning of posts array.
	 *
	 * @param WP_Query $testimonials_query main query for testimonials.
	 *
	 * @return mixed
	 */
	public static function stick_the_sticky_at_top( $testimonials_query ) {

		$posts = $testimonials_query->posts;

		$sticky_posts  = (array) get_option( 'sticky_posts' );
		$num_posts     = count( $posts );
		$sticky_offset = 0;

		// Loop through the post array and find the sticky post.
		for ( $i = 0; $i < $num_posts; $i++ ) {

			// Put sticky posts at the top of the posts array.
			if ( isset( $posts[ $i ]->ID ) && in_array( $posts[ $i ]->ID, $sticky_posts ) ) {
				$sticky_post = $posts[ $i ];

				// Remove sticky from current position.
				array_splice( $posts, $i, 1 );
			}
		}

		// Fetch sticky posts that weren't in the query results.
		if ( ! empty( $sticky_posts ) ) {

			$stickies = get_posts( array(
				'post__in'    => $sticky_posts,
				'post_type'   => $testimonials_query->query_vars['post_type'],
				'post_status' => 'publish',
				'nopaging'    => true,
				'orderby'     => $testimonials_query->query_vars['orderby'],
				'order'       => $testimonials_query->query_vars['order'],
			) );

			foreach ( $stickies as $sticky_post ) {
				array_splice( $posts, $sticky_offset, 0, array( $sticky_post ) );
				$sticky_offset++;
			}
		}

		return $posts;
	}

	/**
	 * Add sticky posts to WP_Query
	 *
	 * @param WP_Query $testimonials_query main query for testimonials.
	 * @param array $args TWP parameters.
	 *
	 * @return WP_Query
	 */
	public static function sticky_testimonials_query( $testimonials_query, $args ) {
		$enable_sticky = tw_get_option( 'enable_sticky_testimonials' );
		if ( empty( $args['post__in'] ) && $enable_sticky ) {
			$testimonials_query->posts = self::stick_the_sticky_at_top( $testimonials_query );
		}

		return $testimonials_query;
	}
}

?>
