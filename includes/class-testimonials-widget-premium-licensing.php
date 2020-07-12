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

require_once AIHR_DIR_INC . 'class-aihrus-licensing.php';

if ( class_exists( 'Axl_Testimonials_Widget_Premium_Licensing' ) ) {
	return;
}


class Axl_Testimonials_Widget_Premium_Licensing extends Aihrus_Licensing {
	public static $settings_id = Axl_Testimonials_Widget_Settings::ID;


	public function __construct() {
		parent::__construct( Axl_Testimonials_Widget_Premium::SLUG, TWP_NAME );

		//add_filter( 'tw_settings', array( __CLASS__, 'settings' ), 5 );
	}


	public static function settings( $settings ) {
		$settings[ Axl_Testimonials_Widget_Premium::SLUG . 'license_key' ] = array(
			'section' => 'premium',
			'title' => esc_html__( 'License Key', 'testimonials-widget-premium' ),
			'desc' => esc_html__( 'Required to enable premium plugin updating. Activation is automatic. Use `0` to deactivate.', 'testimonials-widget-premium' ),
			'validate' => 'twp_update_license',
			'widget' => 0,
			'show_code' => false,
		);

		return $settings;
	}
}


/**
 *
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
function twp_update_license( $license ) {
	global $TW_Premium_Licensing;

	if ( ! empty( $_REQUEST['option_page'] ) && Axl_Testimonials_Widget_Settings::ID == $_REQUEST['option_page'] ) {
		$current_license = $TW_Premium_Licensing->get_license();
		$valid_license   = $TW_Premium_Licensing->valid_license();
		if ( ! $valid_license || $license != $current_license ) {
			$result        = $TW_Premium_Licensing->update_license( $license );
			$valid_license = $TW_Premium_Licensing->valid_license();
			if ( ! $valid_license ) {
				Axl_Testimonials_Widget_Premium::set_notice( 'notice_license', HOUR_IN_SECONDS );
			}
		}

		return $result;
	}

	return $license;
}


?>
