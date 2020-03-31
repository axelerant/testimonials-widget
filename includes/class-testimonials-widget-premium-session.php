<?php
/**
 * Testimonials Widget Premium Session based upon EDD Session by Pippin Williamson
 *
 * This is a wrapper class for WP_Session and handles the storage of form antispam details.
 */

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

if ( class_exists( 'Axl_Testimonials_Widget_Premium_Session' ) ) {
	return;
}


class Axl_Testimonials_Widget_Premium_Session {
	/**
	 * Holds our session data
	 *
	 * @var array
	 * @access private
	 */
	private $session = array();


	/**
	 * Get things started
	 *
	 * Defines our WP_Session constants, includes the necessary libraries and
	 * retrieves the WP Session instance
	 *
	 */
	public function __construct() {
		if ( ! defined( 'WP_SESSION_COOKIE' ) ) {
			define( 'WP_SESSION_COOKIE', 'twp_session' );
		}

		if ( ! class_exists( 'Recursive_ArrayAccess' ) ) {
			require_once TWP_DIR_LIB . 'class-recursive-arrayaccess.php';
		}

		if ( ! class_exists( 'WP_Session' ) ) {
			require_once TWP_DIR_LIB . 'class-wp-session.php';
			require_once TWP_DIR_LIB . 'wp-session.php';
		}

		if ( empty( $this->session ) ) {
			$this->init();
		}
	}


	/**
	 * Setup the WP_Session instance
	 *
	 * @access public
	 * @return void
	 */
	public function init() {
		$this->session = WP_Session::get_instance();

		return $this->session;
	}


	/**
	 * Retrieve a session variable
	 *
	 * @access public
	 * @param string $key Session key
	 * @return string Session variable
	 */
	public function get( $key ) {
		$key = sanitize_key( $key );

		return isset( $this->session[ $key ] ) ? maybe_unserialize( $this->session[ $key ] ) : null;
	}

	/**
	 * Set a session variable
	 *
	 *
	 * @param $key Session key
	 * @param $value Session variable
	 * @return mixed Session variable
	 */
	public function set( $key, $value ) {
		$key = sanitize_key( $key );

		if ( is_array( $value ) ) {
			$this->session[ $key ] = serialize( $value );
		} else {
			$this->session[ $key ] = $value;
		}

		return $this->session[ $key ];
	}


	public function session_okay() {
		return ! empty( $this->session );
	}
}


?>
