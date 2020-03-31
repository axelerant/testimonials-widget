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

if ( class_exists( 'Axl_Testimonials_Widget_Premium_Antispam_Base' ) ) {
	return;
}


class Axl_Testimonials_Widget_Premium_Antispam_Base {
	protected $formatted;
	protected $input;
	protected $test_results = array();
	protected $tests        = array();
	protected $valid;


	public function __construct( $input ) {
		$this->input = $input;
	}


	public static function init( $input ) {
		$this->formatted    = null;
		$this->input        = $input;
		$this->test_results = array();
		$this->valid        = null;
	}


	public function is_valid() {
		if ( is_null( $this->valid ) ) {
			$this->run_tests();
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

		$this->formatted['title'] = $title;

		if ( ! empty( $this->input['post_content'] ) ) {
			$body = $this->input['post_content'];
		} else {
			$body = '';
		}

		$this->formatted['body'] = $body;

		if ( ! empty( $this->input['meta_email'] ) ) {
			$email = $this->input['meta_email'];
		} else {
			$email = '';
		}

		$this->formatted['email'] = $email;

		if ( ! empty( $this->input['meta_url'] ) ) {
			$url = $this->input['meta_url'];
		} else {
			$url = '';
		}

		$this->formatted['url'] = $url;

		if ( ! empty( $this->input['ID'] ) ) {
			$permalink = get_permalink( $this->input['ID'] );
		} else {
			$permalink = '';
		}

		$this->formatted['permalink'] = $permalink;
	}


	public function run_tests() {}
}


?>
