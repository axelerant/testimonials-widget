<?php
/*
 * This class is based upon plugin Antispam Bee, http://antispambee.com.
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

if ( class_exists( 'Axl_Testimonials_Widget_Premium_Antispam_Bee_Base' ) ) {
	return;
}


class Axl_Testimonials_Widget_Premium_Antispam_Bee_Base {
	/**
	 * Prüfung und Rückgabe eines Array-Keys
	 *
	 * @since   2.4.2
	 * @change  2.4.2
	 *
	 * @param array   $array Array mit Werten
	 * @param string  $key   Name des Keys
	 * @return  mixed           Wert des angeforderten Keys
	 */


	public static function get_key( $array, $key ) {
		if ( empty( $array ) or empty( $key ) or empty( $array[ $key ] ) ) {
			return null;
		}

		return $array[ $key ];
	}


	//###########################
	//#####  SPAMPRÜFUNG  #######
	//###########################



	/**
	 * Prüfung den Kommentar
	 *
	 * @since   2.4
	 * @change  2.5.6
	 *
	 * @param array   $comment Daten des Kommentars
	 * @return  array            Array mit dem Verdachtsgrund [optional]
	 *
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function verify_comment_request( $comment ) {
		/* IP */
		$ip = self::get_key( $_SERVER, 'REMOTE_ADDR' );

		/* Kommentarwerte */
		$url    = self::get_key( $comment, 'comment_author_url' );
		$body   = self::get_key( $comment, 'comment_content' );
		$email  = self::get_key( $comment, 'comment_author_email' );
		$author = self::get_key( $comment, 'comment_author' );

		/* Leere Werte ? */
		if ( empty( $body ) ) {
			return array(
				'reason' => 'empty',
			);
		}

		/* IP? */
		if ( empty( $ip ) or ( function_exists( 'filter_var' ) && ! filter_var( $ip, FILTER_VALIDATE_IP ) ) ) {
			return array(
				'reason' => 'empty',
			);
		}

		/* Optionen */
		$options = tw_get_options();

		/* BBCode Spam */
		if ( ! empty( $options['bbcode_check'] ) && self::_is_bbcode_spam( $body ) ) {
			return array(
				'reason' => 'bbcode',
			);
		}

		/* Erweiterter Schutz */
		if ( ! empty( $options['advanced_check'] ) && self::_is_fake_ip( $ip ) ) {
			return array(
				'reason' => 'server',
			);
		}

		/* Regexp für Spam */
		if ( ! empty( $options['regexp_check'] ) && self::_is_regexp_spam(
				array(
					'ip'  => $ip,
					'host'  => parse_url( $url, PHP_URL_HOST ),
					'body'  => $body,
					'email'  => $email,
					'author' => $author,
				)
			) ) {
			return array(
				'reason' => 'regexp',
			);
		}

		/* IP im lokalen Spam */
		if ( ! empty( $options['spam_ip'] ) && self::_is_db_spam( $ip, $url, $email ) ) {
			return array(
				'reason' => 'localdb',
			);
		}

		/* DNSBL Spam */
		if ( ! empty( $options['dnsbl_check'] ) && self::_is_dnsbl_spam( $ip ) ) {
			return array(
				'reason' => 'dnsbl',
			);
		}

		/* Country Code prüfen */
		if ( ! empty( $options['country_code'] ) && self::_is_country_spam( $ip ) ) {
			return array(
				'reason' => 'country',
			);
		}

		/* Translate API */
		if ( ! empty( $options['translate_api'] ) && self::_is_lang_spam( $body ) ) {
			return array(
				'reason' => 'lang',
			);
		}
	}


	/**
	 * Anwendung von Regexp, auch benutzerdefiniert
	 *
	 * @since   2.5.2
	 * @change  2.5.6
	 *
	 * @param array   $comment Array mit Kommentardaten
	 * @return  boolean          TRUE bei verdächtigem Kommentar
	 */
	private static function _is_regexp_spam( $comment ) {
		/* Felder */
		$fields = array(
			'ip',
			'host',
			'body',
			'email',
			'author',
		);

		/* Regexp */
		$patterns = array(
			0 => array(
				'host' => '^(www\.)?\d+\w+\.com$',
				'body' => '^\w+\s\d+$',
				'email' => '@gmail.com$',
			),
			1 => array(
				'body' => '\<\!.+?mfunc.+?\>',
			),
		);

		/* Spammy author */
		if ( $quoted_author = preg_quote( $comment['author'], '/' ) ) {
			$patterns[] = array(
				'body' => sprintf( '<a.+?>%s<\/a>$', $quoted_author ),
			);
			$patterns[] = array(
				'body' => sprintf( '%s https?:.+?$', $quoted_author ),
			);
			$patterns[] = array(
				'email'  => '@gmail.com$',
				'author' => '^[a-z0-9-\.]+\.[a-z]{2,6}$',
				'host'  => sprintf( '^%s$', $quoted_author ),
			);
		}

		/* Hook */
		$patterns = apply_filters( 'antispam_bee_patterns', $patterns );

		/* Leer? */
		if ( ! $patterns ) {
			return false;
		}

		/* Ausdrücke loopen */
		foreach ( $patterns as $pattern ) {
			$hits = array();

			/* Felder loopen */
			foreach ( $pattern as $field => $regexp ) {
				/* Empty value? */
				if ( empty( $field ) or ! in_array( $field, $fields ) or empty( $regexp ) ) {
					continue;
				}

				/* Ignore non utf-8 chars */
				$comment[ $field ] = ( function_exists( 'iconv' ) ? iconv( 'utf-8', 'utf-8//TRANSLIT', $comment[ $field ] ) : $comment[ $field ] );

				/* Empty value? */
				if ( empty( $comment[ $field ] ) ) {
					continue;
				}

				if ( preg_match( '|' .$regexp. '|isu', $comment[ $field ] ) ) {
					$hits[ $field ] = true;
				}
			}

			if ( count( $hits ) === count( $pattern ) ) {
				return true;
			}
		}

		return false;
	}


	/**
	 * Prüfung eines Kommentars auf seine Existenz im lokalen Spam
	 *
	 * @since   2.0.0
	 * @change  2.5.4
	 *
	 * @param string  $ip    Kommentar-IP
	 * @param string  $url   Kommentar-URL [optional]
	 * @param string  $email Kommentar-Email [optional]
	 * @return  boolean          TRUE bei verdächtigem Kommentar
	 */
	private static function _is_db_spam( $ip, $url = '', $email = '' ) {
		global $wpdb;

		$filter = array( '`comment_author_IP` = %s' );
		$params = array( $ip );

		/* URL abgleichen */
		if ( ! empty( $url ) ) {
			$filter[] = '`comment_author_url` = %s';
			$params[] = $url;
		}

		/* E-Mail abgleichen */
		if ( ! empty( $email ) ) {
			$filter[] = '`comment_author_email` = %s';
			$params[] = $email;
		}

		/* Query ausführen */
		$result = $wpdb->get_var(
			$wpdb->prepare(
				sprintf(
					"SELECT `comment_ID` FROM `$wpdb->comments` WHERE `comment_approved` = 'spam' AND (%s) LIMIT 1",
					implode( ' OR ', $filter )
				),
				$params
			)
		);

		return ! empty( $result );
	}


	/**
	 * Prüfung auf erlaubten Ländercodes
	 *
	 * @since   0.1
	 * @change  2.5.1
	 *
	 * @param string  $ip IP-Adresse
	 * @return  boolean       TRUE bei unerwünschten Ländercodes
	 */
	private static function _is_country_spam( $ip ) {
		/* Optionen */
		$options = tw_get_options();

		/* White & Black */
		$white = preg_split(
			'/ /',
			$options['country_white'],
			-1,
			PREG_SPLIT_NO_EMPTY
		);
		$black = preg_split(
			'/ /',
			$options['country_black'],
			-1,
			PREG_SPLIT_NO_EMPTY
		);

		/* Leere Listen? */
		if ( empty( $white ) && empty( $black ) ) {
			return false;
		}

		/* IP abfragen */
		$response = wp_remote_get(
			esc_url_raw(
				sprintf(
					'https://geoip.maxmind.com/a?l=%s&i=%s',
					strrev( '1Lbn0ZsL08e1' ),
					self::_anonymize_ip( $ip )
				),
				'https'
			)
		);

		/* Fehler? */
		if ( is_wp_error( $response ) ) {
			return false;
		}

		/* Land auslesen */
		$country = wp_remote_retrieve_body( $response );

		/* Kein Land? */
		if ( empty( $country ) ) {
			return false;
		}

		/* Blacklist */
		if ( ! empty( $black ) ) {
			return in_array( $country, $black ) ? true : false;
		}

		/* Whitelist */
		return in_array( $country, $white ) ? false : true;
	}


	/**
	 * Prüfung auf DNSBL Spam
	 *
	 * @since   2.4.5
	 * @change  2.4.5
	 *
	 * @param string  $ip IP-Adresse
	 * @return  boolean       TRUE bei gemeldeter IP
	 */
	private static function _is_dnsbl_spam( $ip ) {
		/* Start request */
		$response = wp_remote_get(
			esc_url_raw(
				sprintf(
					'http://www.stopforumspam.com/api?ip=%s&f=json',
					$ip
				),
				'http'
			)
		);

		/* Response error? */
		if ( is_wp_error( $response ) ) {
			return false;
		}

		/* Get JSON */
		$json = wp_remote_retrieve_body( $response );

		/* Decode JSON */
		$result = json_decode( $json );

		/* Empty data */
		if ( empty( $result->success ) ) {
			return false;
		}

		/* Return status */
		return (bool) $result->ip->appears;
	}


	/**
	 * Prüfung auf BBCode Spam
	 *
	 * @since   2.5.1
	 * @change  2.5.1
	 *
	 * @param string  $body Inhalt eines Kommentars
	 * @return  boolean         TRUE bei BBCode im Inhalt
	 */
	private static function _is_bbcode_spam( $body ) {
		return (bool) preg_match( '/\[url[=\]].*\[\/url\]/is', $body );
	}


	/**
	 * Prüfung auf eine gefälschte IP
	 *
	 * @since   2.0
	 * @change  2.5.1
	 *
	 * @param string  $ip   IP-Adresse
	 * @param string  $host Host [optional]
	 * @return  boolean         TRUE bei gefälschter IP
	 */
	private static function _is_fake_ip( $ip, $host = false ) {
		/* Remote Host */
		$hostbyip = gethostbyaddr( $ip );

		/* IPv6 */
		if ( ! self::_is_ipv4( $ip ) ) {
			return $ip != $hostbyip;
		}

		/* IPv4 / Kommentar */
		if ( empty( $host ) ) {
			$found = strpos(
				$ip,
				self::_cut_ip(
					gethostbyname( $hostbyip )
				)
			);

			/* IPv4 / Trackback */
		} else {
			/* IP-Vergleich */
			if ( $hostbyip == $ip ) {
				return true;
			}

			/* Treffer suchen */
			$found = strpos(
				$ip,
				self::_cut_ip(
					gethostbyname( $host )
				)
			);
		}

		return false === $found;
	}


	/**
	 * Prüfung auf unerwünschte Sprachen
	 *
	 * @since   2.0
	 * @change  2.4.2
	 *
	 * @param string  $content Inhalt des Kommentars
	 * @return  boolean            TRUE bei Spam
	 */
	private static function _is_lang_spam( $content ) {
		/* Init */
		$lang = tw_get_option( 'translate_lang' );

		/* Formatieren */
		$content = wp_strip_all_tags( $content );

		/* Keine Daten? */
		if ( empty( $lang ) or empty( $content ) ) {
			return false;
		}

		/* Formatieren */
		$content = rawurlencode(
			( function_exists( 'mb_substr' ) ? mb_substr( $content, 0, 200 ) : substr( $content, 0, 200 ) )
		);

		/* IP abfragen */
		$response = wp_remote_get(
			esc_url_raw(
				sprintf(
					'http://translate.google.com/translate_a/t?client=x&text=%s',
					$content
				),
				'http'
			)
		);

		/* Fehler? */
		if ( is_wp_error( $response ) ) {
			return false;
		}

		/* Parsen */
		preg_match(
			'/"src":"(\\D{2})"/',
			wp_remote_retrieve_body( $response ),
			$matches
		);

		/* Fehler? */
		if ( empty( $matches[1] ) ) {
			return false;
		}

		return strtolower( $matches[1] ) != $lang;
	}


	/**
	 * Kürzung der IP-Adressen
	 *
	 * @since   0.1
	 * @change  2.5.1
	 *
	 * @param string  $ip      Original IP
	 * @param boolean $cut_end Kürzen vom Ende?
	 * @return  string             Gekürzte IP
	 */
	private static function _cut_ip( $ip, $cut_end = true ) {
		/* Trenner */
		$separator = ( self::_is_ipv4( $ip ) ? '.' : ':' );

		return str_replace(
			( $cut_end ? strrchr( $ip, $separator ) : strstr( $ip, $separator ) ),
			'',
			$ip
		);
	}


	/**
	 * Anonymisierung der IP-Adressen
	 *
	 * @since   2.5.1
	 * @change  2.5.1
	 *
	 * @param string  $ip Original IP
	 * @return  string       Anonyme IP
	 */
	private static function _anonymize_ip( $ip ) {
		if ( self::_is_ipv4( $ip ) ) {
			return self::_cut_ip( $ip ). '.0';
		}

		return self::_cut_ip( $ip, false ). ':0:0:0:0:0:0:0';
	}


	/**
	 * Dreht die IP-Adresse
	 *
	 * @since   2.4.5
	 * @change  2.4.5
	 *
	 * @param string  $ip IP-Adresse
	 * @return  string        Gedrehte IP-Adresse
	 *
	 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
	 */
	private static function _reverse_ip( $ip ) {
		return implode(
			'.',
			array_reverse(
				explode(
					'.',
					$ip
				)
			)
		);
	}


	/**
	 * Prüfung auf eine IPv4-Adresse
	 *
	 * @since   2.4
	 * @change  2.4
	 *
	 * @param string  $ip Zu prüfende IP
	 * @return  integer       Anzahl der Treffer
	 */
	private static function _is_ipv4( $ip ) {
		return preg_match( '/^\d{1,3}(\.\d{1,3}){3,3}$/', $ip );
	}
}


?>
