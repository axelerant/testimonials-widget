<?php
/**
Testimonials Widget
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

require_once AIHR_DIR_INC . 'class-aihrus-widget.php';

if ( class_exists( 'Axl_Testimonials_Widget_Tag_Cloud_Widget' ) ) {
	return;
}


class Axl_Testimonials_Widget_Tag_Cloud_Widget extends Aihrus_Widget {
	const ID = 'tw_tag_cloud_widget';

	public static $title;


	public function __construct() {
		$classname   = __CLASS__;
		$description = esc_html__( 'A cloud of your most used testimonials\' tags.', 'testimonials-widget' );
		$id_base     = self::ID;
		self::$title = esc_html__( 'Testimonials Tag Cloud', 'testimonials-widget' );

		parent::__construct( $classname, $description, $id_base, self::$title );
	}


	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function get_content( $instance = null, $widget_number = null ) {
		return Axl_Testimonials_Widget::testimonials_tag_cloud( $instance, $widget_number );
	}


	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function form_parts( $instance = null, $number = null ) {
		$form_parts = parent::form_parts( $instance, $number );

		$form_parts['title']['std'] = self::$title;

		$use_cpt_taxonomy = tw_get_option( 'use_cpt_taxonomy', false );
		if ( ! $use_cpt_taxonomy ) {
			$options = array(
				'category' => esc_html__( 'Category', 'testimonials-widget' ),
				'post_tag' => esc_html__( 'Tags', 'testimonials-widget' ),
			);

			$std = 'post_tag';
		} else {
			$options = array(
				Axl_Testimonials_Widget::$cpt_category => esc_html__( 'Category', 'testimonials-widget' ),
				Axl_Testimonials_Widget::$cpt_tags => esc_html__( 'Tags', 'testimonials-widget' ),
			);

			$std = Axl_Testimonials_Widget::$cpt_tags;
		}

		$form_parts['taxonomy'] = array(
			'title' => esc_html__( 'Taxonomy', 'testimonials-widget' ),
			'type' => 'select',
			'choices' => $options,
			'std' => $std,
		);

		foreach ( $form_parts as $id => $parts ) {
			$form_parts[ $id ] = wp_parse_args( $parts, self::$default );
		}

		$form_parts = apply_filters( 'tw_tag_cloud_widget_options', $form_parts );

		return $form_parts;
	}
}


?>
