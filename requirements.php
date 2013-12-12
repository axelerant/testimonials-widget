<?php
/*
	Copyright 2013 Michael Cannon (email: mc@aihr.us)

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

require_once ABSPATH . 'wp-admin/includes/plugin.php';

if ( ! function_exists( 'aihr_check_php' ) ) {
	function aihr_check_php( $file = null, $php_min = '5.3.0' ) {
		if ( is_null( $file ) ) {
			aihr_notice_error( __( '`aihr_check_php` requires $file argument' ) );

			return false;
		}

		$file       = plugin_basename( $file );
		$check_okay = version_compare( PHP_VERSION, $php_min, '>=' );

		if ( ! $check_okay && __FILE__ != $file ) {
			deactivate_plugins( $file );

			if ( ! defined( 'AF_PHP_VERSION_FILE' ) )
				define( 'AF_PHP_VERSION_FILE', $file );

			if ( ! defined( 'AF_PHP_VERSION_MIN' ) )
				define( 'AF_PHP_VERSION_MIN', $php_min );

			add_action( 'admin_notices', 'aihr_notice_php' );
		}

		return $check_okay;
	}
}


if ( ! function_exists( 'aihr_notice_php' ) ) {
	function aihr_notice_php() {
		$base = basename( dirname( AF_PHP_VERSION_FILE ) );
		$base = str_replace( '-', ' ', $base );
		$base = ucwords( $base );

		$help_url = esc_url( 'https://aihrus.zendesk.com/entries/30678006' );
		$php_min  = basename( AF_PHP_VERSION_MIN );

		$text = sprintf( __( 'Plugin "%1$s" has been deactivated as it requires PHP %2$s or newer. You\'re running PHP %4$s. Once corrected, "%1$s" can be activated. <a href="%3$s">More information</a>.' ), $base, $php_min, $help_url, PHP_VERSION );

		aihr_notice_error( $text );
	}
}


if ( ! function_exists( 'aihr_notice_error' ) ) {
	function aihr_notice_error( $text ) {
		aihr_notice_updated( $text, 'error' );
	}
}


if ( ! function_exists( 'aihr_notice_updated' ) ) {
	function aihr_notice_updated( $text, $class = 'updated' ) {
		if ( 'updated' == $class )
			$class .= ' fade';

		$content  = '';
		$content .= '<div class="' . $class . '"><p>';
		$content .= $text;
		$content .= '</p></div>';

		echo $content;
	}
}

if ( ! function_exists( 'aihr_notice_version' ) ) {
	function aihr_notice_version( $required_base, $required_name, $required_slug, $required_version, $item_name ) {
		$is_active = is_plugin_active( $required_base );
		if ( $is_active )
			$link = sprintf( __( '<a href="%1$s">update to</a>' ), self_admin_url( 'update-core.php' ) );
		else {
			$plugins = get_plugins();
			if ( empty( $plugins[ $required_base ] ) ) {
				$install = esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $required_slug ), 'install-plugin_' . $required_slug ) );
				$link    = sprintf( __( '<a href="%1$s">install</a>' ), $install );
			} else {
				$activate = esc_url( wp_nonce_url( admin_url( 'plugins.php?action=activate&plugin=' . $required_base ), 'activate-plugin_' . $required_base ) );
				$link     = sprintf( __( '<a href="%1$s">activate</a>' ), $activate );
			}
		}

		$text = sprintf( __( 'Plugin %3$s has been deactivated. Please %1$s %4$s version %2$s or newer before activating %3$s.' ), $link, $required_version, $item_name, $required_name );

		aihr_notice_error( $text );
	}
}

?>
