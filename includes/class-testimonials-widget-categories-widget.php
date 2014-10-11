<?php
/**
Testimonials Widget
Copyright (C) 2014  Michael Cannon

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

if ( class_exists( 'Testimonials_Widget_Categories_Widget' ) ) {
	return;
}


class Testimonials_Widget_Categories_Widget extends Aihrus_Widget {
	const ID = 'tw_categories_widget';

	public static $title;


	public function __construct() {
		$classname   = __CLASS__;
		$description = esc_html__( 'A list or dropdown of testimonials\' categories.', 'testimonials-widget' );
		$id_base     = self::ID;
		self::$title = esc_html__( 'Testimonials Categories', 'testimonials-widget' );

		parent::__construct( $classname, $description, $id_base, self::$title );
	}


	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function get_content( $instance = null, $widget_number = null ) {
		return Testimonials_Widget::testimonials_categories( $instance, $widget_number );
	}


	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function form_parts( $instance = null, $number = null ) {
		$form_parts = parent::form_parts( $instance, $number );

		$form_parts['title']['std'] = self::$title;

		$form_parts['dropdown'] = array(
			'title' => esc_html__( 'Display as dropdown', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 0,
		);

		$form_parts['count'] = array(
			'title' => esc_html__( 'Show post counts', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 0,
		);

		$form_parts['hierarchical'] = array(
			'title' => esc_html__( 'Show hierarchy', 'testimonials-widget' ),
			'type' => 'checkbox',
			'validate' => 'is_true',
			'std' => 0,
		);

		foreach ( $form_parts as $id => $parts ) {
			$form_parts[ $id ] = wp_parse_args( $parts, self::$default );
		}

		$form_parts = apply_filters( 'tw_categories_widget_options', $form_parts );

		return $form_parts;
	}
}


?>
