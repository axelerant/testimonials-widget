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

require_once TWP_DIR_LIB . 'Akismet-API/Rzeka/Service/Akismet.php';
require_once TWP_DIR_LIB . 'Akismet-API/Rzeka/Service/Akismet/Connector/ConnectorInterface.php';
require_once TWP_DIR_LIB . 'Akismet-API/Rzeka/Service/Akismet/Connector/Curl.php';

if ( class_exists( 'Axl_Testimonials_Widget_Premium_Antispam_Akismet' ) ) {
	return;
}


class Axl_Testimonials_Widget_Premium_Antispam_Akismet extends Axl_Testimonials_Widget_Premium_Antispam_Base {
	/**
	 *
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public function run_tests() {
		if ( empty( $this->formatted ) ) {
			$this->format_input();
		}

		$site_url        = network_site_url();
		$akismet_api_key = tw_get_option( 'akismet_api_key' );

		$connector = new Rzeka\Service\Akismet\Connector\Curl();
		$akismet   = new Rzeka\Service\Akismet( $connector );

		$akismet->keyCheck( $akismet_api_key, $site_url );

		$check = array(
			'comment_type' => Axl_Testimonials_Widget::PT,
			'comment_author' => $this->formatted['title'],
			'comment_author_email' => $this->formatted['email'],
			'comment_author_url' => $this->formatted['url'],
			'comment_content' => $this->formatted['body'],
		);

		if ( ! empty( $this->formatted['permalink'] ) ) {
			$check['permalink'] = $this->formatted['permalink'];
		}

		if ( $akismet->check( $check ) ) {
			$this->valid = false;
		} else {
			$this->valid = true;
		}

		if ( ! empty( $_REQUEST['testmode'] ) ) {
			// @codingStandardsIgnoreStart
			echo '<div class="testimonials-widget-premium-form debug">';
			echo "Akismet: isCommentSpam\n<br />";
			var_dump( ! $this->valid );
			echo '</div>';
			// @codingStandardsIgnoreEnd
		}
	}
}

?>
