<?php
/**
 * Type Handling.
 *
 * Functions for typecasting and type detection.
 *
 * @package blobfolio/common
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\common;

class cast {

	/**
	 * To Array
	 *
	 * @param mixed $value Variable.
	 * @return array Array.
	 */
	public static function to_array($value=null) {
		return static::array($value);
	}

	/**
	 * To Array
	 *
	 * @param mixed $value Variable.
	 * @return array Array.
	 */
	public static function array($value=null) {
		ref\cast::array($value);
		return $value;
	}

	/**
	 * Array Type
	 *
	 * "associative": If there are string keys.
	 * "sequential": If the keys are sequential numbers.
	 * "indexed": If the keys are at least numeric.
	 * FALSE: Any other condition.
	 *
	 * @param array $arr Array.
	 * @return string|bool Type. False on failure.
	 */
	public static function array_type(&$arr=null) {
		if (! \is_array($arr) || ! \count($arr)) {
			return false;
		}

		$keys = \array_keys($arr);
		if (\range(0, \count($keys) - 1) === $keys) {
			return 'sequential';
		}
		elseif (\count($keys) === \count(\array_filter($keys, 'is_numeric'))) {
			return 'indexed';
		}
		else {
			return 'associative';
		}
	}

	/**
	 * To Bool
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return bool Bool.
	 */
	public static function to_bool($value=false, bool $flatten=false) {
		return static::bool($value, $flatten);
	}

	/**
	 * To Bool
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return bool Bool.
	 */
	public static function bool($value=false, bool $flatten=false) {
		ref\cast::bool($value, $flatten);
		return $value;
	}

	/**
	 * To Bool
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return bool Bool.
	 */
	public static function boolean($value=false, bool $flatten=false) {
		return static::bool($value, $flatten);
	}

	/**
	 * To Float
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return float Float.
	 */
	public static function to_float($value=0, bool $flatten=false) {
		return static::float($value, $flatten);
	}

	/**
	 * To Float
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return float Float.
	 */
	public static function double($value=0, bool $flatten=false) {
		return static::float($value, $flatten);
	}

	/**
	 * To Float
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return float Float.
	 */
	public static function float($value=0, bool $flatten=false) {
		ref\cast::float($value, $flatten);
		return $value;
	}

	/**
	 * To Int
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return int Int.
	 */
	public static function to_int($value=0, bool $flatten=false) {
		return static::int($value, $flatten);
	}

	/**
	 * To Int
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return int Int.
	 */
	public static function int($value=0, bool $flatten=false) {
		ref\cast::int($value, $flatten);
		return $value;
	}

	/**
	 * To Int
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return int Int.
	 */
	public static function integer($value=0, bool $flatten=false) {
		return static::int($value, $flatten);
	}

	/**
	 * To Number
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return float Number.
	 */
	public static function to_number($value=0, bool $flatten=false) {
		return static::number($value, $flatten);
	}

	/**
	 * To Number
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return float Number.
	 */
	public static function number($value=0, bool $flatten=false) {
		ref\cast::number($value, $flatten);
		return $value;
	}

	/**
	 * To String
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return string String.
	 */
	public static function to_string($value='', bool $flatten=false) {
		return static::string($value, $flatten);
	}

	/**
	 * To String
	 *
	 * @param mixed $value Variable.
	 * @param bool $flatten Do not recurse.
	 * @return string String.
	 */
	public static function string($value='', bool $flatten=false) {
		ref\cast::string($value, $flatten);
		return $value;
	}

	/**
	 * To X Type
	 *
	 * @param mixed $value Variable.
	 * @param string $type Type.
	 * @param bool $flatten Do not recurse.
	 * @return mixed Cast value.
	 */
	public static function to_type($value, string $type='', bool $flatten=false) {
		ref\cast::to_type($value, $type, $flatten);
		return $value;
	}
}


