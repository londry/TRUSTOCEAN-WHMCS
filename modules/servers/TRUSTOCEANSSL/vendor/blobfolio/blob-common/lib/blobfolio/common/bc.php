<?php
/**
 * Helpers for bcmath
 *
 * Fill in some missing bcmath-type operations.
 *
 * @see {https://github.com/lifo101/ip/blob/master/src/Lifo/IP/BC.php}
 * @see {http://cct.me.ntut.edu.tw/ccteducation/chchting/aiahtm/computer/phphelp/ref.bc.php.htm}
 *
 * @package blobfolio/common
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\common;

class bc {

	/**
	 * Bitwise Operations
	 *
	 * Note: this probably should not be referenced directly in your
	 * projects.
	 *
	 * While similar to native bitwise operations, it is not fully
	 * consistent behaviorally. Within blob-common, this helps with IPv6
	 * calculations; results for other purposes will vary.
	 *
	 * @param string $method Bitwise operator.
	 * @param string $left Left argument.
	 * @param string $right Right argument.
	 * @param int $bits Bits to use.
	 * @return string Value or 0.
	 */
	public static function bitwise(string $method, $left, $right='0', $bits=null) {
		// Sanitize the operation.
		$method = \strtoupper($method);
		if (isset(constants::BITWISE_OPERATORS[$method])) {
			$method = constants::BITWISE_OPERATORS[$method];
		}

		if (! $method || ! \in_array($method, constants::BITWISE_OPERATORS, true)) {
			return '0';
		}

		// Calculate bits.
		if (null === $bits) {
			$bits = \max(static::bit_size($left), static::bit_size($right));
		}

		ref\cast::int($bits, true);
		ref\sanitize::to_range($bits, 0);

		// LEFT and RIGHT operations can be done here and now.
		if ('LEFT' === $method) {
			return \bcmul($left, \bcpow('2', $bits));
		}
		elseif ('RIGHT' === $method) {
			return \bcdiv($left, \bcpow('2', $bits));
		}

		// For everything else, there's binary.
		$left  = static::decbin($left);
		// NOT doesn't need a right.
		if ('NOT' === $method) {
			$right = '0';
		}
		// The rest do.
		else {
			$right = static::decbin($right);
		}

		// Pad all arguments to the longest bit_size.
		$length = \max(\strlen($left), \strlen($right), $bits);
		$left = \sprintf("%0{$length}s", $left);
		$right = \sprintf("%0{$length}s", $right);

		// Build the output bit-by-bit.
		$out = '';

		// Bitwise and.
		if ('AND' === $method) {
			for ($x = 0; $x < $length; ++$x) {
				$out .= (($left[$x] + 0) & ($right[$x] + 0)) ? '1' : '0';
			}
		}
		// Bitwise or.
		elseif ('OR' === $method) {
			for ($x = 0; $x < $length; ++$x) {
				$out .= (($left[$x] + 0) | ($right[$x] + 0)) ? '1' : '0';
			}
		}
		// Bitwise exclusive or.
		elseif ('XOR' === $method) {
			for ($x = 0; $x < $length; ++$x) {
				$out .= (($left[$x] + 0) ^ ($right[$x] + 0)) ? '1' : '0';
			}
		}
		// Throw it down, flip it, and reverse it.
		elseif ('NOT' === $method) {
			$out = \strtr($left, array('0'=>'1', '1'=>'0'));
		}

		// Back to a decimal.
		if ($out) {
			return static::bindec($out);
		}

		// Failure.
		return '0';
	}

	/**
	 * Calculate Bits
	 *
	 * Find the number of bits needed to store
	 * a value in blocks of 4.
	 *
	 * @param string $num Number.
	 * @return int Bits.
	 */
	protected static function bit_size($num) {
		ref\cast::string($num, true);

		$bits = 0;
		while ($num > 0) {
			$num = \bcdiv($num, '2', 0);
			$bits++;
		}

		return \ceil($bits / 4) * 4;
	}

	/**
	 * Binary to decimal
	 *
	 * @param string $bin Binary.
	 * @return string Decimal.
	 */
	public static function bindec($bin) {
		ref\cast::string($bin, true);

		$dec = '0';
		$length = \strlen($bin);
		for ($x = 0; $x < $length; ++$x) {
			$dec = \bcmul($dec, '2', 0);
			$dec = \bcadd($dec, $bin[$x], 0);
		}

		return $dec;
	}

	/**
	 * Binary to hex.
	 *
	 * @param string $bin Binary.
	 * @return string Hex.
	 */
	public static function binhex($bin) {
		return static::dechex(static::bindec($bin));
	}

	/**
	 * Decimal to binary
	 *
	 * @param string $dec Decimal.
	 * @param int $length Pad length.
	 * @return string Binary.
	 */
	public static function decbin($dec, int $length=0) {
		ref\cast::string($dec, true);
		ref\sanitize::to_range($length, 0);

		$bin = '';
		while ($dec) {
			$m = \bcmod($dec, 2);
			$dec = \bcdiv($dec, 2, 0);
			$bin = \abs($m) . $bin;
		}

		if ($length) {
			return \sprintf("%0{$length}s", $bin);
		}

		return $bin ? $bin : '0';
	}

	/**
	 * Decimal to hex
	 *
	 * @param string $dec Decimal.
	 * @return string Hex.
	 */
	public static function dechex($dec) {
		$last = \bcmod($dec, 16);
		$remain = \bcdiv(\bcsub($dec, $last, 0), 16, 0);

		// The easy way.
		if ('0' === $remain) {
			return \dechex($last);
		}

		return static::dechex($remain) . \dechex($last);
	}

	/**
	 * HEX to decimal
	 *
	 * @param string $hex Hex.
	 * @return string Decimal.
	 */
	public static function hexdec($hex) {
		ref\cast::string($hex, true);

		// Do it the easy way.
		if (1 === \strlen($hex)) {
			return \hexdec($hex);
		}

		$remain = \substr($hex, 0, -1);
		$last = \substr($hex, -1);
		return \bcadd(\bcmul(16, static::hexdec($remain), 0), \hexdec($last), 0);
	}

}
