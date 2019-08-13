<?php
/**
 * Type Handling - By Reference
 *
 * Functions for typecasting and type detection. Magic fails
 * us here as we can't magically handle by-reference.
 *
 * @package blobfolio/common
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\common\ref;

use blobfolio\common\constants;
use blobfolio\common\data;

class cast {

	/**
	 * To Array
	 *
	 * @param mixed $value Variable.
	 * @return void Nothing.
	 */
	public static function array(&$value=null) {
		// Short circuit.
		if (\is_array($value)) {
			return;
		}

		try {
			$value = (array) $value;
		} catch (\Throwable $e) {
			$value = array();
		}
	}

	/**
	 * To Array
	 *
	 * @param mixed $value Variable.
	 * @return void Nothing.
	 */
	public static function to_array(&$value=null) {
		static::array($value);
	}

	/**
	 * To Bool
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return void Nothing.
	 */
	public static function bool(&$value=false, bool $flatten=false) {
		// Short circuit.
		if (\is_bool($value)) {
			return;
		}

		if (! $flatten && \is_array($value)) {
			foreach ($value as $k=>$v) {
				static::bool($value[$k]);
			}
		}
		else {
			// Evaluate special cases.
			if (\is_string($value)) {
				$value = \strtolower($value);
				if (\in_array($value, constants::TRUE_BOOLS, true)) {
					$value = true;
				}
				elseif (\in_array($value, constants::FALSE_BOOLS, true)) {
					$value = false;
				}
			}
			elseif (\is_array($value)) {
				$value = !! \count($value);
			}

			if (! \is_bool($value)) {
				try {
					$value = (bool) $value;
				} catch (\Throwable $e) {
					$value = false;
				}
			}
		}
	}

	/**
	 * To Bool
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Flatten.
	 * @return void Nothing.
	 */
	public static function to_bool(&$value=null, bool $flatten=false) {
		static::bool($value, $flatten);
	}

	/**
	 * To Bool
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Flatten.
	 * @return void Nothing.
	 */
	public static function boolean(&$value=null, bool $flatten=false) {
		static::bool($value, $flatten);
	}

	/**
	 * To Float
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return void Nothing.
	 */
	public static function float(&$value=0, bool $flatten=false) {
		// Short circuit.
		if (\is_float($value)) {
			return;
		}

		if (! $flatten && \is_array($value)) {
			foreach ($value as $k=>$v) {
				static::float($value[$k]);
			}
		}
		else {
			static::number($value, true);
			try {
				$value = (float) $value;
			} catch (\Throwable $e) {
				$value = 0.0;
			}
		}
	}

	/**
	 * To Float
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Flatten.
	 * @return void Nothing.
	 */
	public static function double(&$value=null, bool $flatten=false) {
		static::float($value, $flatten);
	}

	/**
	 * To Float
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Flatten.
	 * @return void Nothing.
	 */
	public static function to_float(&$value=null, bool $flatten=false) {
		static::float($value, $flatten);
	}

	/**
	 * To Int
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return void Nothing.
	 */
	public static function int(&$value=0, bool $flatten=false) {
		// Short circuit.
		if (\is_int($value)) {
			return;
		}

		if (! $flatten && \is_array($value)) {
			foreach ($value as $k=>$v) {
				static::int($value[$k]);
			}
		}
		else {
			// Flatten single-entry arrays.
			if (\is_array($value) && (1 === \count($value))) {
				$value = data::array_pop_top($value);
			}

			// Evaluate special cases.
			if (\is_string($value)) {
				$value = \strtolower($value);
				if (\in_array($value, constants::TRUE_BOOLS, true)) {
					$value = 1;
				}
				elseif (\in_array($value, constants::FALSE_BOOLS, true)) {
					$value = 0;
				}
			}

			if (! \is_int($value)) {
				static::number($value, true);
				$value = (int) $value;
			}
		}
	}

	/**
	 * To Integer
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Flatten.
	 * @return void Nothing.
	 */
	public static function to_int(&$value=null, bool $flatten=false) {
		static::int($value, $flatten);
	}

	/**
	 * To Integer
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Flatten.
	 * @return void Nothing.
	 */
	public static function integer(&$value=null, bool $flatten=false) {
		static::int($value, $flatten);
	}

	/**
	 * To Number
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return void Nothing.
	 */
	public static function number(&$value=0, bool $flatten=false) {
		// Short circuit.
		if (\is_float($value)) {
			return;
		}

		if (! $flatten && \is_array($value)) {
			foreach ($value as $k=>$v) {
				static::number($value[$k]);
			}
		}
		else {
			// Flatten single-entry arrays.
			if (\is_array($value) && (1 === \count($value))) {
				$value = data::array_pop_top($value);
			}

			if (\is_string($value)) {
				static::string($value);

				// Replace number chars.
				$from = \array_keys(constants::NUMBER_CHARS);
				$to = \array_values(constants::NUMBER_CHARS);
				$value = \str_replace($from, $to, $value);

				// Convert from cents.
				if (\preg_match('/^\-?[\d,]*\.?\d+¢$/', $value)) {
					$value = \preg_replace('/[^\-\d\.]/', '', $value);
					static::number($value);
					$value /= 100;
				}
				// Convert from percent.
				elseif (\preg_match('/^\-?[\d,]*\.?\d+%$/', $value)) {
					$value = \preg_replace('/[^\-\d\.]/', '', $value);
					static::number($value);
					$value /= 100;
				}
			}

			if (! \is_float($value)) {
				try {
					$value = (float) \filter_var(
						$value,
						\FILTER_SANITIZE_NUMBER_FLOAT,
						\FILTER_FLAG_ALLOW_FRACTION
					);
				} catch (\Throwable $e) {
					$value = 0.0;
				}
			}
		}
	}

	/**
	 * To Number
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Flatten.
	 * @return void Nothing.
	 */
	public static function to_number(&$value=null, bool $flatten=false) {
		static::number($value, $flatten);
	}

	/**
	 * To String
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return void Nothing.
	 */
	public static function string(&$value='', bool $flatten=false) {
		if (! $flatten && \is_array($value)) {
			foreach ($value as $k=>$v) {
				static::string($value[$k]);
			}
		}
		else {
			// Flatten single-entry arrays.
			if (\is_array($value) && (1 === \count($value))) {
				$value = data::array_pop_top($value);
			}

			if (\is_array($value)) {
				$value = '';
				return;
			}

			try {
				$value = (string) $value;
				if (
					$value &&
					(
						! \function_exists('mb_check_encoding') ||
						! \mb_check_encoding($value, 'ASCII')
					)
				) {
					sanitize::utf8($value);
				}
			} catch (\Throwable $e) {
				$value = '';
			}
		}
	}

	/**
	 * To String
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Flatten.
	 * @return void Nothing.
	 */
	public static function to_string(&$value=null, bool $flatten=false) {
		static::string($value, $flatten);
	}

	/**
	 * To X Type
	 *
	 * @param mixed $value Variable.
	 * @param string $type Type.
	 * @param bool $flatten Do not recurse.
	 * @return void Nothing.
	 */
	public static function to_type(&$value, string $type='', bool $flatten=false) {
		switch (\strtolower($type)) {
			case 'string':
				static::string($value, $flatten);
				break;
			case 'int':
			case 'integer':
				static::int($value, $flatten);
				break;
			case 'double':
			case 'float':
			case 'number':
				static::float($value, $flatten);
				break;
			case 'bool':
			case 'boolean':
				static::bool($value, $flatten);
				break;
			case 'array':
				static::array($value);
				break;
		}
	}

	/**
	 * Light String Cast
	 *
	 * This method is deprecated.
	 *
	 * @param mixed $value String.
	 * @param bool $light Actually check.
	 * @return void Nothing.
	 */
	public static function constringent(&$value=null, bool $light=false) {
		// This method is deprecated.
		static::string($value, true);
	}
}
