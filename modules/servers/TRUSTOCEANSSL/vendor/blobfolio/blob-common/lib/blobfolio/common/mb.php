<?php
/**
 * Multi-Byte.
 *
 * Functions for multi-byte string handling.
 *
 * @package blobfolio/common
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\common;

class mb {

	/**
	 * Parse URL
	 *
	 * @see {http://php.net/manual/en/function.parse-url.php#114817}
	 * @see {https://github.com/jeremykendall/php-domain-parser/}
	 *
	 * @param string $url URL.
	 * @param int $component Component.
	 * @return mixed Array, Component, or Null.
	 */
	public static function parse_url($url, int $component = -1) {
		ref\cast::string($url, true);

		ref\mb::trim($url, true);

		// Before we start, let's fix scheme-agnostic URLs.
		$url = preg_replace('/^:?\/\//', 'https://', $url);

		// If an IPv6 address is passed on its own, we
		// need to shove it in brackets.
		if (filter_var($url, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
			$url = "[$url]";
		}

		// The trick is to urlencode (most) parts before passing
		// them to the real parse_url().
		$encoded = preg_replace_callback(
			'%([a-zA-Z][a-zA-Z0-9+\-.]*)?(:?//)?([^:/@?&=#\[\]]+)%usD',
			function ($matches) {
				$matches[3] = urldecode($matches[3]);
				return $matches[1] . $matches[2] . $matches[3];
			},
			$url
		);

		// Before getting the real answer, make sure
		// there is a scheme, otherwise PHP will assume
		// all there is is a path, which is stupid.
		if (PHP_URL_SCHEME !== $component) {
			$test = parse_url($encoded, PHP_URL_SCHEME);
			if (!$test) {
				$encoded = "blobfolio://$encoded";
			}
		}

		$parts = parse_url($encoded, $component);

		// And now decode what we've been giving. Let's
		// also take a moment to translate Unicode hosts
		// to ASCII.
		if (is_string($parts) && PHP_URL_SCHEME !== $component) {
			$parts = str_replace(' ', '+', urldecode($parts));

			if (PHP_URL_HOST === $component) {
				// Fix Unicode.
				if (function_exists('idn_to_ascii')) {
					$parts = explode('.', $parts);
					ref\file::idn_to_ascii($parts);
					$parts = implode('.', $parts);
				}

				// Lowercase it.
				ref\mb::strtolower($parts, false, true);

				// Get rid of trailing periods.
				$parts = ltrim($parts, '.');
				$parts = rtrim($parts, '.');

				// Standardize IPv6 formatting.
				if (0 === strpos($parts, '[')) {
					$parts = str_replace(array('[', ']'), '', $parts);
					ref\sanitize::ip($parts, true);
					$parts = "[{$parts}]";
				}
			}
		}
		elseif (is_array($parts)) {
			foreach ($parts as $k=>$v) {
				if (!is_string($v)) {
					continue;
				}

				if ('scheme' !== $k) {
					$parts[$k] = str_replace(' ', '+', urldecode($v));
				}
				// Remove our pretend scheme.
				elseif ('blobfolio' === $v) {
					unset($parts[$k]);
					continue;
				}

				if ('host' === $k) {
					// Fix Unicode.
					if (function_exists('idn_to_ascii')) {
						$parts[$k] = explode('.', $parts[$k]);
						ref\file::idn_to_ascii($parts[$k]);
						$parts[$k] = implode('.', $parts[$k]);
					}

					// Lowercase it.
					ref\mb::strtolower($parts[$k], false, true);

					// Get rid of trailing periods.
					$parts[$k] = ltrim($parts[$k], '.');
					$parts[$k] = rtrim($parts[$k], '.');

					// Standardize IPv6 formatting.
					if (0 === strpos($parts[$k], '[')) {
						$parts[$k] = str_replace(array('[', ']'), '', $parts[$k]);
						ref\sanitize::ip($parts[$k], true);
						$parts[$k] = "[{$parts[$k]}]";
					}
				}
			}
		}

		return $parts;
	}


	/**
	 * Wrapper For parse_str()
	 *
	 * @param string $str String.
	 * @param mixed $result Result.
	 * @return bool True/false.
	 */
	public static function parse_str($str, &$result) {
		if (function_exists('mb_parse_str')) {
			return mb_parse_str($str, $result);
		}
		else {
			return parse_str($str, $result);
		}
	}

	/**
	 * Wrapper For str_pad()
	 *
	 * @param string $str String.
	 * @param int $pad_length Pad length.
	 * @param string $pad_string Pad string.
	 * @param int $pad_type Pad type.
	 * @param bool $constringent Light cast.
	 * @return string Padded string.
	 */
	public static function str_pad($str='', int $pad_length, $pad_string=' ', int $pad_type=STR_PAD_RIGHT, bool $constringent=false) {
		ref\mb::str_pad($str, $pad_length, $pad_string, $pad_type, $constringent);
		return $str;
	}

	/**
	 * Wrapper For str_split()
	 *
	 * @param string $str String.
	 * @param int $split_length Split length.
	 * @param bool $constringent Light cast.
	 * @return array|bool Split string or false.
	 */
	public static function str_split($str, int $split_length=1, bool $constringent=false) {
		ref\mb::str_split($str, $split_length, $constringent);
		return $str;
	}

	/**
	 * Wrapper For strlen()
	 *
	 * @param string $str String.
	 * @param bool $constringent Light cast.
	 * @return int String length.
	 */
	public static function strlen($str, bool $constringent=false) {
		ref\cast::constringent($str, $constringent);

		if (function_exists('mb_strlen')) {
			return (int) mb_strlen($str, 'UTF-8');
		}
		else {
			return strlen($str);
		}
	}

	/**
	 * Wrapper For strpos()
	 *
	 * @param string $haystack Haystack.
	 * @param string $needle Needle.
	 * @param int $offset Offset.
	 * @return int|bool First occurrence or false.
	 */
	public static function strpos(string $haystack, string $needle, int $offset=0) {
		if (function_exists('mb_strpos')) {
			return mb_strpos($haystack, $needle, $offset, 'UTF-8');
		}
		else {
			return strpos($haystack, $needle, $offset);
		}
	}

	/**
	 * Wrapper For strrev()
	 *
	 * @param string $str String.
	 * @param bool $constringent Light cast.
	 * @return string Reversed string.
	 */
	public static function strrev($str, bool $constringent=false) {
		ref\mb::strrev($str, $constringent);
		return $str;
	}

	/**
	 * Wrapper For strpos()
	 *
	 * @param string $haystack Haystack.
	 * @param string $needle Needle.
	 * @param int $offset Offset.
	 * @return int|bool Last occurrence or false.
	 */
	public static function strrpos(string $haystack, string $needle, int $offset=0) {
		if (function_exists('mb_strrpos')) {
			return mb_strrpos($haystack, $needle, $offset, 'UTF-8');
		}
		else {
			return strrpos($haystack, $needle, $offset);
		}
	}

	/**
	 * Wrapper For strtolower()
	 *
	 * This will catch various case-able Unicode beyond
	 * the native PHP functions.
	 *
	 * @param string $str String.
	 * @param bool $strict Strict.
	 * @param bool $constringent Light cast.
	 * @return string String.
	 */
	public static function strtolower($str='', bool $strict=false, bool $constringent=false) {
		ref\mb::strtolower($str, $strict, $constringent);
		return $str;
	}

	/**
	 * Wrapper For strtoupper()
	 *
	 * This will catch various case-able Unicode beyond
	 * the native PHP functions.
	 *
	 * @param string $str String.
	 * @param bool $strict Strict.
	 * @param bool $constringent Light cast.
	 * @return string String.
	 */
	public static function strtoupper($str='', bool $strict=false, bool $constringent=false) {
		ref\mb::strtoupper($str, $strict, $constringent);
		return $str;
	}

	/**
	 * Wrapper For substr()
	 *
	 * @param string $str String.
	 * @param int $start Start.
	 * @param int $length Length.
	 * @param bool $constringent Light cast.
	 * @return string String.
	 */
	public static function substr($str, $start=0, $length=null, bool $constringent=false) {
		ref\cast::constringent($str, $constringent);

		if (function_exists('mb_substr')) {
			return mb_substr($str, $start, $length, 'UTF-8');
		}
		else {
			return substr($str, $start, $length);
		}
	}

	/**
	 * Wrapper For substr_count()
	 *
	 * @param string $haystack Haystack.
	 * @param string $needle Needle.
	 * @return int Count.
	 */
	public static function substr_count(string $haystack, string $needle) {
		if (function_exists('mb_substr_count')) {
			return mb_substr_count($haystack, $needle, 'UTF-8');
		}
		else {
			return substr_count($haystack, $needle);
		}
	}

	/**
	 * Trim
	 *
	 * Trim all whitespacey bits from both ends.
	 *
	 * @param string $str String.
	 * @param bool $constringent Light cast.
	 * @return bool True.
	 */
	public static function trim($str='', bool $constringent=false) {
		ref\mb::trim($str, $constringent);
		return $str;
	}

	/**
	 * Wrapper For ucfirst()
	 *
	 * This will catch various case-able Unicode beyond
	 * the native PHP functions.
	 *
	 * @param string $str String.
	 * @param bool $strict Strict.
	 * @param bool $constringent Light cast.
	 * @return string String.
	 */
	public static function ucfirst($str='', bool $strict=false, bool $constringent=false) {
		ref\mb::ucfirst($str, $strict, $constringent);
		return $str;
	}

	/**
	 * Wrapper For ucwords()
	 *
	 * This will catch various case-able Unicode beyond
	 * the native PHP functions.
	 *
	 * @param string $str String.
	 * @param bool $strict Strict.
	 * @param bool $constringent Light cast.
	 * @return string String.
	 */
	public static function ucwords($str='', bool $strict=false, bool $constringent=false) {
		ref\mb::ucwords($str, $strict, $constringent);
		return $str;
	}

	/**
	 * Wrapper for wordwrap()
	 *
	 * Wrap text to specified line length. Unlike PHP's version, this
	 * will preferentially break long strings on any hypens or dashes
	 * they might have.
	 *
	 * @param string $str String.
	 * @param int $width Width.
	 * @param string $break Break.
	 * @param bool $cut Cut.
	 * @param bool $constringent Light cast.
	 * @return string String.
	 */
	public static function wordwrap($str, int $width=75, $break="\n", bool $cut=false, bool $constringent=false) {
		ref\mb::wordwrap($str, $width, $break, $cut, $constringent);
		return $str;
	}
}


