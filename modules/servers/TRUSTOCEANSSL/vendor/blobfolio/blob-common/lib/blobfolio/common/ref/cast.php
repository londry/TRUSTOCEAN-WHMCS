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

use \blobfolio\common\cast as v_cast;
use \blobfolio\common\data;
use \blobfolio\common\constants;
use \blobfolio\common\mb as v_mb;

class cast {

	/**
	 * To Array
	 *
	 * @param mixed $value Variable.
	 * @return bool True.
	 */
	public static function array(&$value=null) {
		// Short circuit.
		if (is_array($value)) {
			return true;
		}

		try {
			$value = (array) $value;
		} catch (\Throwable $e) {
			$value = array();
		}

		return true;
	}

	/**
	 * To Array
	 *
	 * @param mixed $value Variable.
	 * @return array Array.
	 */
	public static function to_array(&$value=null) {
		return static::array($value);
	}

	/**
	 * To Bool
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return bool True.
	 */
	public static function bool(&$value=false, bool $flatten=false) {
		// Short circuit.
		if (is_bool($value)) {
			return true;
		}

		if (!$flatten && is_array($value)) {
			foreach ($value as $k=>$v) {
				static::bool($value[$k]);
			}
		}
		else {
			// Evaluate special cases.
			if (is_string($value)) {
				$value = strtolower($value);
				if (in_array($value, constants::TRUE_BOOLS, true)) {
					$value = true;
				}
				elseif (in_array($value, constants::FALSE_BOOLS, true)) {
					$value = false;
				}
			}
			elseif (is_array($value)) {
				$value = !!count($value);
			}

			if (!is_bool($value)) {
				try {
					$value = (bool) $value;
				} catch (\Throwable $e) {
					$value = false;
				}
			}
		}

		return true;
	}

	/**
	 * To Bool
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Flatten.
	 * @return bool True/false.
	 */
	public static function to_bool(&$value=null, bool $flatten=false) {
		return static::bool($value, $flatten);
	}

	/**
	 * To Bool
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Flatten.
	 * @return bool True/false.
	 */
	public static function boolean(&$value=null, bool $flatten=false) {
		return static::bool($value, $flatten);
	}

	/**
	 * To Float
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return bool True.
	 */
	public static function float(&$value=0, bool $flatten=false) {
		// Short circuit.
		if (is_float($value)) {
			return true;
		}

		if (!$flatten && is_array($value)) {
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

		return true;
	}

	/**
	 * To Float
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Flatten.
	 * @return float Value.
	 */
	public static function double(&$value=null, bool $flatten=false) {
		return static::float($value, $flatten);
	}

	/**
	 * To Float
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Flatten.
	 * @return float Value.
	 */
	public static function to_float(&$value=null, bool $flatten=false) {
		return static::float($value, $flatten);
	}

	/**
	 * To Int
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return bool True.
	 */
	public static function int(&$value=0, bool $flatten=false) {
		// Short circuit.
		if (is_int($value)) {
			return true;
		}

		if (!$flatten && is_array($value)) {
			foreach ($value as $k=>$v) {
				static::int($value[$k]);
			}
		}
		else {
			// Flatten single-entry arrays.
			if (is_array($value) && (1 === count($value))) {
				$value = data::array_pop_top($value);
			}

			// Evaluate special cases.
			if (is_string($value)) {
				$value = strtolower($value);
				if (in_array($value, constants::TRUE_BOOLS, true)) {
					$value = 1;
				}
				elseif (in_array($value, constants::FALSE_BOOLS, true)) {
					$value = 0;
				}
			}

			if (!is_int($value)) {
				static::number($value, true);
				$value = (int) $value;
			}
		}

		return true;
	}

	/**
	 * To Integer
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Flatten.
	 * @return int Value.
	 */
	public static function to_int(&$value=null, bool $flatten=false) {
		return static::int($value, $flatten);
	}

	/**
	 * To Integer
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Flatten.
	 * @return int Value.
	 */
	public static function integer(&$value=null, bool $flatten=false) {
		return static::int($value, $flatten);
	}

	/**
	 * To Number
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return bool True.
	 */
	public static function number(&$value=0, bool $flatten=false) {
		// Short circuit.
		if (is_float($value)) {
			return true;
		}

		if (!$flatten && is_array($value)) {
			foreach ($value as $k=>$v) {
				static::number($value[$k]);
			}
		}
		else {
			// Flatten single-entry arrays.
			if (is_array($value) && (1 === count($value))) {
				$value = data::array_pop_top($value);
			}

			if (is_string($value)) {
				static::string($value);

				// Replace number chars.
				$from = array_keys(constants::NUMBER_CHARS);
				$to = array_values(constants::NUMBER_CHARS);
				$value = str_replace($from, $to, $value);

				// Convert from cents.
				if (preg_match('/^\-?[\d,]*\.?\d+¢$/', $value)) {
					$value = v_cast::number(preg_replace('/[^\-\d\.]/', '', $value)) / 100;
				}
				// Convert from percent.
				elseif (preg_match('/^\-?[\d,]*\.?\d+%$/', $value)) {
					$value = v_cast::number(preg_replace('/[^\-\d\.]/', '', $value)) / 100;
				}
			}

			if (!is_float($value)) {
				try {
					$value = (float) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
				} catch (\Throwable $e) {
					$value = 0.0;
				}
			}
		}

		return true;
	}

	/**
	 * To Number
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Flatten.
	 * @return float Value.
	 */
	public static function to_number(&$value=null, bool $flatten=false) {
		return static::number($value, $flatten);
	}

	/**
	 * To String
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return bool True.
	 */
	public static function string(&$value='', bool $flatten=false) {
		// Short circuit.
		if (constants::$str_lock && is_string($value)) {
			return true;
		}

		if (!$flatten && is_array($value)) {
			foreach ($value as $k=>$v) {
				static::string($value[$k]);
			}
		}
		else {
			// Flatten single-entry arrays.
			if (is_array($value) && (1 === count($value))) {
				$value = data::array_pop_top($value);
			}

			try {
				$value = (string) $value;
				if (
					$value &&
					(
						!function_exists('mb_check_encoding') ||
						!mb_check_encoding($value, 'ASCII')
					)
				) {
					sanitize::utf8($value);
				}
			} catch (\Throwable $e) {
				$value = '';
			}
		}

		return true;
	}

	/**
	 * To String
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Flatten.
	 * @return string String.
	 */
	public static function to_string(&$value=null, bool $flatten=false) {
		return static::string($value, $flatten);
	}

	/**
	 * To X Type
	 *
	 * @param mixed $value Variable.
	 * @param string $type Type.
	 * @param bool $flatten Do not recurse.
	 * @return bool True.
	 */
	public static function to_type(&$value, string $type='', bool $flatten=false) {
		if (!$type) {
			return true;
		}

		switch (strtolower($type)) {
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

		return true;
	}

	/**
	 * Light String Cast
	 *
	 * We kinda fucked ourselves with heavy string typecasting
	 * dependencies — namely fixing UTF-8 — so we want to offer up a
	 * way to conditionally bypass the extra bits.
	 *
	 * Functions requiring strings have been altered to include a
	 * $constringent argument that will allow light checks.
	 *
	 * @param mixed $value String.
	 * @param bool $light Actually check.
	 * @return bool True/false.
	 */
	public static function constringent(&$value=null, bool $light=false) {
		// Don't need to do anything!
		if ($light && is_string($value)) {
			return true;
		}

		// Flatten single-entry arrays.
		if (is_array($value) && (1 === count($value))) {
			$value = data::array_pop_top($value);
			if ($light && is_string($value)) {
				return true;
			}
		}

		// Cast it.
		try {
			$value = (string) $value;

			// Do heavy stuff if needed.
			if (
				$value &&
				!$light &&
				(
					!function_exists('mb_check_encoding') ||
					!mb_check_encoding($value, 'ASCII')
				)
			) {
				sanitize::utf8($value);
			}
		} catch (\Throwable $e) {
			$value = '';
		}

		return true;
	}
}
