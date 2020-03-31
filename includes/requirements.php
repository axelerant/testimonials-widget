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

require_once AIHR_DIR . 'aihrus-framework.php';


function twp_requirements_check( $force_check = false ) {
	/*if ( is_plugin_active( TWP_REQ_BASE ) ) {
		aihr_deactivate_plugin( TWP_REQ_BASE );
		add_action( 'admin_notices', 'twp_notice_tw_deactivated' );
	}*/

	$check_okay = get_transient( 'twp_requirements_check' );
	if ( empty( $force_check ) && false !== $check_okay ) {
		return $check_okay;
	}

	$deactivate_reason = false;
	if ( ! aihr_check_php( TWP_BASE, TWP_NAME ) ) {
		$deactivate_reason = esc_html__( 'Old PHP version detected', 'testimonials-widget-premium' );
	}

	if ( ! aihr_check_wp( TWP_BASE, TWP_NAME ) ) {
		$deactivate_reason = esc_html__( 'Old WordPress version detected', 'testimonials-widget-premium' );
	}

	global $tw_activated;

	if ( empty( $tw_activated ) ) {
		$deactivate_reason = esc_html__( 'Internal Testimonials Widget not detected', 'testimonials-widget-premium' );
	}

	if ( ! empty( $deactivate_reason ) ) {
		aihr_deactivate_plugin( TWP_BASE, TWP_NAME, $deactivate_reason );
	}

	$check_okay = empty( $deactivate_reason );
	if ( $check_okay ) {
		delete_transient( 'twp_requirements_check' );
		set_transient( 'twp_requirements_check', $check_okay, HOUR_IN_SECONDS );
	}

	return $check_okay;
}


function twp_notice_aihrus() {
	$help_url  = esc_url( 'https://axelerant.atlassian.net/wiki/display/WPFAQ/Axelerant+Framework+Out+of+Date' );
	$help_link = sprintf( __( '<a href="%1$s">Update plugins</a>. <a href="%2$s">More information</a>.', 'testimonials-widget-premium' ), self_admin_url( 'update-core.php' ), $help_url );

	$text = sprintf( esc_html__( 'Plugin "%1$s" has been deactivated as it requires a current Aihrus Framework. Once corrected, "%1$s" can be activated. %2$s', 'testimonials-widget-premium' ), TWP_NAME, $help_link );

	aihr_notice_error( $text );
}


function twp_notice_tw_deactivated() {
	$text = sprintf( esc_html__( 'Plugin "%1$s" has been deactivated as it is no longer required by "%2$s". You can safely delete plugin "%1$s" given that "Remove Plugin Data on Deletion?" isn\'t checked on the Reset tab of Settings.', 'testimonials-widget-premium' ), TWP_REQ_NAME, TWP_NAME );

	aihr_notice_updated( $text );
}

?>
