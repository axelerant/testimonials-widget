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

if ( class_exists( 'Axl_Testimonials_Widget_Premium_Cache' ) ) {
	return;
}


class Axl_Testimonials_Widget_Premium_Cache {
	const ID = 'testimonials-widget-premium-cache';

	public static $cache;
	public static $cache_period = HOUR_IN_SECONDS;


	public function __construct() {
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ), 15 );
		add_action( 'init', array( __CLASS__, 'init' ) );
		add_action( 'twp_cron', array( __CLASS__, 'twp_cron' ) );
	}


	public static function admin_init() {
		add_action( 'save_post', array( __CLASS__, 'clear_cache' ) );
		add_action( 'tw_update', array( __CLASS__, 'update' ) );
	}


	public static function admin_menu() {
		add_submenu_page( 'edit.php?post_type=' . Axl_Testimonials_Widget::PT, esc_html__( 'Testimonials Widget Premium Cache', 'testimonials-widget-premium' ), esc_html__( 'Clear Cache', 'testimonials-widget-premium' ), 'manage_options', self::ID, array( 'Axl_Testimonials_Widget_Premium_Cache', 'do_clear_cache' ) );
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */


	public static function init() {
		self::check_clear_cache();

		add_filter( 'tw_cache_get', array( __CLASS__, 'cache_get' ), 10, 2 );
		add_filter( 'tw_cache_set', array( __CLASS__, 'cache_set' ), 10, 2 );
	}


	public static function update() {
		self::clear_cache_all();
	}


	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function check_clear_cache() {
		$clear_cache = false;

		// TWP clear cache menu link
		if ( ! empty( $_GET['post_type'] ) && Axl_Testimonials_Widget::PT == $_GET['post_type'] && ! empty( $_GET['page'] ) && self::ID == $_GET['page'] ) {
			$clear_cache = true;
		}

		// TWP settings clear cache URL request
		if ( ! empty( $_REQUEST['clearcache'] ) || ! empty( $_REQUEST['nocache'] ) || ! empty( $_REQUEST['no_cache'] ) ) {
			$clear_cache = true;
		}

		// DB Cache Reloaded Fix
		if ( ! empty( $_POST['clear'] ) || ! empty( $_POST['clearold'] ) ) {
			$clear_cache = true;
		}

		// FlexiCache
		if ( isset( $_POST['_purge'] ) && 'true' == $_POST['_purge'] ) {
			$clear_cache = true;
		}

		// Hyper Cache
		if ( ! empty( $_POST['clean'] ) ) {
			$clear_cache = true;
		}

		// WP Super Cache
		if ( isset( $_GET['action'] ) && 'delcachepage' == $_GET['action'] && ( isset( $_GET['_wpnonce'] ) ? wp_verify_nonce( $_REQUEST['_wpnonce'], 'delete-cache' ) : false ) ) {
			$clear_cache = true;
		}

		if ( $clear_cache ) {
			$transients = self::clear_cache_all();
			set_transient( self::ID, $transients, self::$cache_period );
			add_action( 'admin_notices', array( 'Axl_Testimonials_Widget_Premium_Cache', 'notice_clear_cache' ) );
		}
	}


	public static function do_clear_cache() {
		echo '<div class="wrap">';
		echo '<div class="icon32" id="icon-options-general"></div>';
		echo '<h2>' . esc_html__( 'Testimonials Widget Premium Cache', 'testimonials-widget-premium' ) . '</h2>';
		echo '</div>';
	}


	public static function notice_clear_cache() {
		$transients = get_transient( self::ID );
		if ( false !== $transients && is_array( $transients ) ) {
			$cache_count = count( $transients );
		} else {
			$cache_count = 0;
		}

		$text  = esc_html__( '%1$d cache %2$s deleted.', 'testimonials-widget-premium' );
		$entry = _n( 'entry', 'entries', $cache_count, 'testimonials-widget-premium' );
		$entry = esc_html__( $entry, 'testimonials-widget-premium' );
		echo '<div class="updated"><p>' . sprintf( $text, $cache_count, $entry ) . '</p></div>';
	}


	public static function clear_cache_all( $age = 0 ) {
		return self::purge_transients( $age );
	}


	public static function activate_cron() {
		if ( ! wp_next_scheduled( 'twp_cron' ) ) {
			wp_schedule_event( time(), 'daily', 'twp_cron' );
		}
	}


	public static function deactivate_cron() {
		if ( wp_next_scheduled( 'twp_cron' ) ) {
			wp_clear_scheduled_hook( 'twp_cron' );
		}
	}


	public static function twp_cron() {
		self::clear_cache_all( '60 minutes' );
	}


	/**
	 *
	 *
	 * @author Scott Phillips <https://github.com/ScottPhillips>
	 * @ref https://gist.github.com/2907732
	 */
	public static function purge_transients( $older_than = '1 day', $old_name = false ) {
		global $wpdb;

		$time            = time() + self::$cache_period;
		$older_than_time = strtotime( '-' . $older_than, $time );

		if ( $older_than_time > $time || $older_than_time < 1 ) {
			return false;
		}

		if ( ! $old_name ) {
			$query = "
				SELECT REPLACE(option_name, '_transient_timeout_', '') AS transient_name
				FROM {$wpdb->options}
				WHERE 1 = 1
					AND ( option_name LIKE '_transient_" . Axl_Testimonials_Widget_Premium::SLUG . "%%'
						OR option_name LIKE '_transient_" . self::ID . "%%' )
					AND option_name NOT LIKE '_transient_timeout_" . Axl_Testimonials_Widget_Premium::SLUG . "license_key%%'
					AND option_name NOT LIKE '_transient_timeout_" . Axl_Testimonials_Widget_Premium::SLUG . "notices%%'
			";

			if ( $older_than ) {
				$query = $wpdb->prepare(
					$query . ' AND option_value < %d',
					$older_than_time
				);
			}
		} else {
			$query = "
				SELECT REPLACE(option_name, '_transient_', '') AS transient_name
				FROM {$wpdb->options}
				WHERE 1 = 1
					AND option_name REGEXP '^_transient_[a-f0-9]{32}$'
					AND option_value LIKE '%class=\"testimonialswidget%'
			";
		}

		$transients = $wpdb->get_col( $query );

		if ( ! empty( $transients ) ) {
			foreach ( $transients as $transient ) {
				delete_transient( $transient );
			}
		}

		return $transients;
	}


	public static function clear_cache( $post_id = null ) {
		if ( is_null( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( Axl_Testimonials_Widget::PT != get_post_type( $post_id ) ) {
			return;
		}

		global $wpdb;

		$query = "
			SELECT REPLACE(option_name, '_transient_', '') AS transient_name
			FROM {$wpdb->options}
			WHERE 1 = 1
				AND ( option_name LIKE '_transient_" . Axl_Testimonials_Widget_Premium::SLUG . "%%'
					OR option_name LIKE '_transient_" . self::ID . "%%' )
				AND (
					option_value LIKE '%%s:2:\"ID\";i:%d;%%'
					OR option_value LIKE '%% testimonials-widget-testimonials:%d:%%'
				)
		";
		$query = $wpdb->prepare( $query, $post_id, $post_id );

		$transients = $wpdb->get_col( $query );
		if ( empty( $transients ) ) {
			return;
		}

		foreach ( $transients as $transient ) {
			delete_transient( $transient );
		}
	}


	public static function create_hash( $context ) {
		$serialized_context = serialize( $context );
		$hash               = Axl_Testimonials_Widget_Premium::SLUG . md5( $serialized_context );

		return $hash;
	}


	public static function cache_get( $data, $args ) {
		$data = false;

		$do_cache = self::do_cache( $args );
		if ( ! $do_cache ) {
			delete_transient( self::ID );

			return $data;
		}

		if ( null === self::$cache ) {
			self::$cache = get_transient( self::ID );
		}

		$hash = self::create_hash( $args );
		if ( isset( self::$cache[ $hash ] ) ) {
			return self::$cache[ $hash ];
		} else {
			return $data;
		}
	}


	public static function cache_set( $data, $args ) {
		$do_cache = self::do_cache( $args );
		if ( ! $do_cache ) {
			delete_transient( self::ID );

			return $data;
		}

		if ( null === self::$cache ) {
			self::$cache = get_transient( self::ID );
		}

		if ( ! is_array( self::$cache ) ) {
			self::$cache = array();
		}

		$hash                 = self::create_hash( $args );
		self::$cache[ $hash ] = $data;
		set_transient( self::ID, self::$cache, self::$cache_period );

		return $data;
	}


	public static function do_cache( $args ) {
		$disable_cache = apply_filters( 'tw_disable_cache', false );
		$do_cache      = empty( $args['no_cache'] );
		$enable_cache  = empty( $disable_cache ) && $do_cache;

		return $enable_cache;
	}
}


?>
