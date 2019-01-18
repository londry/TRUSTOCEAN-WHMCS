<?php
/**
 * Domain
 *
 * This is the main domain class.
 *
 * @package blobfolio/domain
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\domain;

use \blobfolio\common\constants;
use \blobfolio\common\data as c_data;
use \blobfolio\common\mb as v_mb;
use \blobfolio\common\ref\cast as r_cast;
use \blobfolio\common\ref\file as r_file;
use \blobfolio\common\ref\mb as r_mb;
use \blobfolio\common\ref\sanitize as r_sanitize;

class domain {

	const HOST_PARTS = array(
		'host'=>null,
		'subdomain'=>null,
		'domain'=>null,
		'suffix'=>null,
	);

	protected $host;
	protected $subdomain;
	protected $domain;
	protected $suffix;

	protected $dns;



	// ---------------------------------------------------------------------
	// Init
	// ---------------------------------------------------------------------

	/**
	 * Construct
	 *
	 * @param string $host Host.
	 * @param bool $www Strip leading www.
	 * @return bool True/false.
	 */
	public function __construct($host='', bool $www=false) {
		// Parse the parts.
		if (false === ($parsed = static::parse_host_parts($host))) {
			return false;
		}

		$this->host = $parsed['host'];
		$this->subdomain = $parsed['subdomain'];
		$this->domain = $parsed['domain'];
		$this->suffix = $parsed['suffix'];

		if ($www) {
			$this->strip_www();
		}

		return true;
	}

	/**
	 * Parse Host
	 *
	 * Try to tease the hostname out of any arbitrary
	 * string, which might be the hostname, a URL, or
	 * something else.
	 *
	 * @param string $host Host.
	 * @return string|bool Host or false.
	 */
	public static function parse_host($host) {
		// Try to parse it the easy way.
		$tmp = v_mb::parse_url($host, PHP_URL_HOST);
		if ($tmp) {
			$host = $tmp;
		}
		// Or the hard way?
		else {
			r_cast::string($host, true);

			r_mb::trim($host, true);

			// Cut off the path, if any.
			if (false !== ($start = v_mb::strpos($host, '/'))) {
				$host = v_mb::substr($host, 0, $start, true);
			}

			// Cut off the query, if any.
			if (false !== ($start = v_mb::strpos($host, '?'))) {
				$host = v_mb::substr($host, 0, $start, true);
			}

			// Cut off credentials, if any.
			if (false !== ($start = v_mb::strpos($host, '@'))) {
				$host = v_mb::substr($host, $start + 1, null, true);
			}

			// Is this an IPv6 address?
			if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
				r_sanitize::ip($host, true);
			}
			elseif (
				(0 === strpos($host, '[')) &&
				false !== ($end = v_mb::strpos($host, ']'))
			) {
				$host = v_mb::substr($host, 1, $end - 1, true);
				r_sanitize::ip($host, true);
			}
			// Cut off port, if any.
			elseif (false !== ($start = v_mb::strpos($host, ':'))) {
				$host = v_mb::substr($host, 0, $start, true);
			}

			// If it is empty or invalid, there is nothing we can do.
			if (!strlen($host)) {
				return false;
			}

			// Convert to ASCII if possible.
			if (function_exists('idn_to_ascii')) {
				$host = explode('.', $host);
				r_file::idn_to_ascii($host);
				$host = implode('.', $host);
			}

			// Lowercase it.
			r_mb::strtolower($host, false, true);

			// Get rid of trailing periods.
			$host = ltrim($host, '.');
			$host = rtrim($host, '.');
		}

		// Liberate IPv6 from its walls.
		if (0 === strpos($host, '[')) {
			$host = str_replace(array('[', ']'), '', $host);
			r_sanitize::ip($host, true);
		}

		// Is this an IP address? If so, we're done!
		if (filter_var($host, FILTER_VALIDATE_IP)) {
			return $host;
		}

		// Look for illegal characters. At this point we should
		// only have nice and safe ASCII.
		if (preg_match('/[^a-z\d\-\.]/u', $host)) {
			return false;
		}
		$host = explode('.', $host);
		foreach ($host as $v) {
			// Gotta have length, and can't start or end with a dash.
			if (
				!strlen($v) ||
				(0 === strpos($v, '-')) ||
				('-' === substr($v, -1))
			) {
				return false;
			}
		}

		return implode('.', $host);
	}

	/**
	 * Parse Host Parts
	 *
	 * Break a host down into subdomain, domain, and
	 * suffix parts.
	 *
	 * @param string $host Host.
	 * @return array|bool Parts or false.
	 */
	public static function parse_host_parts($host) {
		// Tease out the hostname.
		if (false === ($host = static::parse_host($host))) {
			return false;
		}

		$out = static::HOST_PARTS;

		// If this is an IP, we don't have to do anything else.
		if (filter_var($host, FILTER_VALIDATE_IP)) {
			$out['host'] = $host;
			$out['domain'] = $host;
			return $out;
		}

		// Now the hard part. See if any parts of the host
		// correspond to a registered suffix.
		$suffixes = data::SUFFIXES;
		$suffix = array();
		$parts = explode('.', $host);
		$parts = array_reverse($parts);

		foreach ($parts as $k=>$part) {
			// Override rule.
			if (isset($suffixes[$part], $suffixes[$part]['!'])) {
				break;
			}

			// A match.
			if (isset($suffixes[$part])) {
				array_unshift($suffix, $part);
				$suffixes = $suffixes[$part];
				unset($parts[$k]);
				continue;
			}

			// A wildcard.
			if (isset($suffixes['*'])) {
				array_unshift($suffix, $part);
				$suffixes = $suffixes['*'];
				unset($parts[$k]);
				continue;
			}

			// We're done.
			break;
		}

		// The suffix can't be all there is.
		if (!count($parts)) {
			return false;
		}

		// The domain.
		$parts = array_reverse($parts);
		$out['domain'] = array_pop($parts);

		// The subdomain.
		if (count($parts)) {
			$out['subdomain'] = implode('.', $parts);
		}

		// The suffix.
		if (count($suffix)) {
			$out['suffix'] = implode('.', $suffix);
		}

		$out['host'] = $host;

		return $out;
	}

	/**
	 * Strip Leading WWW
	 *
	 * The www. subdomain is evil. This removes
	 * it, but only if it is part of the subdomain.
	 *
	 * @return bool True/false.
	 */
	public function strip_www() {
		if (!$this->is_valid() || is_null($this->subdomain)) {
			return false;
		}

		if (
			('www' === $this->subdomain) ||
			(0 === strpos($this->subdomain, 'www.'))
		) {
			$this->subdomain = preg_replace('/^www\.?/u', '', $this->subdomain);
			if (!strlen($this->subdomain)) {
				$this->subdomain = null;
			}

			$this->host = preg_replace('/^www\./u', '', $this->host);
			return true;
		}

		return false;
	}

	// --------------------------------------------------------------------- end init



	// ---------------------------------------------------------------------
	// Results
	// ---------------------------------------------------------------------

	/**
	 * Is Valid
	 *
	 * @param bool $dns Has DNS.
	 * @return bool True/false.
	 */
	public function is_valid(bool $dns=false) {
		return !is_null($this->host) && (!$dns || $this->has_dns());
	}

	/**
	 * Is Fully Qualified Domain Name
	 *
	 * @return bool True/false.
	 */
	public function is_fqdn() {
		return (
			$this->is_valid() &&
			(is_string($this->suffix) || $this->is_ip(false))
		);
	}

	/**
	 * Is IP
	 *
	 * @param bool $restricted Allow restricted.
	 * @return bool True/false.
	 */
	public function is_ip(bool $restricted=true) {
		if (!$this->is_valid()) {
			return false;
		}

		if ($restricted) {
			return !!filter_var($this->host, FILTER_VALIDATE_IP);
		}

		return !!filter_var(
			$this->host,
			FILTER_VALIDATE_IP,
			FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
		);
	}

	/**
	 * Has DNS
	 *
	 * @return bool True/false.
	 */
	public function has_dns() {
		if (is_null($this->dns)) {
			if (!$this->is_fqdn()) {
				$this->dns = false;
			}
			elseif ($this->is_ip()) {
				$this->dns = $this->is_ip(false);
			}
			else {
				$this->dns = !!filter_var(
					gethostbyname("{$this->host}."),
					FILTER_VALIDATE_IP,
					FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
				);
			}
		}

		return $this->dns;
	}

	/**
	 * Is ASCII
	 *
	 * @return bool True/false.
	 */
	public function is_ascii() {
		if (!$this->is_valid()) {
			return false;
		}

		return !$this->is_unicode();
	}

	/**
	 * Is Unicode
	 *
	 * @return bool True/false.
	 */
	public function is_unicode() {
		if (!$this->is_valid() || $this->is_ip() || !function_exists('idn_to_utf8')) {
			return false;
		}

		return ($this->to_unicode('host') !== $this->host);
	}

	// --------------------------------------------------------------------- end results



	// ---------------------------------------------------------------------
	// Return Data
	// ---------------------------------------------------------------------

	/**
	 * Cast to String
	 *
	 * @return string Phone number.
	 */
	public function __toString() {
		return $this->is_valid() ? $this->host : '';
	}

	/**
	 * Magic Getter
	 *
	 * @param string $method Method name.
	 * @param mixed $args Arguments.
	 * @return mixed Variable.
	 * @throws \Exception Invalid method.
	 */
	public function __call($method, $args) {
		preg_match_all('/^get_(.+)$/', $method, $matches);
		if (
			count($matches[0]) &&
			('dns' !== $matches[1][0]) &&
			property_exists($this, $matches[1][0])
		) {
			$variable = $matches[1][0];

			if (is_array($args) && count($args)) {
				$args = c_data::array_pop_top($args);
				r_cast::bool($args);
				if ($args) {
					return $this->to_unicode($variable);
				}
			}

			return $this->{$variable};
		}

		throw new \Exception(
			sprintf(
				'The required method "%s" does not exist for %s',
				$method,
				get_called_class()
			)
		);
	}

	/**
	 * To Unicode
	 *
	 * @param string $key Key.
	 * @return string|null Value.
	 */
	protected function to_unicode($key) {
		$value = $this->{$key};
		if (function_exists('idn_to_utf8') && is_string($value)) {
			$value = explode('.', $value);
			r_file::idn_to_utf8($value);
			return implode('.', $value);
		}

		return $value;
	}

	/**
	 * Get Data
	 *
	 * @param bool $unicode Unicode.
	 * @return array|bool Host data or false.
	 */
	public function get_data(bool $unicode=false) {
		if (!$this->is_valid()) {
			return false;
		}

		return array(
			'host'=>$this->get_host($unicode),
			'subdomain'=>$this->get_subdomain($unicode),
			'domain'=>$this->get_domain($unicode),
			'suffix'=>$this->get_suffix($unicode),
		);
	}

	// --------------------------------------------------------------------- end get
}
