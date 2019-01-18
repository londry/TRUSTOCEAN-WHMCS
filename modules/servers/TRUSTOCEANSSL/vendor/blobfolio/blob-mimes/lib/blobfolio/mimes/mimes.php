<?php
/**
 * MIME, Extension, File-handling
 *
 * This class contains functions for locating and
 * parsing MIME type and file extension data.
 *
 * @package blobfolio/mimes
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\mimes;

use \blobfolio\common\constants;
use \blobfolio\common\data as c_data;
use \blobfolio\common\ref\cast as r_cast;
use \blobfolio\common\ref\file as r_file;
use \blobfolio\common\ref\sanitize as r_sanitize;
use \blobfolio\common\sanitize as v_sanitize;

class mimes {
	const MIME_DEFAULT = 'application/octet-stream';
	const MIME_EMPTY = 'inode/x-empty';



	// -----------------------------------------------------------------
	// Public Data Access
	// -----------------------------------------------------------------

	/**
	 * Get All MIMEs
	 *
	 * Return the entire parsed MIME database.
	 *
	 * @return array MIME data.
	 */
	public static function get_mimes() {
		return data::BY_MIME;
	}

	/**
	 * Get One MIME
	 *
	 * Return information about a single MIME type.
	 *
	 * @param string $mime MIME type.
	 * @return array MIME data.
	 */
	public static function get_mime(string $mime='') {
		r_sanitize::mime($mime);

		// Try the real MIME first.
		if (isset(data::BY_MIME[$mime])) {
			return data::BY_MIME[$mime];
		}

		// Try aliases.
		$loose = array_diff(static::get_loose_mimes($mime), array($mime));
		foreach ($loose as $v) {
			if (isset(data::BY_MIME[$v])) {
				return data::BY_MIME[$v];
			}
		}

		return false;
	}

	/**
	 * Get All Extensions
	 *
	 * Return the entire file extension database.
	 *
	 * @return array Extension data.
	 */
	public static function get_extensions() {
		return data::BY_EXT;
	}

	/**
	 * Get One Extension
	 *
	 * Return information about a single file extension.
	 *
	 * @param string $ext File extension.
	 * @return array Extension data.
	 */
	public static function get_extension(string $ext='') {
		r_sanitize::file_extension($ext);
		return isset(data::BY_EXT[$ext]) ? data::BY_EXT[$ext] : false;
	}

	/**
	 * Verify a MIME/ext pairing
	 *
	 * @param string $ext File extension.
	 * @param string $mime MIME type.
	 * @param bool $soft Soft pass not-found.
	 * @return bool True.
	 */
	public static function check_ext_and_mime(string $ext='', string $mime='', bool $soft=true) {
		// Check the extension.
		r_sanitize::file_extension($ext);
		if (!$ext) {
			return false;
		}

		// Check the MIME.
		r_sanitize::mime($mime);
		if (
			!$mime ||
			(static::MIME_EMPTY === $mime) ||
			($soft && (static::MIME_DEFAULT === $mime))
		) {
			return true;
		}

		// Soft pass on extension fail.
		if (false === ($ext = static::get_extension($ext))) {
			return $soft;
		}

		// Loose mime check.
		$real = $ext['mime'];
		$found = array_intersect($real, static::get_loose_mimes($mime));

		// If we found something, hurray!
		return count($found) > 0;
	}

	/**
	 * Get Loose MIMEs
	 *
	 * Build a list of test MIMEs with x- and vnd. prefixes. These do
	 * not necessarily exist; this method is called prior to such
	 * checks.
	 *
	 * @param string $mime MIME.
	 * @return array MIMEs.
	 */
	protected static function get_loose_mimes(string $mime) {
		r_sanitize::mime($mime);

		$out = array();

		if (!$mime) {
			return $out;
		}

		$out[] = $mime;
		if ((static::MIME_EMPTY === $mime) || (static::MIME_DEFAULT === $mime)) {
			return $out;
		}

		// Weird Microsoft MIME.
		if (0 === strpos($mime, 'application/cdfv2')) {
			$out[] = 'application/vnd.ms-office';
		}

		// Split it up.
		list($type, $subtype) = explode('/', $mime);
		if ($type && $subtype) {
			$subtype = preg_replace('/^(x\-|vnd.)/', '', $subtype);
			$out[] = "$type/x-$subtype";
			$out[] = "$type/vnd.$subtype";
			$out[] = "$type/$subtype";
		}

		// Sort our results, preferring non-prefixed types.
		$out = array_unique($out);
		usort($out, function($a, $b) {
			$a_key = (!preg_match('#/(x\-|vnd\.)#', $a) ? '0_' : '1_') . $a;
			$b_key = (!preg_match('#/(x\-|vnd\.)#', $b) ? '0_' : '1_') . $b;

			return $a_key < $b_key ? -1 : 1;
		});

		return $out;
	}

	/**
	 * Get File Info
	 *
	 * This function is a sexier version of finfo_open().
	 *
	 * @param string $path File path or name.
	 * @param string $nice Nice file name (for e.g. tmp uploads).
	 * @return array File data.
	 */
	public static function finfo($path='', $nice=null) {
		r_cast::string($path, true);
		if (!is_null($nice)) {
			r_cast::string($nice, true);
		}

		$out = array(
			'dirname'=>'',
			'basename'=>'',
			'extension'=>'',
			'filename'=>'',
			'path'=>'',
			'mime'=>static::MIME_DEFAULT,
			'suggested_filename'=>array(),
		);

		// Path might just be an extension.
		if (
			(false === strpos($path, '.')) &&
			(false === strpos($path, '/')) &&
			(false === strpos($path, '\\'))
		) {
			$out['extension'] = v_sanitize::file_extension($path, true);
			if (false !== ($ext = static::get_extension($path))) {
				$out['mime'] = $ext['mime'][0];
			}

			return $out;
		}

		// Path is something path-like.
		r_file::path($path, false, true);
		$out['path'] = $path;
		$out = c_data::parse_args(pathinfo($path), $out);

		if (!is_null($nice)) {
			$pathinfo = pathinfo($nice);
			$out['filename'] = isset($pathinfo['filename']) ? $pathinfo['filename'] : '';
			$out['extension'] = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
		}

		r_sanitize::file_extension($out['extension'], true);

		// Pull the mimes from the extension.
		if (false !== ($ext = static::get_extension($out['extension']))) {
			$out['mime'] = $ext['mime'][0];
		}

		// Try to read the magic mime, if possible.
		try {
			// Find the real path, if possible.
			if (false !== ($path = @realpath($path))) {
				$out['path'] = $path;
				$out['dirname'] = dirname($path);
			}

			// Lookup magic mime, if possible.
			if (
				(false !== $path) &&
				function_exists('finfo_file') &&
				defined('FILEINFO_MIME_TYPE') &&
				@is_file($path) &&
				@filesize($path) > 0
			) {
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$magic_mime = v_sanitize::mime(finfo_file($finfo, $path));
				finfo_close($finfo);

				// SVGs can be misidentified by fileinfo if they are
				// missing the XML tag and/or DOCTYPE declarations. Most
				// other applications don't have that problem, so let's
				// override fileinfo if the file starts with an opening
				// SVG tag.
				if (
					('svg' === $out['extension']) &&
					('image/svg+xml' !== $magic_mime)
				) {
					$tmp = @file_get_contents($path);
					if (
						is_string($tmp) &&
						(false !== strpos(strtolower($tmp), '<svg'))
					) {
						$magic_mime = 'image/svg+xml';
					}
				}

				// Okay, now we can look at the magic in closer detail.
				if (
					$magic_mime &&
					(static::MIME_DEFAULT !== $magic_mime) &&
					(
						(static::MIME_DEFAULT === $out['mime']) ||
						(0 !== strpos($magic_mime, 'text/'))
					) &&
					!static::check_ext_and_mime($out['extension'], $magic_mime)
				) {
					// If we have an alternative magic mime and it is
					// legit, it should override what we derived from
					// the name.
					if (false !== ($mime = static::get_mime($magic_mime))) {
						$out['mime'] = $magic_mime;
						$out['extension'] = $mime['ext'][0];
						foreach ($mime['ext'] as $ext) {
							$out['suggested_filename'][] = "{$out['filename']}.$ext";
						}
					}
				}
			}
		} catch (\Throwable $e) {
			return $out;
		}

		return $out;
	}

	// ----------------------------------------------------------------- end public data access
}



