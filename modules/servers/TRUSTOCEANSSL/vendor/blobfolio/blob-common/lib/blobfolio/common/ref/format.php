<?php
/**
 * Formatting - By Reference
 *
 * Functions for formatting data.
 *
 * @package blobfolio/common
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\common\ref;

use blobfolio\common\bc;
use blobfolio\common\constants;
use blobfolio\common\data;
use blobfolio\common\file as v_file;
use blobfolio\common\format as v_format;
use blobfolio\common\mb as v_mb;
use blobfolio\common\sanitize as v_sanitize;
use blobfolio\domain\domain;
use blobfolio\phone\phone;

class format {

	/**
	 * Flatten Multi-Dimensional Array
	 *
	 * Like array_values(), but move child values into the single (main)
	 * level.
	 *
	 * @param array $arr Array.
	 * @return void Nothing.
	 */
	public static function array_flatten(&$arr) {
		$out = array();

		cast::array($arr);
		foreach ($arr as $v) {
			// Recurse arrays.
			if (\is_array($v)) {
				static::array_flatten($v);
				foreach ($v as $v2) {
					$out[] = $v2;
				}
			}
			else {
				$out[] = $v;
			}
		}

		$arr = $out;
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
	 * @return void Nothing.
	 */
	public static function array_to_indexed(&$arr) {
		cast::array($arr);
		if (\count($arr)) {
			$out = array();
			foreach ($arr as $k=>$v) {
				$out[] = array(
					'key'=>$k,
					'value'=>$v,
				);
			}
			$arr = $out;
		}
	}

	/**
	 * Ceil w/ Precision
	 *
	 * @param float $num Number.
	 * @param int $precision Precision.
	 * @return void Nothing.
	 */
	public static function ceil(&$num, int $precision=0) {
		if (\is_array($num)) {
			foreach ($num as $k=>$v) {
				static::ceil($num[$k], $precision);
			}
		}
		else {
			cast::float($num, true);
			sanitize::to_range($precision, 0);

			$precision = (10 ** $precision);
			$num = \ceil($num * $precision) / $precision;
		}
	}

	/**
	 * Decode JS Entities
	 *
	 * Decode escape and unicode chars.
	 *
	 * @param string $str String.
	 * @return void Nothing.
	 */
	public static function decode_js_entities(&$str) {
		static::decode_unicode_entities($str);
		static::decode_escape_entities($str);
	}

	/**
	 * Decode Escape Entities
	 *
	 * Decode \b, \f, \n, \r, \t.
	 *
	 * @param string $str String.
	 * @return void Nothing.
	 */
	public static function decode_escape_entities(&$str) {
		cast::string($str, true);

		$replacements = array(
			'\b'=>\chr(0x08),
			'\f'=>\chr(0x0C),
			'\n'=>\chr(0x0A),
			'\r'=>\chr(0x0D),
			'\t'=>\chr(0x09),
		);
		$str = \str_replace(
			\array_keys($replacements),
			\array_values($replacements),
			$str
		);
	}

	/**
	 * Decode Unicode Entities
	 *
	 * Decode \u1234 into chars.
	 *
	 * @param string $str String.
	 * @return void Nothing.
	 */
	public static function decode_unicode_entities(&$str) {
		cast::string($str, true);

		$last = '';
		while ($str !== $last) {
			$last = $str;

			$str = \preg_replace_callback(
				'/\\\u([0-9A-Fa-f]{4})/u',
				array(static::class, 'decode_entities_hex'),
				$str
			);

			cast::string($str, true);
		}
	}

	/**
	 * Decode HTML Entities
	 *
	 * Decode all HTML entities back into their char counterparts,
	 * recursively until every last one is captured.
	 *
	 * @param string $str String.
	 * @return void Nothing.
	 */
	public static function decode_entities(&$str) {
		cast::string($str, true);

		$last = '';
		while ($str !== $last) {
			$last = $str;

			$str = \html_entity_decode($str, \ENT_QUOTES, 'UTF-8');
			$str = \preg_replace_callback('/&#([0-9]+);/', array(static::class, 'decode_entities_chr'), $str);
			$str = \preg_replace_callback('/&#[Xx]([0-9A-Fa-f]+);/', array(static::class, 'decode_entities_hex'), $str);

			cast::string($str, true);
		}
	}

	/**
	 * Decode HTML Entities Callback - Chr
	 *
	 * @param array $matches Matches.
	 * @return string ASCII.
	 */
	protected static function decode_entities_chr($matches) {
		return \chr($matches[1]);
	}

	/**
	 * Decode HTML Entities Callback - Hex
	 *
	 * @param array $matches Matches.
	 * @return string ASCII.
	 */
	protected static function decode_entities_hex($matches) {
		return \chr(\hexdec($matches[1]));
	}

	/**
	 * Floor w/ Precision
	 *
	 * @param float $num Number.
	 * @param int $precision Precision.
	 * @return void Nothing.
	 */
	public static function floor(&$num, int $precision=0) {
		if (\is_array($num)) {
			foreach ($num as $k=>$v) {
				static::floor($num[$k], $precision);
			}
		}
		else {
			cast::float($num, true);
			sanitize::to_range($precision, 0);

			$precision = (10 ** $precision);
			$num = \floor($num * $precision) / $precision;
		}
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
	 * @return void Nothing.
	 */
	public static function fraction(&$num, float $precision=0.0001) {
		if (\is_array($num)) {
			foreach ($num as $k=>$v) {
				static::fraction($num[$k], $precision);
			}
		}
		else {
			cast::float($num, true);

			// We need a tolerable tolerance.
			if ($precision <= 0 || $precision >= 1) {
				$num = '';
				return;
			}

			// We don't have to work very hard to calculate zero.
			if (0.0 === $num) {
				$num = '0';
				return;
			}

			// We'll have to add the negative sign on at the end.
			$negative = $num < 0;
			$num = \abs($num);

			$numerator = 1;
			$h2 = $denominator = 0;
			$k2 = 1;
			$b = 1 / $num;
			do {
				$b = 1 / $b;
				$a = \floor($b);
				$aux = $numerator;
				$numerator = $a * $numerator + $h2;
				$h2 = $aux;
				$aux = $denominator;
				$denominator = $a * $denominator + $k2;
				$k2 = $aux;
				$b = $b - $a;
			} while (\abs($num - $numerator / $denominator) > $num * $precision);

			// If the denominator is one, just return a whole number.
			if (1.0 === $denominator) {
				$num = "$numerator";
			}
			// Otherwise fractionize it.
			else {
				$num = "{$numerator}/{$denominator}";
			}

			// Add the negative sign back if needed.
			if ($negative) {
				$num = "-{$num}";
			}
		}
	}

	/**
	 * IP to Number
	 *
	 * @param string $ip IP.
	 * @return bool True/false.
	 */
	public static function ip_to_number(&$ip) {
		// Don't need to fancy cast.
		if (! \is_string($ip)) {
			$ip = false;
			return false;
		}

		if (! \filter_var($ip, \FILTER_VALIDATE_IP)) {
			$ip = false;
			return false;
		}

		// IPv4 is easy.
		if (\filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4)) {
			$ip = \ip2long($ip);
			return true;
		}

		// IPv6 is a little more roundabout.
		if (\filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6)) {
			try {
				$ip_n = \inet_pton($ip);

				$bin = '';
				$length = \strlen($ip_n) - 1;
				for ($bit = $length; $bit >= 0; $bit--) {
					$bin = \sprintf('%08b', \ord($ip_n[$bit])) . $bin;
				}

				if (\function_exists('gmp_init')) {
					$ip = \gmp_strval(\gmp_init($bin, 2), 10);
					return true;
				}

				$ip = bc::bindec($bin);
				return true;
			} catch (\Throwable $e) {
				$ip = false;
				return false;
			}
		}

		$ip = false;
		return false;
	}

	/**
	 * IP to Subnet
	 *
	 * This assumes the standard ranges of 24 for IPv4 and 64 for IPv6.
	 *
	 * @param string $ip IP.
	 * @return bool True/false.
	 */
	public static function ip_to_subnet(&$ip) {
		sanitize::ip($ip, true, false);

		// Not an IP.
		if (! $ip) {
			$ip = false;
			return false;
		}
		// IPv4, as always, easy.
		elseif (\filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4)) {
			// Find the minimum IP (simply last chunk to 0).
			$bits = \explode('.', $ip);
			$bits[3] = 0;
			$ip = \implode('.', $bits) . '/24';
		}
		// IPv6, more annoying.
		else {
			// Find the minimum IP (last 64 bytes to 0).
			$bits = \explode(':', $ip);
			for ($x = 4; $x <= 7; ++$x) {
				$bits[$x] = 0;
			}
			$ip = v_sanitize::ip(\implode(':', $bits), true) . '/64';
		}

		return true;
	}

	/**
	 * JSON
	 *
	 * Fix JSON formatting.
	 *
	 * @param string $str String.
	 * @param bool $pretty Pretty.
	 * @return bool True/false.
	 */
	public static function json(&$str, $pretty=true) {
		if (! \is_string($str)) {
			static::json_encode($str);
		}

		if (false === ($decode = v_format::json_decode($str))) {
			$str = null;
			return false;
		}

		if ($pretty) {
			$str = \json_encode($decode, \JSON_PRETTY_PRINT);
		}
		else {
			$str = \json_encode($decode);
		}
		return true;
	}

	/**
	 * JSON Decode
	 *
	 * A more robust version of JSON decode that can somewhat handle
	 * general Javascript objects. This always returns objecty things as
	 * associative arrays.
	 *
	 * @param string $str String.
	 * @return bool True/false.
	 */
	public static function json_decode(&$str) {
		cast::string($str, true);

		// Remove comments.
		$str = \preg_replace(
			array(
				// Single line //.
				'#^\s*//(.+)$#m',
				// Multi-line /* */.
				'#^\s*/\*(.+)\*/#Us',
				'#/\*(.+)\*/\s*$#Us',
			),
			'',
			$str
		);

		// Trim it.
		mb::trim($str);

		// Is it empty?
		if (! $str || ("''" === $str) || ('""' === $str)) {
			$str = '';
			return true;
		}

		// Maybe it just works?
		$tmp = \json_decode($str, true);
		if (null !== $tmp) {
			$str = $tmp;
			return true;
		}

		$lower = v_mb::strtolower($str, false);
		// Bool.
		if ('true' === $lower || 'false' === $lower) {
			cast::bool($str, true);
			return true;
		}
		// Null.
		elseif ('null' === $lower) {
			$str = null;
			return true;
		}
		// Number.
		elseif (\is_numeric($lower)) {
			if (false !== \strpos($lower, '.')) {
				$str = (float) $lower;
			}
			else {
				$str = (int) $lower;
			}
			return true;
		}
		// String.
		elseif (\preg_match('/^("|\')(.+)(\1)$/s', $str, $match) && ($match[1] === $match[3])) {
			$str = $match[2];
			static::decode_js_entities($str);
			return true;
		}
		// Bail if we don't have an object at this point.
		elseif (! \preg_match('/^\[.*\]$/s', $str) && ! \preg_match('/^\{.*\}$/s', $str)) {
			$str = null;
			return false;
		}

		// Start building an array.
		$slices = array(
			array(
				'type'=>'slice',
				'from'=>0,
				'delimiter'=>false,
			),
		);
		$out = array();
		if (0 === \strpos($str, '[')) {
			$type = 'array';
		}
		else {
			$type = 'object';
		}
		$chunk = v_mb::substr($str, 1, -1);
		$length = v_mb::strlen($chunk);
		for ($x = 0; $x <= $length; ++$x) {
			$last = \end($slices);
			$subchunk = v_mb::substr($chunk, $x, 2);

			// A comma or the end.
			if (
				($x === $length) ||
				((',' === $chunk{$x}) && 'slice' === $last['type'])
			) {
				$slice = v_mb::substr($chunk, $last['from'], ($x - $last['from']));
				$slices[] = array(
					'type'=>'slice',
					'from'=>$x + 1,
					'delimiter'=>false,
				);

				// Arrays are straightforward, just pop it in.
				if ('array' === $type) {
					$out[] = v_format::json_decode($slice);
				}
				// Objects need key/value separation.
				else {
					// Key is quoted.
					if (\preg_match('/^\s*(["\'].*[^\\\]["\'])\s*:\s*(\S.*),?$/Uis', $slice, $parts)) {
						$key = v_format::json_decode($parts[1]);
						$val = v_format::json_decode($parts[2]);
						$out[$key] = $val;
					}
					// Key is unquoted.
					elseif (\preg_match('/^\s*(\w+)\s*:\s*(\S.*),?$/Uis', $slice, $parts)) {
						$key = $parts[1];
						static::decode_js_entities($key);
						$val = v_format::json_decode($parts[2]);
						$out[$key] = $val;
					}
				}
			}
			// A new quote.
			elseif (
				(('"' === $chunk{$x}) || ("'" === $chunk{$x})) &&
				('string' !== $last['type'])
			) {
				$slices[] = array(
					'type'=>'string',
					'from'=>$x,
					'delimiter'=>$chunk{$x},
				);
			}
			// An end quote.
			elseif (
				($chunk{$x} === $last['delimiter']) &&
				('string' === $last['type']) &&
				(
					('\\' !== $chunk{$x - 1}) ||
					(('\\' === $chunk{$x - 1}) && ('\\' === $chunk{$x - 2}))
				)
			) {
				\array_pop($slices);
			}
			// Opening bracket (and we're in a slice/objectish thing.
			elseif (
				('[' === $chunk{$x}) &&
				\in_array($last['type'], array('slice', 'array', 'object'), true)
			) {
				$slices[] = array(
					'type'=>'array',
					'from'=>$x,
					'delimiter'=>false,
				);
			}
			// Closing bracket.
			elseif (
				(']' === $chunk{$x}) &&
				('array' === $last['type'])
			) {
				\array_pop($slices);
			}
			// Opening brace (and we're in a slice/objectish thing.
			elseif (
				('{' === $chunk{$x}) &&
				\in_array($last['type'], array('slice', 'array', 'object'), true)
			) {
				$slices[] = array(
					'type'=>'object',
					'from'=>$x,
					'delimiter'=>false,
				);
			}
			// Closing brace.
			elseif (
				('}' === $chunk{$x}) &&
				('object' === $last['type'])
			) {
				\array_pop($slices);
			}
			// Opening comment.
			elseif (
				('/*' === $subchunk) &&
				\in_array($last['type'], array('slice', 'array', 'object'), true)
			) {
				$slices[] = array(
					'type'=>'comment',
					'from'=>$x,
					'delimiter'=>false,
				);
				++$x;
			}
			// Closing comment.
			elseif (
				('*/' === $subchunk) &&
				('comment' === $last['type'])
			) {
				\array_pop($slices);
				++$x;
				for ($y = $last['from']; $y <= $x; ++$y) {
					$chunk{$y} = ' ';
				}
			}
		}// End each char.

		$str = $out;
		return true;
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
	 * @return void Nothing.
	 */
	public static function json_encode(&$value, $options=0, $depth=512) {
		// Simple values don't require a lot of thought.
		if (! $value || \is_numeric($value) || \is_bool($value)) {
			$value = \json_encode($value, $options, $depth);
			return;
		}

		$original = $value;
		$value = \json_encode($value, $options, $depth);

		// Try again with UTF-8 sanitizing if this failed.
		if (null === $value) {
			sanitize::utf8($original);
			$value = \json_encode($original, $options, $depth);
		}
	}

	/**
	 * Linkify Text
	 *
	 * Make link-like text things clickable HTML links.
	 *
	 * @param string $str String.
	 * @param array $args Arguments.
	 * @param int $pass Pass (1=URL, 2=EMAIL).
	 *
	 * @arg array $class Class(es).
	 * @arg string $rel Rel.
	 * @arg string $target Target.
	 *
	 * @return void Nothing.
	 */
	public static function links(&$str, $args=null, int $pass=1) {
		cast::string($str, true);

		// Build link attributes from our arguments, if any.
		$defaults = array(
			'class'=>array(),
			'rel'=>'',
			'target'=>'',
		);
		$data = data::parse_args($args, $defaults);
		$data['class'] = \implode(' ', $data['class']);
		sanitize::html($data);
		$data = \array_filter($data, 'strlen');
		$atts = array();
		foreach ($data as $k=>$v) {
			$atts[] = "$k=\"$v\"";
		}
		$atts = \implode(' ', $atts);

		// Now look at the string.
		$str = \preg_split('/(<.+?>)/is', $str, 0, \PREG_SPLIT_DELIM_CAPTURE);
		$blacklist = \implode('|', constants::LINKS_BLACKLIST);
		$ignoring = false;
		foreach ($str as $k=>$v) {
			// Even keys exist between tags.
			if (0 === $k % 2) {
				// Skip it if we're waiting on a closing tag.
				if (false !== $ignoring) {
					continue;
				}

				// URL bits.
				switch ($pass) {
					// URL bits.
					case 1:
						// We can afford to be sloppy here, thanks to FDQN validation later.
						$str[$k] = \preg_replace_callback(
							'/((ht|f)tps?:\/\/[^\s\'"\[\]\(\){}]+|[^\s\'"\[\]\(\){}]*xn--[^\s\'"\[\]\(\){}]+|[@]?[\w\.]+\.[\w\.]{2,}[^\s]*)/ui',
							function($matches) use($atts) {
								$raw = $matches[1];

								// Don't do email bits.
								if (0 === \strpos($raw, '@')) {
									return $matches[1];
								}

								// We don't want trailing punctuation added to the link.
								if (\preg_match('/([^\w\/]+)$/ui', $raw, $suffix)) {
									$suffix = $suffix[1];
									$raw = \preg_replace('/([^\w\/]+)$/ui', '', $raw);
								}
								else {
									$suffix = '';
								}

								$link = v_mb::parse_url($raw);
								if (! \is_array($link) || ! isset($link['host'])) {
									return $matches[1];
								}

								// Only linkify FQDNs.
								$domain = new domain($link['host']);
								if (! $domain->is_valid() || ! $domain->is_fqdn()) {
									return $matches[1];
								}

								// Supply a scheme, if missing.
								if (! isset($link['scheme'])) {
									$link['scheme'] = 'http';
								}

								$link = v_file::unparse_url($link);
								if (\filter_var($link, \FILTER_SANITIZE_URL) !== $link) {
									return $matches[1];
								}

								// Finally, make a link!
								sanitize::html($link);
								return '<a href="' . $link . '"' . ($atts ? " $atts" : '') . '>' . $raw . '</a>' . $suffix;
							},
							$str[$k]
						);
						break;
					// Email address bits.
					case 2:
						// Again, we can be pretty careless here thanks to later checks.
						$str[$k] = \preg_replace_callback(
							'/([\w\.\!#\$%&\*\+\=\?_~]+@[^\s\'"\[\]\(\){}@]{2,})/ui',
							function($matches) use($atts) {
								$raw = $matches[1];

								// We don't want trailing punctuation added to the link.
								if (\preg_match('/([^\w]+)$/ui', $raw, $suffix)) {
									$suffix = $suffix[1];
									$raw = \preg_replace('/([^\w]+)$/ui', '', $raw);
								}
								else {
									$suffix = '';
								}

								$link = v_sanitize::email($raw);
								if (! $link) {
									return $matches[1];
								}

								// Finally, make a link!
								sanitize::html($link);

								return '<a href="mailto:' . $link . '"' . ($atts ? " $atts" : '') . '>' . $raw . '</a>' . $suffix;
							},
							$str[$k]
						);
						break;
					// Phone numbers.
					case 3:
						// Again, we can be pretty careless here thanks to later checks.
						$str[$k] = \preg_replace_callback(
							'/(\s)?(\+\d[\d\-\s]{5,}+|\(\d{3}\)\s[\d]{3}[\-\.\s]\d{4}|\d{3}[\-\.\s]\d{3}[\-\.\s]\d{4}|\+\d{7,})/ui',
							function($matches) use($atts) {
								$prefix = $matches[1];
								$raw = $matches[2];

								// We don't want trailing punctuation added to the link.
								if (\preg_match('/([^\d]+)$/ui', $raw, $suffix)) {
									$suffix = $suffix[1];
									$raw = \preg_replace('/([^\d]+)$/ui', '', $raw);
								}
								else {
									$suffix = '';
								}

								$link = v_format::phone($raw);
								$link = \preg_replace('/[^\d]/', '', $link);
								if (! $link) {
									return $matches[1] . $matches[2];
								}

								return $prefix . '<a href="tel:+' . $link . '"' . ($atts ? " $atts" : '') . '>' . $raw . '</a>' . $suffix;
							},
							$str[$k]
						);
						break;
				}
			}
			// Odd keys indicate a tag, opening or closing.
			else {
				// If we aren't already waiting on a closing tag...
				if (false === $ignoring) {
					// Start ignoring if this tag is blacklisted and not self-closing.
					if (\preg_match("/<($blacklist).*(?<!\/)>$/is", $str[$k], $matches)) {
						$ignoring = \preg_quote($matches[1], '/');
					}
				}
				// Otherwise wait until we find a corresponding closing tag.
				elseif (\preg_match("/<\/\s*$ignoring>/i", $str[$k], $matches)) {
					$ignoring = false;
				}
			}
		}
		$str = \implode($str);

		// Linkification is run in stages to prevent overlap issues.
		// Pass #1 is for URL-like bits, pass #2 for email addresses,
		// pass #3 for phone numbers.
		if (1 === $pass) {
			static::links($str, $args, 2);
		}
		elseif (2 === $pass) {
			static::links($str, $args, 3);
		}
	}

	/**
	 * List to Array
	 *
	 * Convert a delimited list into a proper array.
	 *
	 * @param mixed $list List.
	 * @param mixed $args Arguments or delimiter.
	 *
	 * @args string $delimiter Delimiter.
	 * @args bool $trim Trim.
	 * @args bool $unique Unique.
	 * @args bool $sort Sort output.
	 * @args string $cast Cast to type.
	 * @args mixed $min Minimum value.
	 * @args mixed $max Maximum value.
	 *
	 * @return void Nothing.
	 */
	public static function list_to_array(&$list, $args=null) {
		$out = array();

		// If the arguments are a string, we'll assume the delimiter was
		// passed.
		if (\is_string($args)) {
			$args = array('delimiter'=>$args);
		}

		$args = data::parse_args($args, constants::LIST_TO_ARRAY);

		// Sanitize cast type.
		$args['cast'] = \strtolower($args['cast']);
		if (
			('array' === $args['cast']) ||
			! isset(constants::CAST_TYPES[$args['cast']])
		) {
			$args['cast'] = 'string';
		}

		// Sanitize min/max.
		if (
			(null !== $args['min']) &&
			(null !== $args['max']) &&
			$args['min'] > $args['max']
		) {
			data::switcheroo($args['min'], $args['max']);
		}

		cast::array($list);
		foreach ($list as $k=>$v) {
			// Recurse if the value is an array.
			if (\is_array($list[$k])) {
				static::list_to_array($list[$k], $args);
			}
			// Otherwise de-list the line.
			else {
				cast::string($list[$k], true);

				if ($args['delimiter']) {
					$list[$k] = \explode($args['delimiter'], $list[$k]);
				}
				else {
					$list[$k] = mb::str_split($list[$k], 1);
				}

				// Trimming?
				if ($args['trim']) {
					mb::trim($list[$k]);
				}

				// Get rid of empties.
				$list[$k] = \array_filter($list[$k], 'strlen');

				// Casting?
				if ('string' !== $args['cast']) {
					cast::to_type($list[$k], $args['cast']);
				}
			}

			// Add whatever we've got to the running total.
			foreach ($list[$k] as $v2) {
				if (
					((null === $args['min']) || $v2 >= $args['min']) &&
					((null === $args['max']) || $v2 <= $args['max'])
				) {
					$out[] = $v2;
				}
			}
		}

		// Unique?
		if ($args['unique'] && \count($out)) {
			$out = \array_values(\array_unique($out));
		}

		// Sort?
		if ($args['sort'] && \count($out)) {
			\sort($out);
		}

		$list = $out;
	}

	/**
	 * Money (USD)
	 *
	 * @param float $value Value.
	 * @param bool $cents Return sub-$1 values with ¢.
	 * @param string $separator Separator.
	 * @param bool $no00 Remove trailing cents if none.
	 * @return void Nothing.
	 */
	public static function money(&$value=0, bool $cents=false, string $separator='', bool $no00=false) {
		if (\is_array($value)) {
			foreach ($value as $k=>$v) {
				static::money($value[$k], $cents, $separator, $no00);
			}
		}
		else {
			cast::float($value, true);

			$value = \round($value, 2);
			$negative = $value < 0;
			if ($negative) {
				$value = \abs($value);
			}

			if ($value >= 1 || false === $cents) {
				$value = ($negative ? '-' : '') . '$' . \number_format($value, 2, '.', $separator);
				if ($no00) {
					$value = \preg_replace('/\.00$/', '', $value);
				}
			}
			else {
				$value = ($negative ? '-' : '') . (100 * $value) . '¢';
			}
		}
	}

	/**
	 * Number to IP
	 *
	 * @param string $ip Decimal.
	 * @return bool True/false.
	 */
	public static function number_to_ip(&$ip) {
		// If we have a proper integer, we can assume PHP's native
		// function can handle it.
		if (\is_int($ip)) {
			$ip = \long2ip($ip);
			sanitize::ip($ip, true);
			return;
		}

		if (! \is_string($ip)) {
			if (\is_numeric($ip)) {
				$ip = (string) $ip;
			}
			else {
				$ip = false;
				return false;
			}
		}

		if (! $ip || ('0' === $ip)) {
			$ip = false;
			return false;
		}

		if (\function_exists('gmp_init')) {
			$bin = \gmp_strval(\gmp_init($ip, 10), 2);
			$bin = \sprintf('%0128s', $bin);
		}
		else {
			$bin = bc::decbin($ip, 128);
		}

		$chunk = array();
		for ($bit = 0; $bit <= 7; ++$bit) {
			$bin_part = \substr($bin, $bit * 16, 16);
			$chunk[] = \dechex(\bindec($bin_part));
		}
		$ip = \implode(':', $chunk);
		$ip = \inet_ntop(\inet_pton($ip));

		// Make sure IPv4 is normal.
		if (! $ip || '::' === $ip) {
			$ip = '0.0.0.0';
		}

		sanitize::ip($ip, true);
		return true;
	}

	/**
	 * Phone
	 *
	 * @param string $str Phone.
	 * @param string $country Country.
	 * @param array $types Types, e.g. Mobile.
	 * @return bool True.
	 */
	public static function phone(&$str, $country='', $types=array()) {
		if (\is_array($str)) {
			foreach ($str as $k=>$v) {
				static::phone($str[$k], $country, $types);
			}
		}
		else {
			if (! \is_string($str)) {
				if (\is_numeric($str)) {
					$str = (string) $str;
				}
				else {
					$str = '';
					return false;
				}
			}
			sanitize::whitespace($str, 0);

			if (! $str) {
				$str = '';
				return false;
			}

			if (! \is_string($country)) {
				$country = '';
			}
			cast::array($types);

			// We can only go further if blob-phone is installed.
			if (\class_exists('blobfolio\\phone\\phone')) {
				$str = new phone($str, $country);
				if (! $str->is_phone($types)) {
					$str = '';
					return false;
				}
			}
			else {
				$str = \preg_replace('/[^\d]/', '', $str);
			}

			$str = (string) $str;
		}

		return true;
	}

	/**
	 * Round w/ Precision
	 *
	 * @param float $num Number.
	 * @param int $precision Precision.
	 * @param int $mode Mode.
	 * @return void Nothing.
	 */
	public static function round(&$num, int $precision=0, int $mode=\PHP_ROUND_HALF_UP) {
		if (\is_array($num)) {
			foreach ($num as $k=>$v) {
				static::round($num[$k], $precision, $mode);
			}
		}
		else {
			cast::float($num, true);
			sanitize::to_range($precision, 0);

			$num = \round($num, $precision, $mode);
		}
	}

	/**
	 * Convert Timezones
	 *
	 * @param string $date Date.
	 * @param string $from Original Timezone.
	 * @param string $to New Timezone.
	 * @return void Nothing.
	 */
	public static function to_timezone(string &$date, $from='UTC', $to='UTC') {
		sanitize::datetime($date);
		if ('UTC' !== $from) {
			sanitize::timezone($from);
		}
		if ('UTC' !== $to) {
			sanitize::timezone($to);
		}

		if (('0000-00-00 00:00:00' === $date) || ($from === $to)) {
			return;
		}

		$original = $date;
		try {
			$date_new = new \DateTime($date, new \DateTimeZone($from));
			$date_new->setTimezone(new \DateTimeZone($to));
			$date = $date_new->format('Y-m-d H:i:s');
		} catch (\Throwable $e) {
			$date = $original;
		}
	}
}
