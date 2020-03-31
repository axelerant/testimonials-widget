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

require_once TWP_DIR_INC . 'class-testimonials-widget-premium-antispam-bee-base.php';

if ( class_exists( 'Axl_Testimonials_Widget_Premium_Antispam_Bee' ) ) {
	return;
}


class Axl_Testimonials_Widget_Premium_Antispam_Bee extends Axl_Testimonials_Widget_Premium_Antispam_Base {
	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */


	public function run_tests() {
		$this->valid = true;

		if ( empty( $this->formatted ) ) {
			$this->format_input();
		}

		$status = Axl_Testimonials_Widget_Premium_Antispam_Bee_Base::verify_comment_request( $this->formatted );
		if ( ! empty( $status ) ) {
			$this->valid = false;

			if ( ! empty( $_REQUEST['testmode'] ) ) {
				// @codingStandardsIgnoreStart
				echo '<div class="testimonials-widget-premium-form debug">';
				echo "Antispam_Bee\n<br />";
				var_dump( $status );
				echo '</div>';
				// @codingStandardsIgnoreEnd
			}
		}

		return $this->valid;
	}


	public function format_input() {
		$this->formatted = array();

		if ( ! empty( $this->input['post_title'] ) ) {
			$title = $this->input['post_title'];
		} else {
			$title = '';
		}

		$this->formatted['comment_author'] = $title;

		if ( ! empty( $this->input['post_content'] ) ) {
			$body = $this->input['post_content'];
		} else {
			$body = '';
		}

		$this->formatted['comment_content'] = $body;

		if ( ! empty( $this->input['meta_email'] ) ) {
			$email = $this->input['meta_email'];
		} else {
			$email = '';
		}

		$this->formatted['comment_author_email'] = $email;

		if ( ! empty( $this->input['meta_url'] ) ) {
			$url = $this->input['meta_url'];
		} else {
			$url = '';
		}

		$this->formatted['comment_author_url'] = $url;
	}
}


?>
