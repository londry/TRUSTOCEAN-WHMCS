<?php
/**
 * Sanitizing - By Reference
 *
 * Functions for sanitizing data.
 *
 * @package blobfolio/common
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\common\ref;

use blobfolio\common\constants;
use blobfolio\common\data;
use blobfolio\common\dom;
use blobfolio\common\file as v_file;
use blobfolio\common\mb as v_mb;
use blobfolio\common\sanitize as v_sanitize;
use blobfolio\domain\domain;

// Check for MB functions once.
if (! \defined('BLOBCOMMON_SANITIZE_HAS_MB')) {
	\define(
		'BLOBCOMMON_SANITIZE_HAS_MB',
		(
			\function_exists('mb_check_encoding') &&
			\function_exists('mb_strlen')
		)
	);
}

class sanitize {

	/**
	 * Strip Accents
	 *
	 * @param string $str String.
	 * @return void Nothing.
	 */
	public static function accents(&$str) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::accents($str[$k]);
			}
		}
		else {
			cast::string($str, true);

			if (\preg_match('/[\x80-\xff]/', $str)) {
				$str = \strtr($str, constants::ACCENT_CHARS);

				// Remove combining accents.
				$str = \str_replace(
					array(
						"\xCC\x80",
						"\xCC\x81",
					),
					'',
					$str
				);
			}
		}
	}

	/**
	 * Attribute Value
	 *
	 * This will decode entities, strip control characters, and trim
	 * outside whitespace.
	 *
	 * Note: this should not be used for safe insertion into HTML. For
	 * that, use the html() function.
	 *
	 * @param string $str String.
	 * @return void Nothing.
	 */
	public static function attribute_value(&$str='') {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::attribute_value($str[$k]);
			}
		}
		else {
			cast::string($str, true);

			static::control_characters($str);
			format::decode_entities($str);

			// And trim the edges while we're here.
			mb::trim($str);
		}
	}

	/**
	 * CA Postal Code
	 *
	 * @param string $str Postal Code.
	 * @return void Nothing.
	 */
	public static function ca_postal_code(&$str) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::ca_postal_code($str[$k]);
			}
		}
		else {
			// There's no point in checking if this is not a string.
			if (! \is_string($str)) {
				$str = '';
				return;
			}

			$str = \strtoupper($str);

			// Alphanumeric, minus D, F, I, O, Q or U.
			$str = \preg_replace('/[^A-CEGHJ-NPR-TV-Z\d]/', '', $str);

			// W and Z are not allowed in the first slot, otherwise it
			// just alternates between letters and numbers.
			if (! \preg_match('/^[A-VXY][\d][A-Z][\d][A-Z][\d]$/', $str)) {
				$str = '';
			}
			else {
				// If it looks good, add a space in the middle.
				$str = \substr($str, 0, 3) . ' ' . \substr($str, -3);
			}
		}
	}

	/**
	 * Credit Card
	 *
	 * @param string $ccnum Card number.
	 * @return bool True/false.
	 */
	public static function cc(&$ccnum) {
		if (! \is_string($ccnum)) {
			if (\is_numeric($ccnum)) {
				$ccnum = (string) $ccnum;
			}
			else {
				$ccnum = false;
				return false;
			}
		}

		// Digits only.
		$ccnum = \preg_replace('/[^\d]/', '', $ccnum);
		$str = $ccnum;

		if (! $ccnum) {
			$ccnum = false;
			return false;
		}

		// Different cards have different length requirements.
		switch ($ccnum[0]) {
			// Amex.
			case '3':
				if ((\strlen($ccnum) !== 15) || ! \preg_match('/3[47]/', $ccnum)) {
					$ccnum = false;
					return false;
				}
				break;
			// Visa.
			case '4':
				if (! \in_array(\strlen($ccnum), array(13, 16), true)) {
					$ccnum = false;
					return false;
				}
				break;
			// MC.
			case '5':
				if ((\strlen($ccnum) !== 16) || ! \preg_match('/5[1-5]/', $ccnum)) {
					$ccnum = false;
					return false;
				}
				break;
			// Disc.
			case '6':
				if (
					(\strlen($ccnum) !== 16) ||
					(0 !== \strpos($ccnum, '6011'))
				) {
					$ccnum = false;
					return false;
				}
				break;
			// There is nothing else...
			default:
				$ccnum = false;
				return false;
		}

		// MOD10 checks.
		$dig = \str_split($ccnum);
		$numdig = \count($dig);
		$j = 0;
		for ($i = ($numdig - 2); $i >= 0; $i -= 2) {
			$dbl[$j] = $dig[$i] * 2;
			++$j;
		}
		$dblsz = \count($dbl);
		$validate = 0;
		for ($i = 0; $i < $dblsz; ++$i) {
			$add = \str_split($dbl[$i]);
			for ($j = 0; $j < \count($add); ++$j) {
				$validate += $add[$j];
			}
			$add = '';
		}
		for ($i = ($numdig - 1); $i >= 0; $i -= 2) {
			$validate += $dig[$i];
		}

		if (\intval(\substr($validate, -1)) === 0) {
			$ccnum = $str;
		}
		else {
			$ccnum = false;
		}

		return true;
	}

	/**
	 * Control Characters
	 *
	 * @param string $str String.
	 * @return void Nothing.
	 */
	public static function control_characters(&$str) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::control_characters($str[$k]);
			}
		}
		else {
			cast::string($str, true);

			$str = \preg_replace(
				array(
					'/[\x00-\x08\x0B\x0C\x0E-\x1F]/',
					'/\\\\+0+/',
				),
				'',
				$str
			);
		}
	}

	/**
	 * Country
	 *
	 * @param string $str Country.
	 * @return void Nothing.
	 */
	public static function country(&$str) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::country($str[$k]);
			}
		}
		else {
			cast::string($str, true);

			static::whitespace($str, 0);
			mb::strtoupper($str, false);
			if (! isset(constants::COUNTRIES[$str])) {
				// Maybe a name?
				$found = false;
				foreach (constants::COUNTRIES as $k=>$v) {
					if (v_mb::strtoupper($v['name']) === $str) {
						$str = $k;
						$found = true;
						break;
					}
				}

				// Check for a few variations.
				if (! $found) {
					$map = array(
						'BRITAIN'=>'GB',
						'GREAT BRITAIN'=>'GB',
						'U. S. A.'=>'US',
						'U. S. S. R.'=>'RU',
						'U.S.A.'=>'US',
						'U.S.S.R.'=>'RU',
						'UNITED STATES OF AMERICA'=>'US',
						'UNITED STATES'=>'US',
						'USSR'=>'RU',
					);
					if (isset($map[$str])) {
						$str = $map[$str];
						$found = true;
					}
				}

				if (! $found) {
					$str = '';
				}
			}
		}
	}

	/**
	 * CSV Cell Data
	 *
	 * @param string $str String.
	 * @return void Nothing.
	 */
	public static function csv(&$str='') {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::csv($str[$k]);
			}
		}
		else {
			cast::string($str, true);

			static::quotes($str);
			static::whitespace($str, 0);

			// Strip existing double quotes.
			while (false !== \strpos($str, '""')) {
				$str = \str_replace('""', '"', $str);
			}

			// Double quotes.
			$str = \str_replace('"', '""', $str);
		}
	}

	/**
	 * Datetime
	 *
	 * @param string|int $str Date or timestamp.
	 * @return void Nothing.
	 */
	public static function datetime(&$str) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::datetime($str[$k]);
			}
		}
		else {
			// We don't need fancy casting.
			if (! \is_string($str)) {
				if (\is_numeric($str)) {
					$str = (string) $str;
				}
				else {
					$str = '0000-00-00 00:00:00';
					return;
				}
			}

			// Could be a timestamp.
			if (\preg_match('/^\d{9,}$/', $str)) {
				$str = \date('Y-m-d H:i:s', \intval($str));
				return;
			}

			$str = \trim($str);
			if (
				! $str ||
				(0 === \strpos($str, '0000-00-00')) ||
				(false === ($str = \strtotime($str)))
			) {
				$str = '0000-00-00 00:00:00';
				return;
			}

			// Make it!
			$str = \date('Y-m-d H:i:s', $str);
		}
	}

	/**
	 * Date
	 *
	 * @param string|int $str Date or timestamp.
	 * @return void Nothing.
	 */
	public static function date(&$str) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::date($str[$k]);
			}
		}
		else {
			static::datetime($str);
			$str = \substr($str, 0, 10);
		}
	}

	/**
	 * Domain Name.
	 *
	 * This locates the domain name portion of a URL,
	 * removes leading "www." subdomains, and ignores
	 * IP addresses.
	 *
	 * @param string $str Domain.
	 * @param bool $unicode Unicode.
	 * @return bool True/false.
	 */
	public static function domain(&$str='', bool $unicode=false) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::domain($str[$k], $unicode);
			}
		}
		else {
			$host = new domain($str, true);
			if ($host->is_fqdn() && ! $host->is_ip()) {
				$str = $host->get_host($unicode);
			}
			else {
				$str = '';
				return false;
			}
		}

		return true;
	}

	/**
	 * EAN13
	 *
	 * Almost exactly like UPC, but not quite.
	 *
	 * @param string $str String.
	 * @param bool $formatted Formatted.
	 * @return bool True/false.
	 */
	public static function ean(&$str, bool $formatted=false) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::ean($str[$k], $formatted);
			}
		}
		else {
			if (! \is_string($str)) {
				if (! \is_numeric($str)) {
					$str = '';
					return false;
				}
				$str = (string) $str;
			}

			// Numbers only.
			$str = \preg_replace('/[^\d]/', '', $str);
			$str = \str_pad($str, 13, '0', \STR_PAD_LEFT);

			// Trim leading zeroes if it is too long.
			while (isset($str[13]) && (0 === \strpos($str, '0'))) {
				$str = \substr($str, 1);
			}

			if (\strlen($str) !== 13 || ('0000000000000' === $str)) {
				$str = '';
				return false;
			}

			// Try to pad it.
			while (! static::gtin($str) && \strlen($str) <= 18) {
				$str = "0$str";
			}
			if (! static::gtin($str)) {
				$str = '';
				return false;
			}

			// Last thing, format?
			if ($formatted) {
				$str = \preg_replace('/^(\d{1})(\d{6})(\d{6})$/', '$1-$2-$3', $str);
			}
		}

		return true;
	}

	/**
	 * Email
	 *
	 * Converts the email to lowercase, strips
	 * invalid characters, quotes, and apostrophes.
	 *
	 * @param string $str Email.
	 * @return void Nothing.
	 */
	public static function email(&$str) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::email($str[$k]);
			}
		}
		else {
			cast::string($str, true);

			static::quotes($str);
			mb::strtolower($str, false);

			// Strip comments.
			$str = \preg_replace('/\([^)]*\)/u', '', $str);

			// For backward-compatibility, strip quotes now.
			$str = \str_replace(array("'", '"'), '', $str);

			// Sanitize by part.
			if (\substr_count($str, '@') === 1) {
				$parts = \explode('@', $str);

				// Sanitize local part.
				$parts[0] = \preg_replace('/[^\.a-z0-9\!#\$%&\*\+\-\=\?_~]/u', '', $parts[0]);
				$parts[0] = \ltrim($parts[0], '.');
				$parts[0] = \rtrim($parts[0], '.');

				if (! $parts[0]) {
					$str = '';
					return;
				}

				// Sanitize host.
				$domain = new domain($parts[1]);
				if (! $domain->is_valid() || ! $domain->is_fqdn() || $domain->is_ip()) {
					$str = '';
					return;
				}
				$parts[1] = (string) $domain;

				$str = \implode('@', $parts);
			}
			else {
				$str = '';
				return;
			}
		}
	}

	/**
	 * File Extension
	 *
	 * @param string $str Extension.
	 * @return void Nothing.
	 */
	public static function file_extension(&$str) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::file_extension($str[$k]);
			}
		}
		else {
			cast::string($str, true);

			mb::strtolower($str, false);
			$str = \preg_replace('/\s/u', '', $str);
			$str = \ltrim($str, '*.');
		}
	}

	/**
	 * Validate GTIN
	 *
	 * This validates the GTIN checksum for`EAN, UPC, etc. The check
	 * character is expected to be lopped on the end.
	 *
	 * @param string $str String.
	 * @return bool True/false.
	 */
	protected static function gtin(string $str) {
		$str = \preg_replace('/[^\d]/', '', $str);
		$code = \str_split(\substr($str, 0, -1));
		$check = (int) \substr($str, -1);

		$total = 0;
		for ($x = \count($code) - 1; $x >= 0; --$x) {
			$total += (($x % 2) * 2 + 1 ) * $code[$x];
		}
		$checksum = (10 - ($total % 10));

		return $checksum === $check;
	}

	/**
	 * HTML
	 *
	 * @param string $str HTML.
	 * @return void Nothing.
	 */
	public static function html(&$str=null) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::html($str[$k]);
			}
		}
		else {
			cast::string($str, true);

			$str = \htmlspecialchars($str, \ENT_QUOTES | \ENT_HTML5, 'UTF-8');
		}
	}

	/**
	 * Hostname
	 *
	 * @param string $domain Hostname.
	 * @param bool $www Keep leading www.
	 * @param bool $unicode Unicode.
	 * @return bool True/false.
	 */
	public static function hostname(&$domain, bool $www=false, bool $unicode=false) {
		cast::string($domain, true);

		$host = new domain($domain, ! $www);
		if (! $host->is_valid()) {
			$domain = false;
			return false;
		}

		$domain = $host->get_host($unicode);
		return true;
	}

	/**
	 * IP Address
	 *
	 * @param string $str IP.
	 * @param bool $restricted Allow private/restricted values.
	 * @param bool $condense Condense IPv6.
	 * @return void Nothing.
	 */
	public static function ip(&$str='', bool $restricted=false, bool $condense=true) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::ip($str[$k], $restricted, $condense);
			}
		}
		else {
			// Don't need to fancy cast.
			if (! \is_string($str)) {
				$str = '';
				return;
			}

			// Start by getting rid of obviously bad data.
			$str = \preg_replace('/[^\d\.\:a-f]/', '', \strtolower($str));

			// IPv6 might be encased in brackets.
			if (\preg_match('/^\[[\d\.\:a-f]+\]$/', $str)) {
				$str = \substr($str, 1, -1);
			}

			// Turn IPv6-ized 4s back into IPv4.
			if ((0 === \strpos($str, '::')) && (false !== \strpos($str, '.'))) {
				$str = \substr($str, 2);
			}

			// IPv6.
			if (\filter_var($str, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6)) {
				// Condense it?
				if ($condense) {
					$str = \inet_ntop(\inet_pton($str));
				}
				// Expand.
				else {
					$hex = \unpack('H*hex', \inet_pton($str));
					$str = \substr(\preg_replace('/([a-f\d]{4})/', '$1:', $hex['hex']), 0, -1);
				}
			}
			elseif (! \filter_var($str, \FILTER_VALIDATE_IP)) {
				$str = '';
			}

			if (
				! $restricted &&
				$str &&
				! \filter_var($str, \FILTER_VALIDATE_IP, \FILTER_FLAG_NO_PRIV_RANGE | \FILTER_FLAG_NO_RES_RANGE)
			) {
				$str = '';
			}
		}
	}

	/**
	 * IRI Value
	 *
	 * @param string $str IRI value.
	 * @param array $protocols Allowed protocols.
	 * @param array $domains Allowed domains.
	 * @return void Nothing.
	 */
	public static function iri_value(&$str='', $protocols=null, $domains=null) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::iri_value($str[$k], $protocols, $domains);
			}
		}
		else {
			cast::string($str, true);
			cast::array($protocols);
			cast::array($domains);

			static::attribute_value($str);

			$allowed_protocols = \array_merge(constants::SVG_WHITELIST_PROTOCOLS, $protocols);
			mb::strtolower($allowed_protocols, false);
			$allowed_protocols = \array_map('trim', $allowed_protocols);
			$allowed_protocols = \array_filter($allowed_protocols, 'strlen');
			$allowed_protocols = \array_unique($allowed_protocols);
			\sort($allowed_protocols);

			$allowed_domains = \array_merge(constants::SVG_WHITELIST_DOMAINS, $domains);
			static::domain($allowed_domains);
			$allowed_domains = \array_filter($allowed_domains, 'strlen');
			$allowed_domains = \array_unique($allowed_domains);
			\sort($allowed_domains);

			// Assign a protocol.
			$str = \preg_replace('/^\/\//', 'https://', $str);

			// Remove newlines.
			$str = \preg_replace('/\v/u', '', $str);

			// Check protocols.
			$test = \preg_replace('/\s/', '', $str);
			mb::strtolower($test, true);
			if (false !== \strpos($test, ':')) {
				$test = \explode(':', $test);
				if (! \in_array($test[0], $allowed_protocols, true)) {
					$str = '';
					return;
				}
			}

			// Is this at least a URLish thing?
			if (\filter_var($str, \FILTER_SANITIZE_URL) !== $str) {
				$str = '';
				return;
			}

			// Check the domain, if applicable.
			if (\preg_match('/^[\w\d]+:\/\//i', $str)) {
				$domain = v_sanitize::domain($str);
				if ($domain && ! \in_array($domain, $allowed_domains, true)) {
					$str = '';
				}
			}
		}
	}

	/**
	 * ISBN
	 *
	 * Validate an ISBN 10 or 13.
	 *
	 * @see {https://www.isbn-international.org/export_rangemessage.xml}
	 *
	 * @param string $str String.
	 * @return bool True/false.
	 */
	public static function isbn(&$str) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::isbn($str[$k]);
			}
		}
		else {
			// No fancy casting needed.
			if (! \is_string($str)) {
				if (\is_numeric($str)) {
					$str = (string) $str;
				}
				else {
					$str = '';
					return false;
				}
			}

			$str = \strtoupper($str);
			$str = \preg_replace('/[^\dX]/', '', $str);

			// Zero-pad.
			if (! isset($str[10])) {
				$str = \str_pad($str, 10, '0', \STR_PAD_LEFT);
			}
			elseif (! isset($str[12])) {
				$str = \preg_replace('/[^\d]/', '', $str);
				$str = \str_pad($str, 13, '0', \STR_PAD_LEFT);
			}

			if (
				('0000000000' === $str) ||
				('0000000000000' === $str) ||
				isset($str[13])
			) {
				$str = '';
				return false;
			}

			// Validate a 10.
			if (\strlen($str) === 10) {
				$checksum = 0;
				for ($x = 0; $x < 9; ++$x) {
					if ('X' === $str[$x]) {
						$checksum += 10 * (10 - $x);
					}
					else {
						$checksum += \intval($str[$x]) * (10 - $x);
					}
				}

				$checksum = 11 - $checksum % 11;
				if (10 === $checksum) {
					$checksum = 'X';
				}
				elseif (11 === $checksum) {
					$checksum = 0;
				}
				else {
					$checksum = (int) $checksum;
				}

				$check = ('X' === $str[9]) ? 'X' : \intval($str[9]);
				if ($check !== $checksum) {
					$str = '';
					return false;
				}
			}
			// Validate a 13.
			else {
				if (! static::gtin($str)) {
					$str = '';
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * JS Variable
	 *
	 * @param string $str String.
	 * @param string $quote Quote type.
	 * @return void Nothing.
	 */
	public static function js(&$str='', $quote="'") {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::js($str[$k], $quote);
			}
		}
		else {
			cast::string($str, true);

			sanitize::quotes($str);
			sanitize::whitespace($str, 0);

			// Escape slashes, e.g. </script> -> <\/script>.
			$str = \str_replace('/', '\\/', $str);

			if ("'" === $quote) {
				$str = \str_replace("'", "\'", $str);
			}
			elseif ('"' === $quote) {
				$str = \str_replace('"', '\"', $str);
			}
		}
	}

	/**
	 * IANA MIME Type
	 *
	 * @param string $str MIME.
	 * @return bool True/false.
	 */
	public static function mime(&$str) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::mime($str[$k]);
			}
		}
		else {
			// Don't need to be fancy.
			if (! \is_string($str)) {
				if (\is_numeric($str)) {
					$str = (string) $str;
				}
				else {
					$str = '';
					return false;
				}
			}

			$str = \strtolower($str);
			$str = \preg_replace('/[^-+*.a-z0-9\/]/', '', $str);
		}

		return true;
	}

	/**
	 * (Person's) Name
	 *
	 * A bit of a fool's goal, but this will attempt to
	 * strip out obviously bad data and convert to title
	 * casing.
	 *
	 * @param string $str Name.
	 * @return void Nothing.
	 */
	public static function name(&$str='') {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::name($str[$k]);
			}
		}
		else {
			cast::string($str, true);

			static::quotes($str);
			static::whitespace($str, 0);
			$str = \preg_replace('/[^\p{L}\p{Zs}\p{Pd}\d\'\"\,\.]/u', '', $str);
			static::whitespace($str, 0);
			mb::ucwords($str, false);
		}
	}

	/**
	 * Password
	 *
	 * This simply removes excess whitespace and
	 * non-printable characters, which are likely
	 * only present because of user error.
	 *
	 * @param string $str Password.
	 * @return void Nothing.
	 */
	public static function password(&$str='') {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::password($str[$k]);
			}
		}
		else {
			cast::string($str, true);

			static::printable($str);
			static::whitespace($str, 0);
		}
	}

	/**
	 * Printable
	 *
	 * Remove non-printable characters (except spaces).
	 *
	 * @param string $str String.
	 * @return void Nothing.
	 */
	public static function printable(&$str) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::printable($str[$k]);
			}
		}
		else {
			cast::string($str, true);

			// Stripe zero-width chars.
			$str = \preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $str);

			// Make whitespace consistent.
			$str = \str_replace("\r\n", "\n", $str);
			$str = \str_replace("\r", "\n", $str);
			$str = \preg_replace_callback(
				'/[^[:print:]]/u',
				function($match) {
					// Allow newlines and tabs, in case the OS considers
					// those non-printable.
					if (
						("\n" === $match[0]) ||
						("\t" === $match[0])
					) {
						return $match[0];
					}

					// Ignore everything else.
					return '';
				},
				$str
			);
		}
	}

	/**
	 * Canadian Province
	 *
	 * @param string $str Province.
	 * @return void Nothing.
	 */
	public static function province(&$str) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::province($str[$k]);
			}
		}
		else {
			cast::string($str, true);

			static::whitespace($str, 0);
			$str = \strtoupper($str);

			if (! isset(constants::PROVINCES[$str])) {
				if (false === ($str = data::array_isearch($str, constants::PROVINCES, true))) {
					$str = '';
				}
			}
		}
	}

	/**
	 * Quotes
	 *
	 * Replace those damn curly quotes with the straight
	 * ones Athena intended!
	 *
	 * @param string $str String.
	 * @return void Nothing.
	 */
	public static function quotes(&$str) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::quotes($str[$k]);
			}
		}
		else {
			cast::string($str, true);

			$from = \array_keys(constants::QUOTE_CHARS);
			$to = \array_values(constants::QUOTE_CHARS);
			$str = \str_replace($from, $to, $str);
		}
	}

	/**
	 * US State/Territory
	 *
	 * @param string $str State.
	 * @return void Nothing.
	 */
	public static function state(&$str) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::state($str[$k]);
			}
		}
		else {
			cast::string($str, true);

			static::whitespace($str, 0);
			$str = \strtoupper($str);

			if (! isset(constants::STATES[$str])) {
				if (false === ($str = data::array_isearch($str, constants::STATES, true))) {
					$str = '';
				}
			}
		}
	}

	/**
	 * Australian State/Territory
	 *
	 * @param string $str State.
	 * @return void Nothing.
	 */
	public static function au_state(&$str) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::au_state($str[$k]);
			}
		}
		else {
			cast::string($str, true);

			static::whitespace($str, 0);
			$str = \strtoupper($str);

			if (! isset(constants::STATES_AU[$str])) {
				if (false === ($str = data::array_isearch($str, constants::STATES_AU, true))) {
					$str = '';
				}
			}
		}
	}

	/**
	 * SVG
	 *
	 * @param string $str SVG code.
	 * @param array $tags Additional whitelist tags.
	 * @param array $attr Additional whitelist attributes.
	 * @param array $protocols Additional whitelist protocols.
	 * @param array $domains Additional whitelist domains.
	 * @return string SVG code.
	 */
	public static function svg(&$str='', $tags=null, $attr=null, $protocols=null, $domains=null) {
		// First, sanitize and build out function arguments!
		cast::string($str, true);

		$allowed_tags = constants::SVG_WHITELIST_TAGS;
		static::_merge_svg_args($allowed_tags, $tags);

		$allowed_attributes = constants::SVG_WHITELIST_ATTR;
		static::_merge_svg_args($allowed_attributes, $attr);

		$allowed_protocols = constants::SVG_WHITELIST_PROTOCOLS;
		static::_merge_svg_args($allowed_protocols, $protocols);

		$allowed_domains = constants::SVG_WHITELIST_DOMAINS;
		static::_merge_svg_args($allowed_domains, $domains);

		$iri_attributes = constants::SVG_IRI_ATTRIBUTES;

		// Load the SVG!
		if (false === ($dom = dom::load_svg($str))) {
			return false;
		}
		$svg = $dom->getElementsByTagName('svg');
		if (! $svg->length) {
			$str = '';
			return false;
		}
		$xpath = new \DOMXPath($dom);

		// Validate tags.
		$tags = $dom->getElementsByTagName('*');
		for ($x = $tags->length - 1; $x >= 0; $x--) {
			$tag = $tags->item($x);
			$tag_name = v_mb::strtolower($tag->tagName, false);

			// The tag might be namespaced (ns:tag). We'll allow it if
			// the tag is allowed.
			if (
				(false !== \strpos($tag_name, ':')) &&
				! \in_array($tag_name, $allowed_tags, true)
			) {
				$tag_name = \explode(':', $tag_name);
				$tag_name = $tag_name[1];
			}

			// Bad tag: not whitelisted.
			if (! \in_array($tag_name, $allowed_tags, true)) {
				dom::remove_node($tag);
				continue;
			}

			// If this is a <style> tag, we need to make sure all
			// entities are decoded. Thanks a lot, XML!
			if ('style' === $tag_name) {
				$style = \strip_tags(v_sanitize::attribute_value($tag->textContent));
				$tag->textContent = $style;
			}

			// Use XPath for attributes, as $tag->attributes will skip
			// anything namespaced. Note: We aren't focusing on actual
			// Namespaces here, that comes later.
			$attributes = $xpath->query('.//@*', $tag);
			for ($y = $attributes->length - 1; $y >= 0; $y--) {
				$attribute = $attributes->item($y);

				$attribute_name = v_mb::strtolower($attribute->nodeName, false);

				// Could be namespaced.
				if (
					! \in_array($attribute_name, $allowed_attributes, true) &&
					(false !== ($start = v_mb::strpos($attribute_name, ':')))
				) {
					$attribute_name = v_mb::substr($attribute_name, $start + 1, null);
				}

				// Bad attribute: not whitelisted. data-* is implicitly
				// whitelisted.
				if (
					(0 !== \strpos($attribute_name, 'data-')) &&
					! \in_array($attribute_name, $allowed_attributes, true)
				) {
					$tag->removeAttribute($attribute->nodeName);
					continue;
				}

				// Validate values.
				$attribute_value = v_sanitize::attribute_value($attribute->value);

				// Validate protocols.
				// IRI attributes get the full treatment.
				$iri = false;
				if (\in_array($attribute_name, $iri_attributes, true)) {
					$iri = true;
					static::iri_value($attribute_value, $allowed_protocols, $allowed_domains);
				}
				// For others, we are specifically interested in removing scripty bits.
				elseif (\preg_match('/(?:\w+script):/xi', $attribute_value)) {
					$attribute_value = '';
				}

				// Update it.
				if ($attribute_value !== $attribute->value) {
					if ($iri) {
						$tag->removeAttribute($attribute->nodeName);
					} else {
						$tag->setAttribute($attribute->nodeName, $attribute_value);
					}
				}
			}
		} // Each tag.

		// Once more through the tags to find namespaces.
		$tags = $dom->getElementsByTagName('*');
		for ($x = 0; $x < $tags->length; ++$x) {
			$tag = $tags->item($x);
			$nodes = $xpath->query('namespace::*', $tag);
			for ($y = 0; $y < $nodes->length; ++$y) {
				$node = $nodes->item($y);

				$node_name = v_mb::strtolower($node->nodeName, false);

				// Not xmlns?
				if (0 !== \strpos($node_name, 'xmlns:')) {
					dom::remove_namespace($dom, $node->localName);
					continue;
				}

				// Validate values.
				$node_value = v_sanitize::iri_value($node->nodeValue, $allowed_protocols, $allowed_domains);

				// Remove invalid.
				if (! $node_value) {
					dom::remove_namespace($dom, $node->localName);
				}
			}
		}

		// Back to string!
		$svg = dom::save_svg($dom);

		// One more task, sanitize CSS values (e.g. foo="url(...)").
		$svg = \preg_replace_callback(
			'/url\s*\((.*)\s*\)/Ui',
			function($match) use($allowed_protocols, $allowed_domains) {
				$str = v_sanitize::attribute_value($match[1]);

				// Strip quotes.
				$str = \ltrim($str, "'\"");
				$str = \rtrim($str, "'\"");

				static::iri_value($str, $allowed_protocols, $allowed_domains);

				if ($str) {
					return "url('$str')";
				}

				return 'none';
			},
			$svg
		);

		$str = $svg;

		return true;
	}

	/**
	 * Process SVG Args
	 *
	 * This code is just a little tedious.
	 *
	 * @param array $default Default.
	 * @param array $extra Extra.
	 * @return void Nothing.
	 */
	protected static function _merge_svg_args(&$default, $extra=null) {
		// We always want default to be an array.
		if (! \is_array($default)) {
			$default = array();
		}

		// If there are no extras, we're done.
		if (! \is_array($extra) || ! \count($extra)) {
			return;
		}

		// Loop.
		foreach ($extra as $v) {
			if (! \is_string($v)) {
				continue;
			}

			mb::strtolower($v);
			$v = \trim($v);

			// There aren't likely to be too many extra values passed,
			// otherwise we'd want to flip and test isset().
			if ($v && ! \in_array($default, $v, true)) {
				$default[] = $v;
			}
		}

		// Sort and we're done.
		\sort($default);
	}

	/**
	 * Timezone
	 *
	 * @param string $str Timezone.
	 * @return bool True/false.
	 */
	public static function timezone(&$str) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::timezone($str[$k]);
			}
		}
		elseif (! \in_array($str, constants::TIMEZONES, true)) {
			if (! \is_string($str)) {
				$str = 'UTC';
				return false;
			}

			$str = \preg_replace('/\s/u', '', \strtoupper($str));

			if (isset(constants::TIMEZONES[$str])) {
				$str = constants::TIMEZONES[$str];
			}
			else {
				$str = 'UTC';
			}
		}

		return true;
	}

	/**
	 * Confine a Value to a Range
	 *
	 * @param mixed $value Value.
	 * @param mixed $min Min.
	 * @param mixed $max Max.
	 * @return void Nothing.
	 */
	public static function to_range(&$value, $min=null, $max=null) {
		// Make sure min/max are in the right order.
		if (
			(null !== $min) &&
			(null !== $max) &&
			$min > $max
		) {
			data::switcheroo($min, $max);
		}

		// Recursive.
		if (\is_array($value)) {
			foreach ($value as $k=>$v) {
				static::to_range($v, $min, $max);
			}
		}
		else {
			$original = $value;

			try {
				if ((null !== $min) && $value < $min) {
					$value = $min;
				}
				if ((null !== $max) && $value > $max) {
					$value = $max;
				}
			} catch (\Throwable $e) {
				$value = $original;
			}
		}
	}

	/**
	 * UPC-A
	 *
	 * @param string $str String.
	 * @param bool $formatted Formatted.
	 * @return bool True/false.
	 */
	public static function upc(&$str, bool $formatted=false) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::upc($str[$k], $formatted);
			}
		}
		else {
			// No fancy casting needed.
			if (! \is_string($str)) {
				if (\is_numeric($str)) {
					$str = (string) $str;
				}
				else {
					$str = '';
					return false;
				}
			}

			$str = \preg_replace('/[^\d]/', '', $str);
			$str = \str_pad($str, 12, '0', \STR_PAD_LEFT);

			// Trim leading zeroes if it is too long.
			while (isset($str[12]) && (0 === \strpos($str, '0'))) {
				$str = \substr($str, 1);
			}

			if ((\strlen($str) !== 12) || ('000000000000' === $str)) {
				$str = '';
				return false;
			}

			// Temporarily add an extra 0 to validate the GTIN.
			$str = "0$str";
			if (static::gtin($str)) {
				$str = \substr($str, 1);
			}
			else {
				$str = '';
				return false;
			}

			// Last thing, format?
			if ($formatted) {
				$str = \preg_replace('/^(\d)(\d{5})(\d{5})(\d)$/', '$1-$2-$3-$4', $str);
			}
		}

		return true;
	}

	/**
	 * URL
	 *
	 * Validate URLishness and convert // schemas.
	 *
	 * @param string $str URL.
	 * @return bool True/false.
	 */
	public static function url(&$str) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::url($str[$k]);
			}
		}
		else {
			cast::string($str, true);

			$tmp = v_mb::parse_url($str);

			// Schemes can be lowercase.
			if (isset($tmp['scheme'])) {
				mb::strtolower($tmp['scheme'], false);
			}

			// Validate the host, and ASCIIfy international bits
			// to keep PHP happy.
			if (! isset($tmp['host'])) {
				$str = '';
				return false;
			}
			$tmp['host'] = new domain($tmp['host']);
			if (! $tmp['host']->is_valid()) {
				$str = '';
				return false;
			}
			$tmp['host'] = (string) $tmp['host'];

			$str = v_file::unparse_url($tmp);

			$str = \filter_var($str, \FILTER_SANITIZE_URL);
			if (! \filter_var($str, \FILTER_VALIDATE_URL)) {
				$str = '';
				return false;
			}
		}

		return true;
	}

	/**
	 * UTF-8
	 *
	 * Ensure string contains valid UTF-8 encoding.
	 *
	 * @see {https://github.com/neitanod/forceutf8}
	 *
	 * @param string $str String.
	 * @return void Nothing.
	 */
	public static function utf8(&$str='') {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::utf8($str[$k]);
			}
		}
		elseif ($str && ! \is_numeric($str) && ! \is_bool($str)) {
			if (! \is_string($str)) {
				try {
					$str = (string) $str;
				} catch (\Throwable $e) {
					$str = '';
				}
			}

			if (
				$str &&
				(! \BLOBCOMMON_SANITIZE_HAS_MB || ! \mb_check_encoding($str, 'ASCII'))
			) {
				if (\BLOBCOMMON_SANITIZE_HAS_MB) {
					$length = \mb_strlen($str, '8bit');
				}
				else {
					$length = \strlen($str);
				}

				$out = '';
				for ($x = 0; $x < $length; ++$x) {
					$c1 = $str[$x];

					// Should be converted to UTF-8 if not already.
					if ($c1 >= "\xc0") {
						$c2 = $x + 1 >= $length ? "\x00" : $str[$x + 1];
						$c3 = $x + 2 >= $length ? "\x00" : $str[$x + 2];
						$c4 = $x + 3 >= $length ? "\x00" : $str[$x + 3];

						// Probably 2-byte UTF-8.
						if ($c1 >= "\xc0" & $c1 <= "\xdf") {
							// Looks good.
							if ($c2 >= "\x80" && $c2 <= "\xbf") {
								$out .= $c1 . $c2;
								++$x;
							}
							// Invalid; convert it.
							else {
								$cc1 = (\chr(\ord($c1) / 64) | "\xc0");
								$cc2 = ($c1 & "\x3f") | "\x80";
								$out .= $cc1 . $cc2;
							}
						}
						// Probably 3-byte UTF-8.
						elseif ($c1 >= "\xe0" & $c1 <= "\xef") {
							// Looks good.
							if (
								$c2 >= "\x80" &&
								$c2 <= "\xbf" &&
								$c3 >= "\x80" &&
								$c3 <= "\xbf"
							) {
								$out .= $c1 . $c2 . $c3;
								$x += 2;
							}
							// Invalid; convert it.
							else {
								$cc1 = (\chr(\ord($c1) / 64) | "\xc0");
								$cc2 = ($c1 & "\x3f") | "\x80";
								$out .= $cc1 . $cc2;
							}
						}
						// Probably 4-byte UTF-8.
						elseif ($c1 >= "\xf0" & $c1 <= "\xf7") {
							// Looks good.
							if (
								$c2 >= "\x80" &&
								$c2 <= "\xbf" &&
								$c3 >= "\x80" &&
								$c3 <= "\xbf" &&
								$c4 >= "\x80" &&
								$c4 <= "\xbf"
							) {
								$out .= $c1 . $c2 . $c3 . $c4;
								$x += 3;
							}
							// Invalid; convert it.
							else {
								$cc1 = (\chr(\ord($c1) / 64) | "\xc0");
								$cc2 = ($c1 & "\x3f") | "\x80";
								$out .= $cc1 . $cc2;
							}
						}
						// Doesn't appear to be UTF-8; convert it.
						else {
							$cc1 = (\chr(\ord($c1) / 64) | "\xc0");
							$cc2 = (($c1 & "\x3f") | "\x80");
							$out .= $cc1 . $cc2;
						}
					}
					// Convert it.
					elseif (($c1 & "\xc0") === "\x80") {
						$o1 = \ord($c1);

						// Convert from Windows-1252.
						if (isset(constants::WIN1252_CHARS[$o1])) {
							$out .= constants::WIN1252_CHARS[$o1];
						}
						else {
							$cc1 = (\chr($o1 / 64) | "\xc0");
							$cc2 = (($c1 & "\x3f") | "\x80");
							$out .= $cc1 . $cc2;
						}
					}
					// No change.
					else {
						$out .= $c1;
					}
				}

				$str = (1 === @\preg_match('/^./us', $out)) ? $out : '';
			}
		}
	}

	/**
	 * Whitespace
	 *
	 * Trim edges, replace all consecutive horizontal whitespace
	 * with a single space, and constrict consecutive newlines.
	 *
	 * @param string $str String.
	 * @param int $newlines Consecutive newlines allowed.
	 * @return void Nothing.
	 */
	public static function whitespace(&$str='', int $newlines=0) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::whitespace($str[$k], $newlines);
			}
		}
		else {
			// If there are no spaces, we're done.
			if (\is_string($str) && ! \preg_match('/\s/u', $str)) {
				return;
			}

			cast::string($str, true);
			static::to_range($newlines, 0);

			if (! $newlines || ! \preg_match('/\v/u', $str)) {
				$str = \trim(\preg_replace('/\s+/u', ' ', $str));
				return;
			}

			// Sanitize newlines.
			mb::trim($str);
			$str = \str_replace("\r\n", "\n", $str);
			$str = \preg_replace('/\v/u', "\n", $str);

			// Now go through line by line.
			$str = \explode("\n", $str);
			static::whitespace($str, 0);
			$str = \implode("\n", $str);

			$str = \preg_replace('/\n{' . ($newlines + 1) . ',}/', \str_repeat("\n", $newlines), $str);

			$str = \trim($str);
		}
	}

	/**
	 * Whitespace Multiline
	 *
	 * @param string $str String.
	 * @param int $newlines Consecutive newlines allowed.
	 * @return void Nothing.
	 */
	public static function whitespace_multiline(&$str='', int $newlines=1) {
		static::whitespace($str, $newlines);
	}

	/**
	 * US ZIP5
	 *
	 * @param string $str ZIP Code.
	 * @return bool True/false.
	 */
	public static function zip5(&$str='') {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::zip5($str[$k]);
			}
		}
		else {
			// No need for fancy casting.
			if (! \is_string($str)) {
				if (\is_numeric($str)) {
					$str = (string) $str;
				}
				else {
					$str = '';
					return false;
				}
			}

			$str = \preg_replace('/[^\d]/', '', $str);

			if (! isset($str[4])) {
				$str = \sprintf('%05d', $str);
			}
			elseif (isset($str[5])) {
				$str = \substr($str, 0, 5);
			}

			if ('00000' === $str) {
				$str = '';
				return false;
			}
		}

		return true;
	}
}
