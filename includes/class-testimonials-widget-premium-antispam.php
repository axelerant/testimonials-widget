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

require_once TWP_DIR_INC . 'class-testimonials-widget-premium-antispam-base.php';
require_once TWP_DIR_INC . 'class-testimonials-widget-premium-antispam-bee.php';
require_once TWP_DIR_INC . 'class-testimonials-widget-premium-antispam-simple.php';

if ( class_exists( 'Axl_Testimonials_Widget_Premium_Antispam' ) ) {
	return;
}


class Axl_Testimonials_Widget_Premium_Antispam extends Axl_Testimonials_Widget_Premium_Antispam_Base {
	public function __construct( $input ) {
		parent::__construct( $input );

		$this->tests[] = 'Axl_Testimonials_Widget_Premium_Antispam_Simple';
		$this->tests[] = 'Axl_Testimonials_Widget_Premium_Antispam_Bee';

		$akismet_api_key = tw_get_option( 'akismet_api_key' );
		if ( $akismet_api_key ) {
			require_once TWP_DIR_INC . 'class-testimonials-widget-premium-antispam-akismet.php';
			$this->tests[] = 'Axl_Testimonials_Widget_Premium_Antispam_Akismet';
		}
	}


	public function run_tests() {
		foreach ( $this->tests as $test ) {
			$test_class                  = new $test( $this->input );
			$this->test_results[ $test ] = $test_class->is_valid();
		}

		$this->valid = true;
		foreach ( $this->test_results as $test => $result ) {
			if ( empty( $result ) ) {
				$this->valid = false;
				break;
			}
		}
	}
}


?>
