<?php
/**
 * Data Helpers.
 *
 * Miscellaneous functions for processing and manipulating data.
 *
 * @package blobfolio/common
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\common;

class data {

	/**
	 * Compare Two Arrays
	 *
	 * @param array $arr1 Array.
	 * @param array $arr2 Array.
	 * @return bool True/false.
	 */
	public static function array_compare(&$arr1, &$arr2) {
		// Obviously bad data.
		if (!is_array($arr1) || !is_array($arr2)) {
			return false;
		}

		$length = count($arr1);

		// Length mismatch.
		if (count($arr2) !== $length) {
			return false;
		}

		// Different keys, we don't need to check further.
		if (count(array_intersect_key($arr1, $arr2)) !== $length) {
			return false;
		}

		// We will ignore keys for non-associative arrays.
		if (
			(cast::array_type($arr1) !== 'associative') &&
			(cast::array_type($arr2) !== 'associative')
		) {
			return count(array_intersect($arr1, $arr2)) === $length;
		}

		// Check each item.
		foreach ($arr1 as $k=>$v) {
			if (!isset($arr2[$k])) {
				return false;
			}

			// Recursive?
			if (is_array($arr1[$k]) && is_array($arr2[$k])) {
				if (!static::array_compare($arr1[$k], $arr2[$k])) {
					return false;
				}
			}
			elseif ($arr1[$k] !== $arr2[$k]) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Case-insensitive array_diff()
	 *
	 * Note: Type matters.
	 *
	 * @param array $arr1 Array.
	 * @param array $arr2 Array.
	 * @return array Difference.
	 */
	public static function array_idiff($arr1, $arr2) {
		// First off, a variable number of arguments can be passed.
		// Let's take a look and see what we have.
		$arrays = func_get_args();
		if (!isset($arrays[1])) {
			return array();
		}
		foreach ($arrays as $a) {
			if (!is_array($a)) {
				return array();
			}
		}

		// Compare the first to each.
		$length = count($arrays);
		for ($x = 1; $x < $length; ++$x) {
			$common = array();

			// If the arrays are the same, or the second is empty,
			// we can skip the tests.
			if (!count($arrays[$x])) {
				continue;
			}

			// Lowercase for comparison.
			$arr1 = mb::strtolower($arrays[0], true);
			$arr2 = mb::strtolower($arrays[$x], true);

			foreach ($arr1 as $k=>$v) {
				if (!is_array($v) && !in_array($v, $arr2, true)) {
					$common[$k] = $arrays[0][$k];
				}
			}

			// Nothing left? The end!
			if (!count($common)) {
				return $common;
			}

			$arrays[0] = $common;
		}

		return $arrays[0];
	}

	/**
	 * Case-insensitive array_intersect()
	 *
	 * @param array $arr1 Array.
	 * @param array $arr2 Array.
	 * @return array Intersection.
	 */
	public static function array_iintersect($arr1, $arr2) {
		// First off, a variable number of arguments can be passed.
		// Let's take a look and see what we have.
		$arrays = func_get_args();
		if (!isset($arrays[1])) {
			return array();
		}
		foreach ($arrays as $a) {
			if (!is_array($a) || !count($a)) {
				return array();
			}
		}

		// Compare the first to each.
		$length = count($arrays);
		for ($x = 1; $x < $length; ++$x) {
			$common = array();

			// Lowercase for comparison.
			$arr1 = mb::strtolower($arrays[0], true);
			$arr2 = mb::strtolower($arrays[$x], true);

			foreach ($arr1 as $k=>$v) {
				if (!is_array($v) && in_array($v, $arr2, true)) {
					$common[$k] = $arrays[0][$k];
				}
			}

			// Nothing left? The end!
			if (!count($common)) {
				return $common;
			}

			$arrays[0] = $common;
		}

		return $arrays[0];
	}

	/**
	 * Case-insensitive array_key_exists()
	 *
	 * @param string $needle Needle.
	 * @param array $haystack Haystack.
	 * @return bool True/false.
	 */
	public static function array_ikey_exists($needle, $haystack) {
		if (!is_array($haystack) || !count($haystack)) {
			return false;
		}
		$haystack = array_keys($haystack);

		return (false !== static::array_isearch($needle, $haystack));
	}

	/**
	 * Case-insensitive array_search()
	 *
	 * @param string $needle Needle.
	 * @param array $haystack Haystack.
	 * @param bool $strict Strict.
	 * @return mixed Key or false.
	 */
	public static function array_isearch($needle, $haystack, bool $strict=true) {
		if (!is_array($haystack) || !count($haystack)) {
			return false;
		}

		// Lowercase for comparison.
		ref\mb::strtolower($needle, true);
		ref\mb::strtolower($haystack, true);

		// phpcs:disable
		return array_search($needle, $haystack, $strict);
		// phpcs:enable
	}

	/**
	 * Recursive Array Map
	 *
	 * @param callable $func Callback function.
	 * @param array $arr Array.
	 * @return bool True/false.
	 */
	public static function array_map_recursive(callable $func, array $arr) {
		return filter_var($arr, FILTER_CALLBACK, array('options'=>$func));
	}

	/**
	 * Otherize Values
	 *
	 * Convert an indefinitely large array of totals to
	 * a fixed length with the chopped bits grouped under
	 * "other".
	 *
	 * @param array $arr Array.
	 * @param int $length Length.
	 * @param string $other Label for others.
	 * @return array|bool Array or false.
	 */
	public static function array_otherize($arr=null, int $length=5, $other='Other') {
		if ('associative' !== cast::array_type($arr)) {
			return false;
		}

		// Make sure everything is numeric.
		foreach ($arr as $k=>$v) {
			if (!is_int($arr[$k]) && !is_float($arr[$k])) {
				ref\cast::float($arr[$k], true);
			}
		}

		arsort($arr);

		ref\sanitize::to_range($length, 1);

		// Nothing to do.
		if (count($arr) <= $length) {
			return $arr;
		}

		ref\cast::string($other, true);
		if (!$other) {
			$other = 'Other';
		}

		// Just sum it.
		if (1 === $length) {
			return array($other=>array_sum($arr));
		}

		$out = array_slice($arr, 0, $length - 1);
		$out[$other] = array_sum(array_slice($arr, $length - 1));

		return $out;
	}

	/**
	 * Return the last value of an array.
	 *
	 * This is like array_pop() but non-destructive.
	 *
	 * @param array $arr Array.
	 * @return mixed Value. False on error.
	 */
	public static function array_pop(array &$arr) {
		if (!count($arr)) {
			return false;
		}

		$reversed = array_reverse($arr);
		return static::array_pop_top($reversed);
	}

	/**
	 * Return a random array element.
	 *
	 * @param array $arr Array.
	 * @return mixed Value. False on error.
	 */
	public static function array_pop_rand(array &$arr) {
		$length = count($arr);

		if (!$length) {
			return false;
		}

		// Nothing random about an array with one thing.
		if (1 === $length) {
			return static::array_pop_top($arr);
		}

		$keys = array_keys($arr);
		$index = static::random_int(0, $length - 1);

		return $arr[$keys[$index]];
	}

	/**
	 * Return the first value of an array.
	 *
	 * @param array $arr Array.
	 * @return mixed Value. False on error.
	 */
	public static function array_pop_top(array &$arr) {
		if (!count($arr)) {
			return false;
		}

		reset($arr);
		return $arr[key($arr)];
	}

	/**
	 * Generate Credit Card Expiration Months
	 *
	 * @param string $format Date format.
	 * @return array Months.
	 */
	public static function cc_exp_months(string $format='m - M') {
		$months = array();
		for ($x = 1; $x <= 12; ++$x) {
			$months[$x] = date($format, strtotime('2000-' . sprintf('%02d', $x) . '-01'));
		}
		return $months;
	}

	/**
	 * Generate Credit Card Expiration Years
	 *
	 * @param int $length Number of years.
	 * @return array Years.
	 */
	public static function cc_exp_years(int $length=10) {
		if ($length < 1) {
			$length = 10;
		}

		$years = array();
		for ($x = 0; $x < $length; ++$x) {
			$year = (int) (date('Y') + $x);
			$years[$year] = $year;
		}

		return $years;
	}

	/**
	 * Days Between Dates
	 *
	 * @param string $date1 Date.
	 * @param string $date2 Date.
	 * @return int Difference in Days.
	 */
	public static function datediff($date1, $date2) {
		ref\sanitize::date($date1);
		ref\sanitize::date($date2);

		// Same or bad dates.
		if (
			!is_string($date1) ||
			!is_string($date2) ||
			($date1 === $date2) ||
			('0000-00-00' === $date1) ||
			('0000-00-00' === $date2)
		) {
			return 0;
		}

		// Prefer DateTime.
		if (class_exists('DateTime')) {
			$date1 = new \DateTime($date1);
			$date2 = new \DateTime($date2);
			$diff = $date1->diff($date2);

			return abs($diff->days);
		}

		// Fallback to counting seconds.
		$date1 = strtotime($date1);
		$date2 = strtotime($date2);
		return ceil(abs($date2 - $date1) / 60 / 60 / 24);
	}

	/**
	 * Case-insensitive in_array()
	 *
	 * @param string $needle Needle.
	 * @param array $haystack Haystack.
	 * @param bool $strict Strict.
	 * @return bool True/false.
	 */
	public static function iin_array($needle, $haystack, bool $strict=true) {
		return (false !== static::array_isearch($needle, $haystack, $strict));
	}

	/**
	 * Is Value In Range?
	 *
	 * @param mixed $value Value.
	 * @param mixed $min Min.
	 * @param mixed $max Max.
	 * @return bool True/false.
	 */
	public static function in_range($value, $min=null, $max=null) {
		return sanitize::to_range($value, $min, $max) === $value;
	}

	/**
	 * IP in Range?
	 *
	 * Check to see if an IP is in range. This
	 * either accepts a minimum and maximum IP,
	 * or a CIDR.
	 *
	 * @param string $ip String.
	 * @param string $min Min or CIDR.
	 * @param string $max Max.
	 * @return bool True/false.
	 */
	public static function ip_in_range(string $ip, $min, $max=null) {
		ref\sanitize::ip($ip, true);
		if (!is_string($min)) {
			return false;
		}

		// Bad IP.
		if (!$ip) {
			return false;
		}

		// Is $min a range?
		if (false !== strpos($min, '/')) {
			if (false === ($range = format::cidr_to_range($min))) {
				return false;
			}
			$min = $range['min'];
			$max = $range['max'];
		}
		// Max is required otherwise.
		elseif (is_null($max)) {
			return false;
		}

		// Convert everything to a number.
		ref\format::ip_to_number($ip);
		ref\format::ip_to_number($min);
		ref\format::ip_to_number($max);

		if (
			(false !== $ip) &&
			(false !== $min) &&
			(false !== $max)
		) {
			return static::in_range($ip, $min, $max);
		}

		return false;
	}

	/**
	 * Is JSON?
	 *
	 * @param string $str String.
	 * @param bool $empty Allow empty.
	 * @return bool True/false.
	 */
	public static function is_json($str, bool $empty=false) {
		if (!is_string($str) || (!$empty && !$str)) {
			return false;
		}

		if ($empty && !$str) {
			return true;
		}

		$json = json_decode($str);
		return !is_null($json);
	}

	/**
	 * Is Value Valid UTF-8?
	 *
	 * @param string $str String.
	 * @return bool True/false.
	 */
	public static function is_utf8($str) {
		if (is_numeric($str) || is_bool($str)) {
			return true;
		}
		elseif (is_string($str)) {
			return (bool) preg_match('//u', $str);
		}

		return false;
	}

	/**
	 * JSON Decode As Array
	 *
	 * This ensures a JSON string is always decoded to
	 * an array, optionally matching the structure of
	 * a template object.
	 *
	 * @param string $json JSON.
	 * @param mixed $defaults Template.
	 * @param bool $strict Enforce types if templating.
	 * @param bool $recursive Recursive templating.
	 * @return array Data.
	 */
	public static function json_decode_array($json, $defaults=null, bool $strict=true, bool $recursive=true) {
		ref\format::json_decode($json);

		if (is_null($json) || (is_string($json) && !$json)) {
			$json = array();
		}
		else {
			ref\cast::array($json);
		}

		if (is_array($defaults)) {
			return static::parse_args($json, $defaults, $strict, $recursive);
		}
		else {
			return $json;
		}
	}

	/**
	 * Length in Range
	 *
	 * See if the length of a string is between two extremes.
	 *
	 * @param string $str String.
	 * @param int $min Min length.
	 * @param int $max Max length.
	 * @return bool True/false.
	 */
	public static function length_in_range(string $str, $min=null, $max=null) {
		if (!is_null($min) && !is_int($min)) {
			ref\cast::int($min, true);
		}
		if (!is_null($max) && !is_int($max)) {
			ref\cast::int($max, true);
		}

		$length = mb::strlen($str, true);
		if (!is_null($min) && !is_null($max) && $min > $max) {
			static::switcheroo($min, $max);
		}

		if (!is_null($min) && $min > $length) {
			return false;
		}

		if (!is_null($max) && $max < $length) {
			return false;
		}

		return true;
	}

	/**
	 * Parse Arguments
	 *
	 * Make sure user arguments follow a default
	 * format. Unlike `wp_parse_args()`-type functions,
	 * only keys from the template are allowed.
	 *
	 * @param mixed $args User arguments.
	 * @param mixed $defaults Default values/format.
	 * @param bool $strict Strict type enforcement.
	 * @param bool $recursive Recursively apply formatting if inner values are also arrays.
	 * @return array Parsed arguments.
	 */
	public static function parse_args($args, $defaults, bool $strict=true, bool $recursive=true) {
		ref\cast::array($args);
		ref\cast::array($defaults);

		if (!count($defaults)) {
			return array();
		}

		foreach ($defaults as $k=>$v) {
			if (array_key_exists($k, $args)) {
				// Recurse if the default is a populated associative array.
				if (
					$recursive &&
					is_array($defaults[$k]) &&
					(cast::array_type($defaults[$k]) === 'associative')
				) {
					$defaults[$k] = static::parse_args($args[$k], $defaults[$k], $strict, $recursive);
				}
				// Otherwise just replace.
				else {
					$defaults[$k] = $args[$k];
					if ($strict && !is_null($v)) {
						ref\cast::to_type($defaults[$k], gettype($v), true);
					}
				}
			}
		}

		return $defaults;
	}

	/**
	 * Generate Random Integer
	 *
	 * This will use the most secure function
	 * available for the environment.
	 *
	 * @param int $min Min.
	 * @param int $max Max.
	 * @return int Random number.
	 */
	public static function random_int(int $min=0, int $max=1) {
		if ($min > $max) {
			static::switcheroo($min, $max);
		}

		if (function_exists('random_int')) {
			return random_int($min, $max);
		}
		else {
			return mt_rand($min, $max);
		}
	}

	/**
	 * Generate Random String
	 *
	 * By default the string will only contain
	 * unambiguous uppercase letters and numbers.
	 * Alternate alphabets can be passed instead.
	 *
	 * @param int $length Length.
	 * @param array $soup Alternate alphabet.
	 * @return string Random string.
	 */
	public static function random_string(int $length=10, $soup=null) {
		if ($length < 1) {
			return '';
		}

		if (is_array($soup) && count($soup)) {
			ref\cast::string($soup);

			$soup = implode('', $soup);
			ref\sanitize::printable($soup, true);

			$soup = preg_replace('/\s/u', '', $soup);
			$soup = array_unique(mb::str_split($soup, 1, true));
			$soup = array_values($soup);
			if (!count($soup)) {
				return '';
			}
		}

		// Use default soup.
		if (!is_array($soup) || !count($soup)) {
			$soup = constants::RANDOM_CHARS;
		}

		// Pick nine entries at random.
		$salt = '';
		$max = count($soup) - 1;
		for ($x = 0; $x < $length; ++$x) {
			$salt .= $soup[static::random_int(0, $max)];
		}

		return $salt;
	}

	/**
	 * Switch Two Variables
	 *
	 * @param mixed $var1 Variable.
	 * @param mixed $var2 Variable.
	 * @return bool True.
	 */
	public static function switcheroo(&$var1, &$var2) {
		$tmp = $var1;
		$var1 = $var2;
		$var2 = $tmp;

		return true;
	}

	/**
	 * Delete a Cookie
	 *
	 * A companion to PHP's `setcookie()` function. It
	 * attempts to remove the cookie. The same path, etc.,
	 * values should be passed as were used to first set it.
	 *
	 * @param string $name Name.
	 * @param string $path Path.
	 * @param string $domain Domain.
	 * @param bool $secure SSL only.
	 * @param bool $httponly HTTP only.
	 * @return bool True/false.
	 */
	public static function unsetcookie(string $name, string $path='', string $domain='', bool $secure=false, bool $httponly=false) {

		if (!headers_sent()) {
			setcookie($name, false, -1, $path, $domain, $secure, $httponly);
			if (isset($_COOKIE[$name])) {
				unset($_COOKIE[$name]);
			}

			return true;
		}

		return false;
	}
}


