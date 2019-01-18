<?php
/**
 * Multi-Byte - By Reference
 *
 * Functions for multi-byte string handling.
 *
 * @package blobfolio/common
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\common\ref;

use \blobfolio\common\constants;
use \blobfolio\common\mb as v_mb;

class mb {

	/**
	 * Wrapper For str_pad()
	 *
	 * @param string $str String.
	 * @param int $pad_length Pad length.
	 * @param string $pad_string Pad string.
	 * @param int $pad_type Pad type.
	 * @param bool $constringent Light cast.
	 * @return bool True/false.
	 */
	public static function str_pad(&$str='', int $pad_length, $pad_string=' ', int $pad_type=STR_PAD_RIGHT, bool $constringent=false) {
		cast::constringent($str, $constringent);
		cast::string($pad_string, true);

		$current_length = v_mb::strlen($str, true);
		$pad_string_length = v_mb::strlen($pad_string, true);

		if ($pad_length <= $current_length || !$pad_string_length) {
			return true;
		}

		// Pad left.
		if (STR_PAD_LEFT === $pad_type) {
			$str = str_repeat($pad_string, ceil(($pad_length - $current_length) / $pad_string_length)) . $str;
			$new_length = v_mb::strlen($str, true);
			if ($new_length > $pad_length) {
				$str = v_mb::substr($str, $new_length - $pad_length, null, true);
			}
		}
		// Pad both.
		elseif (STR_PAD_BOTH === $pad_type) {
			$leftright = 'right';
			while (v_mb::strlen($str, true) < $pad_length) {
				$leftright = 'left' === $leftright ? 'right' : 'left';
				if ('left' === $leftright) {
					$str = "{$pad_string}{$str}";
				}
				else {
					$str .= $pad_string;
				}
			}

			$new_length = v_mb::strlen($str, true);
			if ($new_length > $pad_length) {
				if ('left' === $leftright) {
					$str = v_mb::substr($str, $new_length - $pad_length, null, true);
				}
				else {
					$str = v_mb::substr($str, 0, $pad_length, true);
				}
			}
		}
		// Pad right.
		else {
			$str .= str_repeat($pad_string, ceil(($pad_length - $current_length) / $pad_string_length));
			$new_length = v_mb::strlen($str, true);
			if ($new_length > $pad_length) {
				$str = v_mb::substr($str, 0, $pad_length, true);
			}
		}

		return true;
	}

	/**
	 * Wrapper For str_split()
	 *
	 * @param string $str String.
	 * @param int $split_length Split length.
	 * @param bool $constringent Light cast.
	 * @return bool True/false.
	 */
	public static function str_split(&$str, int $split_length=1, bool $constringent=false) {
		if ($split_length < 1) {
			$str = false;
			return false;
		}

		cast::constringent($str, $constringent);

		$str_length = v_mb::strlen($str, true);
		$out = array();

		for ($i = 0; $i < $str_length; $i += $split_length) {
			$out[] = v_mb::substr($str, $i, $split_length, true);
		}

		$str = $out;
		return true;
	}

	/**
	 * Wrapper For strrev()
	 *
	 * @param string $str String.
	 * @param bool $constringent Light cast.
	 * @return bool True/false.
	 */
	public static function strrev(&$str, bool $constringent=false) {
		cast::constringent($str, $constringent);

		if (!$str) {
			return false;
		}

		preg_match_all('/./us', $str, $arr);
		$str = implode('', array_reverse($arr[0]));

		return true;
	}

	/**
	 * Wrapper For strtolower()
	 *
	 * This will catch various case-able Unicode beyond the native PHP
	 * functions.
	 *
	 * @param string $str String.
	 * @param bool $strict Strict.
	 * @param bool $constringent Light cast.
	 * @return string String.
	 */
	public static function strtolower(&$str='', bool $strict=false, bool $constringent=false) {
		if (is_array($str)) {
			foreach ($str as $k=>$v) {
				static::strtolower($str[$k], $strict);
			}
		}
		elseif (!$strict || is_string($str)) {
			cast::constringent($str, $constringent);

			if ($str) {
				if (
					function_exists('mb_strtolower') &&
					(
						!function_exists('mb_check_encoding') ||
						!mb_check_encoding($str, 'ASCII')
					)
				) {
					$str = mb_strtolower($str, 'UTF-8');

					// Replace some extra characters.
					$from = array_keys(constants::CASE_CHARS);
					$to = array_values(constants::CASE_CHARS);
					$str = str_replace($from, $to, $str);
				}
				else {
					$str = strtolower($str);
				}
			}
		}

		return true;
	}

	/**
	 * Wrapper For strtoupper()
	 *
	 * This will catch various case-able Unicode beyond the native PHP
	 * functions.
	 *
	 * @param string $str String.
	 * @param bool $strict Strict.
	 * @param bool $constringent Light cast.
	 * @return string String.
	 */
	public static function strtoupper(&$str='', bool $strict=false, bool $constringent=false) {
		if (is_array($str)) {
			foreach ($str as $k=>$v) {
				static::strtoupper($str[$k], $strict);
			}
		}
		elseif (!$strict || is_string($str)) {
			cast::constringent($str, $constringent);

			if ($str) {
				if (
					function_exists('mb_strtoupper') &&
					(
						!function_exists('mb_check_encoding') ||
						!mb_check_encoding($str, 'ASCII')
					)
				) {
					$str = mb_strtoupper($str, 'UTF-8');

					// Replace some extra characters.
					$to = array_keys(constants::CASE_CHARS);
					$from = array_values(constants::CASE_CHARS);
					$str = str_replace($from, $to, $str);
				}
				else {
					$str = strtoupper($str);
				}
			}
		}

		return true;
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
	public static function trim(&$str='', bool $constringent=false) {
		if (is_array($str)) {
			foreach ($str as $k=>$v) {
				static::trim($str[$k]);
			}
		}
		else {
			cast::constringent($str, $constringent);

			$str = preg_replace('/^\s+/u', '', $str);
			$str = preg_replace('/\s+$/u', '', $str);
		}

		return true;
	}

	/**
	 * Wrapper For ucfirst()
	 *
	 * This will catch various case-able Unicode beyond the native PHP
	 * functions.
	 *
	 * @param string $str String.
	 * @param bool $strict Strict.
	 * @param bool $constringent Light cast.
	 * @return string String.
	 */
	public static function ucfirst(&$str='', bool $strict=false, bool $constringent=false) {
		if (is_array($str)) {
			foreach ($str as $k=>$v) {
				static::ucfirst($str[$k], $strict);
			}
		}
		elseif (!$strict || is_string($str)) {
			cast::constringent($str, $constringent);

			if ($str) {
				if (
					function_exists('mb_substr') &&
					(
						!function_exists('mb_check_encoding') ||
						!mb_check_encoding($str, 'ASCII')
					)
				) {
					$first = v_mb::substr($str, 0, 1, true);
					static::strtoupper($first, false, true);
					$str = $first . v_mb::substr($str, 1, null, true);
				}
				else {
					$str = ucfirst($str);
				}
			}
		}

		return true;
	}

	/**
	 * Wrapper For ucwords()
	 *
	 * This will catch various case-able Unicode beyond the native PHP
	 * functions.
	 *
	 * @param string $str String.
	 * @param bool $strict Strict.
	 * @param bool $constringent Light cast.
	 * @return string String.
	 */
	public static function ucwords(&$str='', bool $strict=false, bool $constringent=false) {
		if (is_array($str)) {
			foreach ($str as $k=>$v) {
				static::ucwords($str[$k], $strict);
			}
		}
		elseif (!$strict || is_string($str)) {
			cast::constringent($str, $constringent);

			if ($str) {
				// Don't use the built-in case functions as those
				// kinda suck. Instead let's adjust manually.
				$extra = array();

				// The first letter.
				preg_match_all('/^(\p{L})/u', $str, $matches);
				if (count($matches[0])) {
					static::strtoupper($matches[1][0], false, true);
					if ($matches[0][0] !== $matches[1][0]) {
						$extra[$matches[0][0]] = $matches[1][0];
					}
				}

				// Any letter following a dash, space, or forward slash.
				preg_match_all('/(\s|\p{Pd}|\/)(.)/u', $str, $matches);
				if (count($matches[0])) {
					foreach ($matches[0] as $k=>$v) {
						static::strtoupper($matches[2][$k], false, true);
						$new = $matches[1][$k] . $matches[2][$k];
						if ($v !== $new) {
							$extra[$v] = $new;
						}
					}
				}

				// Make replacement(s).
				if (count($extra)) {
					$extra = array_unique($extra);
					$str = str_replace(array_keys($extra), array_values($extra), $str);
				}
			}
		}

		return true;
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
	 * @return bool True.
	 */
	public static function wordwrap(&$str, int $width=75, $break="\n", bool $cut=false, bool $constringent=false) {
		cast::constringent($str, $constringent);
		cast::string($break, true);

		// Bad data?
		if (!$str || $width <= 0) {
			return true;
		}

		// No mbstring?
		if (!function_exists('mb_substr')) {
			$str = wordwrap($str, $width, $break, $cut);
			return true;
		}

		// First, split on horizontal whitespace.
		$chunks = preg_split('/([\s$]+)/uS', trim($str), -1, PREG_SPLIT_DELIM_CAPTURE);
		$lines = array('');
		$line = 0;

		// Loop through chunks.
		foreach ($chunks as $v) {
			// Always start a new line with vertical whitespace.
			if (preg_match('/\v/u', $v)) {
				$line++;
				$lines[$line] = $v;
				$line++;
				$lines[$line] = '';
				continue;
			}

			// Always append horizontal whitespace.
			if (preg_match('/\h/u', $v)) {
				$lines[$line] .= $v;
				continue;
			}

			// Start a new line?
			$line_length = v_mb::strlen($lines[$line], true);
			if ($line_length >= $width) {
				$line++;
				$lines[$line] = '';
				$line_length = 0;
			}

			$word_length = v_mb::strlen($v, true);

			// We can just add it.
			if ($word_length + $line_length <= $width) {
				$lines[$line] .= $v;
				continue;
			}

			// We should make sure each chunk fits.
			if ($cut) {
				static::str_split($v, $width);
				$v = implode("\n", $v);
			}

			// Is this word hyphenated or dashed?
			$v = preg_replace('/(\p{Pd})\n/u', '$1', $v);
			$v = preg_replace('/(\p{Pd}+)/u', "$1\n", $v);
			static::trim($v);

			// Loop through word chunks to see what fits where.
			$v = explode("\n", $v);
			foreach ($v as $v2) {
				$word_length = v_mb::strlen($v2, true);
				$line_length = v_mb::strlen($lines[$line], true);

				// New line?
				if ($word_length + $line_length > $width) {
					$line++;
					$lines[$line] = '';
				}

				$lines[$line] .= $v2;
			}
		}

		// Okay, let's trim our lines real quick.
		foreach ($lines as $k=>$v) {
			// Ignore vertical space, unless it matches the breaker.
			if (preg_match('/\v/u', $v)) {
				// Don't need to double it.
				if ($v === $break) {
					unset($lines[$k]);
				}
				$lines[$k] = preg_replace('/^' . preg_quote($break, '/') . '/ui', '', $v);
				continue;
			}

			static::trim($lines[$k], true);
		}

		// Finally, join our lines by the delimiter.
		$str = implode($break, $lines);
		static::trim($str, true);

		return true;
	}
}
