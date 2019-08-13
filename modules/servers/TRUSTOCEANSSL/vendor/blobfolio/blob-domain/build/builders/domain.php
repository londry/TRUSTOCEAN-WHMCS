<?php
/**
 * Compile Suffix Data
 *
 * @package blobfolio/domain
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

/**
 * Data Source: Public Suffix List
 *
 * @see {https://publicsuffix.org/list/public_suffix_list.dat}
 *
 * @copyright 2017 Mozilla Foundation.
 * @license https://www.mozilla.org/en-US/MPL/ MPL
 */

namespace blobfolio\dev;

use blobfolio\bob\format;
use blobfolio\bob\io;
use blobfolio\bob\log;
use blobfolio\common\mb as v_mb;
use blobfolio\common\ref\sanitize as r_sanitize;

class domain extends \blobfolio\bob\base\mike {
	// Project Name.
	const NAME = 'blob-domain';
	const DESCRIPTION = 'blob-domain is a simple PHP library for parsing and validating domain names. It supports the full Public Suffix ruleset, translates Unicode to ASCII or vice versa (if the PHP extension INTL is present), and can break down a host into its constituent parts: subdomain, domain, and suffix.';
	const CONFIRMATION = '';
	const SLUG = '';

	// Runtime requirements.
	const REQUIRED_FUNCTIONS = array('idn_to_ascii');

	const REQUIRED_DOWNLOADS = array(
		'https://publicsuffix.org/list/public_suffix_list.dat',
	);

	// Automatic setup.
	const CLEAN_ON_SUCCESS = false;			// Delete tmp/bob when done.
	const SHITLIST = null;					// Specific shitlist.

	// Functions to run to complete the build, in order, grouped by
	// heading.
	const ACTIONS = array(
		'Updating Data'=>array(
			'build',
		),
	);



	/**
	 * Build
	 *
	 * This is actually pretty simple; we don't need a million different
	 * callbacks to get it built. Haha.
	 *
	 * @return void Nothing.
	 */
	public static function build() {
		if (! \defined('BOB_ROOT_DIR')) {
			log::error('Missing root dir.');
		}

		log::print('Loading data…');

		$data = io::get_url(static::REQUIRED_DOWNLOADS[0]);

		// We want to cut out the ICANN bits. If these strings don't
		// exist, there's something wrong.
		if (
			(false === ($start = v_mb::strpos($data, '// ===BEGIN ICANN DOMAINS==='))) ||
			(false === ($end = v_mb::strpos($data, '// ===END ICANN DOMAINS===')))
		) {
			log::error('Unexpected data was returned.');
		}

		// Chop and sanitize.
		$data = v_mb::substr($data, $start, ($end - $start));
		r_sanitize::whitespace($data, 1);
		$data = \explode("\n", $data);
		$data = \array_filter($data, 'strlen');

		log::print('Parsing data…');

		$suffixes = array();
		foreach ($data as $line) {
			// Skip comments.
			if (0 === \strpos($line, '//')) {
				continue;
			}

			// Tease out the parts.
			$parts = \preg_replace('/^!/', '!.', $line);
			$parts = \explode('.', $parts);

			// Recurse.
			static::build_save($suffixes, $parts);
		}

		// Note how many we found.
		log::total(\count($suffixes));

		log::print('Exporting data…');

		$template_file = \BOB_ROOT_DIR . 'skel/data.template';
		$out_file = \dirname(\BOB_ROOT_DIR) . '/lib/blobfolio/domain/data.php';

		$out = \file_get_contents($template_file);
		$out = \str_replace(
			array(
				'%GENERATED%',
				'%SUFFIXES%',
			),
			array(
				\date('Y-m-d H:i:s'),
				format::array_to_php($suffixes, 2),
			),
			$out
		);
		\file_put_contents($out_file, $out);

		// Save a JSON copy to the build root.
		$out = \json_encode($suffixes);
		$out_file = \dirname(\BOB_ROOT_DIR) . '/bin/blob-domains.json';
		\file_put_contents($out_file, $out);
	}



	// -----------------------------------------------------------------
	// Helpers
	// -----------------------------------------------------------------

	/**
	 * Build Storage
	 *
	 * @param array $old Old.
	 * @param array $new New.
	 * @return void Nothing.
	 */
	protected static function build_save(&$old, $new) {
		// Ignore bad data.
		if (! \is_array($old) || ! \is_array($new)) {
			return;
		}

		// Pop off the end.
		$part = \array_pop($new);
		$part = \idn_to_ascii($part);

		// If it doesn't exist, it's a new base.
		if (! isset($old[$part])) {
			$old[$part] = array();
		}

		// If there are other parts, do it all over again.
		if (\count($new) > 0) {
			static::build_save($old[$part], $new);
		}
	}

	// ----------------------------------------------------------------- end helpers
}
