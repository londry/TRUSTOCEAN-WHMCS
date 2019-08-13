<?php
/**
 * CLI
 *
 * CLI helpers.
 *
 * @package blobfolio/common
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\common;

class cli {

	/**
	 * Is CLI?
	 *
	 * Check if a script is running in CLI mode.
	 *
	 * @return bool True/false.
	 */
	public static function is_cli() {
		return ('cli' === \php_sapi_name());
	}

	/**
	 * Is Root?
	 *
	 * Check if the script is being run with root privilegs.
	 *
	 * Note: this does not work on Windows.
	 *
	 * @return bool True/false.
	 */
	public static function is_root() {
		if (\function_exists('posix_getuid')) {
			return (0 === \posix_getuid());
		}

		return false;
	}

	/**
	 * Colorize String
	 *
	 * Apply sexy BASH formatting to a string. This accepts a variable
	 * number of arguments, each one a substring and any style codes to
	 * apply to it. If an argument is a string, no styles will be
	 * applied. Otherwise if it is an array, the first value should be
	 * the string, and all subsequent values are considered codes.
	 *
	 * Example: "hello"
	 * Example: ["hello", 1]
	 * Example: ["hello", [1, 32]]
	 * Example: ["hello", 1, 32]
	 *
	 * @see {https://misc.flogisoft.com/bash/tip_colors_and_formatting}
	 *
	 * @param mixed $args Arguments.
	 * @return string Colorized string.
	 */
	public static function colorize($args) {
		$args = \func_get_args();
		$out = '';

		foreach ($args as $v) {
			ref\cast::array($v);
			if (! \count($v)) {
				continue;
			}

			// The string comes first.
			$str = \array_shift($v);
			ref\cast::string($str, true);

			// Deal with codes.
			ref\format::array_flatten($v);
			$codes = \array_filter($v, 'is_numeric');
			if (\count($codes)) {
				ref\cast::int($codes);
				$out .= "\033[" . \implode(';', $codes) . "m{$str}\033[0m";
			}
			else {
				$out .= $str;
			}
		}

		return $out;
	}
}
