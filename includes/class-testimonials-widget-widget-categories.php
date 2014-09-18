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

if ( class_exists( 'Testimonials_Widget_Widget_Categories' ) )
	return;


class Testimonials_Widget_Widget_Categories extends Aihrus_Widget {
	const ID = 'tw_widget_categories';

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
	public static function get_content( $instance, $widget_number ) {
		$c = ! empty( $instance['count'] ) ? '1' : '0';
		$d = ! empty( $instance['dropdown'] ) ? '1' : '0';
		$h = ! empty( $instance['hierarchical'] ) ? '1' : '0';

		$cat_args = array(
			'orderby' => 'name',
			'show_count' => $c,
			'hierarchical' => $h,
		);

		$use_cpt_taxonomy = tw_get_option( 'use_cpt_taxonomy', false );
		if ( $use_cpt_taxonomy ) {
			$cat_args['taxonomy'] = Testimonials_Widget::$cpt_category;
		}

		if ( $d ) {
			$cat_args['show_option_none'] = esc_html__( 'Select Testimonials Category', 'testimonials-widget' );

			wp_dropdown_categories( apply_filters( 'tw_widget_categories_dropdown_args', $cat_args ) );
			?>

			<script type='text/javascript'>
			/* <![CDATA[ */
			var dropdown = document.getElementById("cat");
			function onCatChange() {
				if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
					location.href = "<?php echo home_url(); ?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
				}
			}
			dropdown.onchange = onCatChange;
			/* ]]> */
			</script>

			<?php
		} else {
			?>
			<ul>
			<?php
				$cat_args['title_li'] = '';

				wp_list_categories( apply_filters( 'tw_widget_categories_args', $cat_args ) );
			?>
			</ul>
			<?php
		}
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

		$form_parts = apply_filters( 'tw_widget_categories_options', $form_parts );

		return $form_parts;
	}
}


?>
