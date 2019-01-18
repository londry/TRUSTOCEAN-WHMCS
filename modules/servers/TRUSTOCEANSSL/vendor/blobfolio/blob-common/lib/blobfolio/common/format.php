<?php
/**
 * Formatting
 *
 * Functions for formatting data.
 *
 * @package blobfolio/common
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\common;

class format {

	/**
	 * Flatten Multi-Dimensional Array
	 *
	 * Like array_values(), but move child values into the single (main)
	 * level.
	 *
	 * @param array $arr Array.
	 * @return array Values.
	 */
	public static function array_flatten($arr) {
		ref\format::array_flatten($arr);
		return $arr;
	}

	/**
	 * Create Index Array
	 *
	 * This will convert a {k:v} associative array into an indexed array
	 * with {key: k, value: v} as the values. Useful when exporting
	 * sorted data to Javascript, which doesn't preserve object key
	 * ordering.
	 *
	 * @param array $arr Array.
	 * @return array Array.
	 */
	public static function array_to_indexed($arr) {
		ref\format::array_to_indexed($arr);
		return $arr;
	}

	/**
	 * Ceil w/ Precision
	 *
	 * @param float $num Number.
	 * @param int $precision Precision.
	 * @return float Number.
	 */
	public static function ceil($num, int $precision=0) {
		ref\format::ceil($num, $precision);
		return $num;
	}

	/**
	 * CIDR to IP Range
	 *
	 * Find the minimum and maximum IPs in a given CIDR range.
	 *
	 * @param string $cidr CIDR.
	 * @return array|bool Range or false.
	 */
	public static function cidr_to_range($cidr) {
		ref\cast::string($cidr, true);

		$range = array('min'=>0, 'max'=>0);
		$cidr = array_pad(explode('/', $cidr), 2, 0);
		ref\cast::int($cidr[1], true);

		// IPv4?
		if (filter_var($cidr[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
			// IPv4 is only 32-bit.
			ref\sanitize::to_range($cidr[1], 0, 32);

			if (0 === $cidr[1]) {
				$range['min'] = $range['max'] = sanitize::ip($cidr[0]);
			}
			else {
				// Work from binary.
				$cidr[1] = bindec(str_pad(str_repeat('1', $cidr[1]), 32, '0'));

				// Calculate the range.
				$ip = ip2long($cidr[0]);
				$netmask = $cidr[1];
				$first = ($ip & $netmask);
				$bc = $first | ~$netmask;

				$range['min'] = long2ip($first);
				$range['max'] = long2ip($bc);
			}
			return $range;
		}

		// IPv6? Of course a little more complicated.
		if (filter_var($cidr[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
			// IPv6 is only 128-bit.
			ref\sanitize::to_range($cidr[1], 0, 128);

			if (0 === $cidr[1]) {
				$range['min'] = $range['max'] = sanitize::ip($cidr[0]);
				return $range;
			}

			// Work from binary.
			$bin = str_pad(str_repeat('1', $cidr[1]), 128, '0');
			if (function_exists('gmp_init')) {
				$cidr[1] = gmp_strval(gmp_init($bin, 2), 10);
			}
			else {
				$cidr[1] = bc::bindec($bin);
			}

			// Calculate the range.
			$ip = static::ip_to_number($cidr[0]);
			$netmask = $cidr[1];

			if (function_exists('gmp_and')) {
				$first = gmp_and($ip, $netmask);

				// GMP doesn't have the kind of ~ we're looking for. But
				// that's fine; binary is easy.
				$bin = gmp_strval(gmp_init($netmask, 10), 2);
				$bin = sprintf('%0128s', $bin);
				$bin = strtr($bin, array('0'=>'1', '1'=>'0'));
				$not = gmp_strval(gmp_init($bin, 2), 10);

				$bc = gmp_or($first, $not);

				// Make sure they're strings.
				$first = gmp_strval($first);
				$bc = gmp_strval($bc);
			}
			else {
				$first = bc::bitwise('&', $ip, $netmask);
				$bc = bc::bitwise('|', $first, bc::bitwise('~', $netmask, null, 128));
			}

			$range['min'] = static::number_to_ip($first);
			$range['max'] = static::number_to_ip($bc);

			return $range;
		}

		return false;
	}

	/**
	 * Decode JS Entities
	 *
	 * Decode escape and unicode chars.
	 *
	 * @param string $str String.
	 * @return string String.
	 */
	public static function decode_js_entities($str='') {
		ref\format::decode_js_entities($str);
		return $str;
	}

	/**
	 * Decode Escape Entities
	 *
	 * Decode \b, \f, \n, \r, \t.
	 *
	 * @param string $str String.
	 * @return string String.
	 */
	public static function decode_escape_entities($str='') {
		ref\format::decode_escape_entities($str);
		return $str;
	}

	/**
	 * Decode Unicode Entities
	 *
	 * Decode \u1234 into chars.
	 *
	 * @param string $str String.
	 * @return string String.
	 */
	public static function decode_unicode_entities($str='') {
		ref\format::decode_unicode_entities($str);
		return $str;
	}

	/**
	 * Decode HTML Entities
	 *
	 * Decode all HTML entities back into their char counterparts,
	 * recursively until every last one is captured.
	 *
	 * @param string $str String.
	 * @return string HTML.
	 */
	public static function decode_entities($str='') {
		ref\format::decode_entities($str);
		return $str;
	}

	/**
	 * Generate Text Except
	 *
	 * @param string $str String.
	 * @param mixed $args Arguments.
	 *
	 * @arg int $length Length limit.
	 * @arg string $unit Unit to examine, "character" or "word".
	 * @arg string $suffix Suffix, e.g. ...
	 *
	 * @return string Excerpt.
	 */
	public static function excerpt($str='', $args=null) {
		ref\cast::string($str, true);

		ref\sanitize::whitespace($str, 0, true);
		$str = strip_tags($str);

		$options = data::parse_args($args, constants::EXCERPT);
		if ($options['length'] < 1) {
			return '';
		}

		$options['unit'] = strtolower($options['unit']);
		switch (substr($options['unit'], 0, 4)) {
			case 'char':
				$options['unit'] = 'character';
				break;
			case 'word':
				$options['unit'] = 'word';
				break;
		}

		// Character limit.
		if (
			('character' === $options['unit']) &&
			mb::strlen($str, true) > $options['length']
		) {
			$str = trim(mb::substr($str, 0, $options['length'], true)) . $options['suffix'];
		}
		// Word limit.
		elseif (
			('word' === $options['unit']) &&
			substr_count($str, ' ') > $options['length'] - 1
		) {
			$str = explode(' ', $str);
			$str = array_slice($str, 0, $options['length']);
			$str = implode(' ', $str) . $options['suffix'];
		}

		return $str;
	}

	/**
	 * Floor w/ Precision
	 *
	 * @param float $num Number.
	 * @param int $precision Precision.
	 * @return float Number.
	 */
	public static function floor($num, int $precision=0) {
		ref\format::floor($num, $precision);
		return $num;
	}

	/**
	 * Fraction
	 *
	 * Convert a decimal to a fraction, e.g. 0.5 to 1/2.
	 *
	 * @see {https://www.designedbyaturtle.co.uk/2015/converting-a-decimal-to-a-fraction-in-php/}
	 *
	 * @param float $num Number.
	 * @param float $precision Precision.
	 * @return string Fraction.
	 */
	public static function fraction($num, float $precision=0.0001) {
		ref\format::fraction($num, $precision);
		return $num;
	}

	/**
	 * Inflect
	 *
	 * Inflect a phrase given a count. `sprintf` formatting is
	 * supported. If an array is passed as $count, its size will be used
	 * for inflection.
	 *
	 * @param int|array $count Count.
	 * @param string $single Singular.
	 * @param string $plural Plural.
	 * @return string Inflected string.
	 */
	public static function inflect($count, $single, $plural) {
		if (is_array($count)) {
			$count = (float) count($count);
		}
		else {
			ref\cast::number($count);
		}

		if (1.0 === $count) {
			ref\cast::string($single, true);
			return sprintf($single, $count);
		}
		else {
			ref\cast::string($plural, true);
			return sprintf($plural, $count);
		}
	}

	/**
	 * IP to Number
	 *
	 * @param string $ip IP.
	 * @return int|bool IP or false.
	 */
	public static function ip_to_number($ip) {
		ref\format::ip_to_number($ip);
		return $ip;
	}

	/**
	 * IP to Subnet
	 *
	 * This assumes the standard ranges of 24 for IPv4 and 64 for IPv6.
	 *
	 * @param string $ip IP.
	 * @return string|bool Subnet or false.
	 */
	public static function ip_to_subnet($ip) {
		ref\format::ip_to_subnet($ip);
		return $ip;
	}

	/**
	 * JSON
	 *
	 * Fix JSON formatting.
	 *
	 * @param string $str String.
	 * @param bool $pretty Pretty.
	 * @return string|null JSON or null.
	 */
	public static function json($str='', bool $pretty=true) {
		ref\format::json($str, $pretty);
		return $str;
	}

	/**
	 * JSON Decode
	 *
	 * A more robust version of JSON decode that can somewhat handle
	 * general Javascript objects. This always returns objecty things as
	 * associative arrays.
	 *
	 * @param string $str String.
	 * @return mixed Value.
	 */
	public static function json_decode($str='') {
		ref\format::json_decode($str);
		return $str;
	}

	/**
	 * JSON Encode
	 *
	 * This is a wrapper for json_encode, but will try to fix common
	 * issues.
	 *
	 * @param mixed $value Value.
	 * @param int $options Options.
	 * @param int $depth Depth.
	 * @return string JSON.
	 */
	public static function json_encode($value, $options=0, $depth=512) {
		ref\format::json_encode($value, $options, $depth);
		return $value;
	}

	/**
	 * Linkify Text
	 *
	 * Make link-like text things clickable HTML links.
	 *
	 * @param string $str String.
	 * @param array $args Arguments.
	 * @param int $pass Pass (1=URL, 2=EMAIL).
	 * @param bool $constringent Light cast.
	 *
	 * @arg array $class Class(es).
	 * @arg string $rel Rel.
	 * @arg string $target Target.
	 *
	 * @return bool True.
	 */
	public static function links($str, $args=null, int $pass=1, bool $constringent=false) {
		ref\format::links($str, $args, $pass, $constringent);
		return $str;
	}

	/**
	 * List to Array
	 *
	 * Convert a delimited list into a proper array.
	 *
	 * @param mixed $list List.
	 * @param mixed $args Arguments or delimiter.
	 * @param bool $constringent Light cast.
	 *
	 * @args string $delimiter Delimiter.
	 * @args bool $trim Trim.
	 * @args bool $unique Unique.
	 * @args bool $sort Sort output.
	 * @args string $cast Cast to type.
	 * @args mixed $min Minimum value.
	 * @args mixed $max Maximum value.
	 *
	 * @return array List.
	 */
	public static function list_to_array($list, $args=null, bool $constringent=false) {
		ref\format::list_to_array($list, $args, $constringent);
		return $list;
	}

	/**
	 * Money (USD)
	 *
	 * @param float $value Value.
	 * @param bool $cents Return sub-$1 values with ¢.
	 * @param string $separator Separator.
	 * @param bool $no00 Remove trailing cents if none.
	 * @return string Value.
	 */
	public static function money($value=0, bool $cents=false, $separator='', bool $no00=false) {
		ref\format::money($value, $cents, $separator, $no00);
		return $value;
	}

	/**
	 * Number to IP
	 *
	 * @param string $ip Decimal.
	 * @return string|bool IP or false.
	 */
	public static function number_to_ip($ip) {
		ref\format::number_to_ip($ip);
		return $ip;
	}

	/**
	 * Phone
	 *
	 * @param string $str Phone.
	 * @param string $country Country.
	 * @param array $types Types, e.g. Mobile.
	 * @return string Phone in International Format.
	 */
	public static function phone($str='', $country='', $types=array()) {
		ref\format::phone($str, $country, $types);
		return $str;
	}

	/**
	 * Round w/ Precision
	 *
	 * @param float $num Number.
	 * @param int $precision Precision.
	 * @param int $mode Mode.
	 * @return float Number.
	 */
	public static function round($num, int $precision=0, int $mode=PHP_ROUND_HALF_UP) {
		ref\format::round($num, $precision, $mode);
		return $num;
	}

	/**
	 * Generate CSV from Data
	 *
	 * @param array $data Data (row=>cells).
	 * @param array $headers Headers.
	 * @param string $delimiter Delimiter.
	 * @param string $eol Line ending type.
	 * @return string CSV content.
	 */
	public static function to_csv($data=null, $headers=null, string $delimiter=',', string $eol="\n") {
		ref\cast::array($data);
		$data = array_values(array_filter($data, 'is_array'));
		ref\cast::array($headers);

		$out = array();

		// Grab headers from data?
		if (
			!count($headers) &&
			count($data) &&
			(cast::array_type($data[0]) === 'associative')
		) {
			$headers = array_keys($data[0]);
		}

		// Output headers, if applicable.
		if (count($headers)) {
			foreach ($headers as $k=>$v) {
				ref\cast::string($headers[$k], true);
			}

			ref\sanitize::csv($headers, true);

			$out[] = '"' . implode('"' . $delimiter . '"', $headers) . '"';
		}

		// Output data.
		if (count($data)) {
			foreach ($data as $line) {
				foreach ($line as $k=>$v) {
					ref\cast::string($line[$k], true);
				}

				ref\sanitize::csv($line, true);

				$out[] = '"' . implode('"' . $delimiter . '"', $line) . '"';
			}
		}

		return implode($eol, $out);
	}

	/**
	 * Convert Timezones
	 *
	 * @param string $date Date.
	 * @param string $from Original Timezone.
	 * @param string $to New Timezone.
	 * @return string Date.
	 */
	public static function to_timezone(string $date, $from='UTC', $to='UTC') {
		ref\format::to_timezone($date, $from, $to);
		return $date;
	}

	/**
	 * Generate XLS from Data
	 *
	 * This uses Microsoft's XML spreadsheet format.
	 *
	 * @param array $data Data (row=>cells).
	 * @param array $headers Headers.
	 * @return string XLS content.
	 */
	public static function to_xls($data=null, $headers=null) {
		ref\cast::array($data);
		$data = array_values(array_filter($data, 'is_array'));
		ref\cast::array($headers);

		// @codingStandardsIgnoreStart
		$out = array(
			'<?xml version="1.0" encoding="UTF-8"?><?mso-application progid="Excel.Sheet"?>',
			'<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40">',
			'<Styles>',
			'<Style ss:ID="s0">',
			'<NumberFormat ss:Format="True/False"/>',
			'</Style>',
			'<Style ss:ID="s1">',
			'<NumberFormat ss:Format="General Date"/>',
			'</Style>',
			'<Style ss:ID="s2">',
			'<NumberFormat ss:Format="Short Date"/>',
			'</Style>',
			'<Style ss:ID="s3">',
			'<NumberFormat ss:Format="Long Time"/>',
			'</Style>',
			'<Style ss:ID="s4">',
			'<NumberFormat ss:Format="Percent"/>',
			'</Style>',
			'<Style ss:ID="s5">',
			'<NumberFormat ss:Format="Currency"/>',
			'</Style>',
			'</Styles>',
			'<Worksheet>',
			'<Table>',
			'<Column ss:Index="1" ss:AutoFitWidth="0" ss:Width="110"/>'
		);
		// @codingStandardsIgnoreEnd

		// Grab headers from data?
		if (
			!count($headers) &&
			count($data) &&
			(cast::array_type($data[0]) === 'associative')
		) {
			$headers = array_keys($data[0]);
		}

		// Output headers, if applicable.
		if (count($headers)) {
			foreach ($headers as $k=>$v) {
				ref\cast::string($headers[$k], true);
			}

			$out[] = '<Row>';
			foreach ($headers as $cell) {
				$cell = htmlspecialchars(
					strip_tags(
						sanitize::quotes(
							sanitize::whitespace($cell, 0, true),
							true
						)
					),
					ENT_XML1 | ENT_NOQUOTES,
					'UTF-8'
				);
				$out[] = '<Cell><Data ss:Type="String"><b>' . $cell . '</b></Data></Cell>';
			}
			$out[] = '</Row>';
		}

		// Output data.
		if (count($data)) {
			foreach ($data as $line) {
				$out[] = '<Row>';
				foreach ($line as $cell) {
					// Different types of data need to be treated differently.
					$type = gettype($cell);
					$format = null;
					if ('boolean' === $type || 'bool' === $type) {
						$type = 'Boolean';
						$format = '0';
						$cell = $cell ? 1 : 0;
					}
					elseif (is_numeric($cell)) {
						$type = 'Number';
						ref\cast::number($cell);
					}
					else {
						ref\cast::string($cell, true);
						ref\sanitize::whitespace($cell, 2, true);

						// Date and time.
						if (preg_match('/^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}$/', $cell)) {
							$type = 'DateTime';
							$format = '1';
							$cell = str_replace(' ', 'T', $cell);
						}
						// Date.
						elseif (preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $cell)) {
							$type = 'DateTime';
							$format = '2';
							$cell .= 'T00:00:00';
						}
						// Time.
						elseif (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $cell)) {
							$type = 'DateTime';
							$format = '3';
							$cell = "0000-00-00T$cell";
							if (substr_count($cell, ':') === 2) {
								$cell .= ':00';
							}
						}
						// Percent.
						elseif (preg_match('/^\-?[\d,]*\.?\d+%$/', $cell)) {
							$type = 'Number';
							$format = '4';
							ref\cast::number($cell);
						}
						// Currency.
						elseif (preg_match('/^\-\$?[\d,]*\.?\d+$/', $cell) || preg_match('/^\-?[\d,]*\.?\d+¢$/', $cell)) {
							$type = 'Number';
							$format = '5';
							ref\cast::number($cell);
						}
						// Everything else.
						else {
							$type = 'String';
							$cell = htmlspecialchars(
								strip_tags(
									sanitize::quotes($cell, true)
								),
								ENT_XML1 | ENT_NOQUOTES,
								'UTF-8'
							);
						}
					}

					$out[] = '<Cell' . (!is_null($format) ? ' ss:StyleID="s' . $format . '"' : '') . '><Data ss:Type="' . $type . '">' . $cell . '</Data></Cell>';
				}
				$out[] = '</Row>';
			}
		}

		// Close it off.
		$out[] = '</Table>';
		$out[] = '</Worksheet>';
		$out[] = '</Workbook>';

		return implode("\r\n", $out);
	}
}


