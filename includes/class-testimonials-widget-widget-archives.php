<?php
/**
Aihrus Testimonials
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

if ( class_exists( 'Testimonials_Widget_Widget_Archives' ) )
	return;


class Testimonials_Widget_Widget_Archives extends Aihrus_Widget {
	const ID = 'testimonials_widget_widget_archives';

	public static $title;


	public function __construct() {
		$classname   = __CLASS__;
		$description = esc_html__( 'A monthly archive of your site\'s testimonials.', 'testimonials-widget' );
		$id_base     = self::ID;
		self::$title = esc_html__( 'Testimonials Archives', 'testimonials-widget' );

		parent::__construct( $classname, $description, $id_base, self::$title );
	}


	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function get_content( $instance, $widget_number ) {
		$c = ! empty( $instance['count'] ) ? '1' : '0';
		$d = ! empty( $instance['dropdown'] ) ? '1' : '0';
		
		add_filter( 'getarchives_where', array( __CLASS__, 'getarchives_where' ), 10, 2 );

		if ( $d ) {
			?>
			<select name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'>
				<option value=""><?php echo esc_attr( __( 'Select Month' ) ); ?></option>
				<?php
				wp_get_archives( apply_filters( 'testimonials_widget_widget_archives_dropdown_args', array(
					'type'            => 'monthly',
					'format'          => 'option',
					'show_post_count' => $c
				) ) );
				?>
			</select>
			<?php
		} else {
			?>
			<ul>
			<?php
				wp_get_archives( apply_filters( 'testimonials_widget_widget_archives_args', array(
					'type'            => 'monthly',
					'show_post_count' => $c
				) ) );
			?>
			</ul>
			<?php
		}
		
		remove_filter( 'getarchives_where', array( __CLASS__, 'getarchives_where' ), 10, 2 );
	}


	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public static function getarchives_where( $where, $args ) {
		error_log( print_r( func_get_args(), true ) . ':' . __LINE__ . ':' . basename( __FILE__ ) );
		return "WHERE post_type = '" . Testimonials_Widget::PT . "' AND post_status = 'publish'";
	}


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

		foreach ( $form_parts as $id => $parts ) {
			$form_parts[ $id ] = wp_parse_args( $parts, self::$default );
		}

		$form_parts = apply_filters( 'testimonials_widget_widget_archives_options', $form_parts );

		return $form_parts;
	}
}


?>
