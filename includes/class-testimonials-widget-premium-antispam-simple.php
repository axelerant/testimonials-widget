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

if ( class_exists( 'Axl_Testimonials_Widget_Premium_Antispam_Simple' ) ) {
	return;
}


class Axl_Testimonials_Widget_Premium_Antispam_Simple extends Axl_Testimonials_Widget_Premium_Antispam_Base {
	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */


	public function run_tests() {
		$valid_agent = ! empty( $_SERVER['HTTP_USER_AGENT'] ) ? true : false;
		$valid_hpsc  = empty( $this->input['hpsc'] );

		$site_url = ! empty( $_SERVER['HTTP_ORIGIN'] ) ? $_SERVER['HTTP_ORIGIN'] : false;
		if ( empty( $site_url ) ) {
			$site_url = 'http://' . $_SERVER['HTTP_HOST'];
		}

		$base_url      = parse_url( $site_url );
		$base_url      = $base_url['host'];
		$referer_url   = ! empty( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';
		$valid_referer = strpos( $referer_url, $base_url ) !== false;

		$hpsc_session       = twp_session_get( 'hpsc_session' );
		$valid_hpsc_session = $hpsc_session == $this->input['hpsc_session'];

		$hide_are_you_human = tw_get_option( 'hide_are_you_human' );
		if ( $hide_are_you_human ) {
			$valid_human = true;
		} else {
			$sum1        = twp_session_get( 'hpsc_sum1' );
			$sum2        = twp_session_get( 'hpsc_sum2' );
			$valid_human = ( $sum2 == $this->input['are_you_human'] );
			twp_session_set( 'hpsc_sum2', $sum1, HOUR_IN_SECONDS );
		}

		if ( ! empty( $_REQUEST['testmode'] ) ) {
			// @codingStandardsIgnoreStart
			echo '<div class="testimonials-widget-premium-form debug">';
			echo '<h2>Antispam Debug</h2>';

			echo "session_okay\n<br />";
			$session_okay = Axl_Testimonials_Widget_Premium::$session->session_okay();
			var_dump( $session_okay );
			echo "\n<br />";

			echo "valid_agent\n<br />";
			var_dump( $valid_agent );
			echo "\n<br />";

			echo "valid_hpsc\n<br />";
			var_dump( $valid_hpsc );
			echo "\n<br />";

			echo "valid_referer\n<br />";
			var_dump( $valid_referer );
			echo "\n<br />";

			echo "referer_url\n<br />";
			var_dump( $referer_url );
			echo "\n<br />";

			echo "site_url\n<br />";
			var_dump( $site_url );
			echo "\n<br />";

			echo "base_url\n<br />";
			var_dump( $base_url );
			echo "\n<br />";

			echo "valid_hpsc_session\n<br />";
			var_dump( $valid_hpsc_session );
			echo "\n<br />";

			echo "valid_human\n<br />";
			var_dump( $valid_human );
			echo "\n<br />";

			if ( isset( $sum1) && isset( $sum2 ) ) {
				$are_you_human = $this->input['are_you_human'];
				echo 'given: ';
				var_dump( $are_you_human );
				echo "\n<br />";

				echo 'expected: ';
				var_dump( $sum2 );
				echo "\n<br />";

				echo 'next expected: ';
				var_dump( $sum1 );
				echo "\n<br />";
			}

			echo '</div>';
			// @codingStandardsIgnoreEnd
		}

		if ( ! $valid_agent || ! $valid_hpsc || ! $valid_referer || ! $valid_hpsc_session || ! $valid_human ) {
			$this->valid = false;
		} else {
			$this->valid = true;
		}
	}
}


?>
