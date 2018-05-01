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

/**
 * Testimonials template loader class
 *
 * @ref https://github.com/GaryJones/Gamajo-Template-Loader
 */

require_once TW_DIR_LIB . 'Gamajo-Template-Loader/class-gamajo-template-loader.php';

if ( class_exists( 'Axl_Testimonials_Widget_Template_Loader' ) ) {
	return;
}


/**
 *
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Axl_Testimonials_Widget_Template_Loader extends Gamajo_Template_Loader {
	protected $filter_prefix            = 'testimonials_widget';
	protected $theme_template_directory = 'testimonials-widget';
	protected $plugin_directory         = TW_DIR;
}

?>
