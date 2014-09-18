<?php
/**
Aihrus Framework
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

if ( class_exists( 'Aihrus_Widget_Interface' ) )
	return;


interface Aihrus_Widget_Interface {
	public static function form_instance( $instance );
	public static function form_parts( $instance = null, $number = null );
	public static function get_content( $instance, $widget_number );
	public static function get_defaults();
	public static function validate_settings( $instance );
}


?>
