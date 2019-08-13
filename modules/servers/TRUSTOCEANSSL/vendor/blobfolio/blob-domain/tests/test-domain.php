<?php
/**
 * Domain tests.
 *
 * PHPUnit tests for domain.
 *
 * @package blobfolio/domain
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

use blobfolio\common\constants;
use blobfolio\domain\domain;

/**
 * Test Suite
 */
class domain_tests extends \PHPUnit\Framework\TestCase {
	// -----------------------------------------------------------------
	// Tests
	// -----------------------------------------------------------------

	/**
	 * ::parse_host()
	 *
	 * @dataProvider data_parse_host
	 *
	 * @param string $host Host.
	 * @param mixed $expected_intl Expected.
	 * @param mixed $expected Expected.
	 * @return void Nothing.
	 */
	function test_parse_host(string $host, $expected_intl, $expected) {
		$result = domain::parse_host($host);

		if (\function_exists('idn_to_ascii')) {
			$this->assertSame($expected_intl, $result);
		}
		else {
			$this->assertSame($expected, $result);
		}
	}

	/**
	 * ::parse_host_parts()
	 *
	 * @dataProvider data_parse_host_parts
	 *
	 * @param string $host Host.
	 * @param mixed $expected Expected.
	 * @return void Nothing.
	 */
	function test_parse_host_parts(string $host, $expected) {
		$this->assertSame($expected, domain::parse_host_parts($host));
	}

	/**
	 * ->__toString()
	 *
	 * @dataProvider data_toString
	 *
	 * @param string $host Host.
	 * @param string $expected Expected.
	 * @return void Nothing.
	 */
	function test_toString(string $host, string $expected) {
		$thing = new domain($host);
		$thing = (string) $thing;
		$this->assertSame($expected, $thing);
	}

	/**
	 * ->is_valid()
	 *
	 * @dataProvider data_is_valid
	 *
	 * @param string $host Host.
	 * @param bool $fqdn FQDN.
	 * @param bool $expected Expected.
	 * @return void Nothing.
	 */
	function test_is_valid(string $host, $fqdn, bool $expected) {
		$thing = new domain($host);
		if (\is_bool($fqdn)) {
			$result = $thing->is_valid($fqdn);
		}
		else {
			$result = $thing->is_valid();
		}
		$this->assertSame($expected, $result);
	}

	/**
	 * ->is_ascii()
	 *
	 * @dataProvider data_is_ascii
	 *
	 * @param string $host Host.
	 * @param bool $expected Expected.
	 * @return void Nothing.
	 */
	function test_is_ascii(string $host, bool $expected) {
		$thing = new domain($host);
		$this->assertSame($expected, $thing->is_ascii());
	}

	/**
	 * ->is_fqdn()
	 *
	 * @dataProvider data_is_fqdn
	 *
	 * @param string $host Host.
	 * @param bool $expected Expected.
	 * @return void Nothing.
	 */
	function test_is_fqdn(string $host, bool $expected) {
		$thing = new domain($host);
		$this->assertSame($expected, $thing->is_fqdn());
	}

	/**
	 * ->is_ip()
	 *
	 * @dataProvider data_is_ip
	 *
	 * @param string $host Host.
	 * @param bool $restricted Allow restricted.
	 * @param bool $expected Expected.
	 * @return void Nothing.
	 */
	function test_is_ip(string $host, $restricted, bool $expected) {
		$thing = new domain($host);
		if (\is_bool($restricted)) {
			$result = $thing->is_ip($restricted);
		}
		else {
			$result = $thing->is_ip();
		}
		$this->assertSame($expected, $result);
	}

	/**
	 * ->is_unicode()
	 *
	 * @dataProvider data_is_unicode
	 *
	 * @param string $host Host.
	 * @param bool $expected Expected.
	 * @return void Nothing.
	 */
	function test_is_unicode(string $host, bool $expected) {
		$thing = new domain($host);
		$this->assertSame($expected, $thing->is_unicode());
	}

	/**
	 * ->has_dns()
	 *
	 * @dataProvider data_has_dns
	 *
	 * @param string $host Host.
	 * @param bool $expected Expected.
	 * @return void Nothing.
	 */
	function test_has_dns(string $host, bool $expected) {
		$thing = new domain($host);
		$this->assertSame($expected, $thing->has_dns());
	}

	/**
	 * ->strip_www()
	 *
	 * @dataProvider data_strip_www
	 *
	 * @param string $domain Domain.
	 * @param mixed $www Strip WWW.
	 * @param mixed $e_host Expected host.
	 * @param mixed $e_subdomain Expected subdomain.
	 * @return void Nothing.
	 */
	function test_strip_www(string $domain, $www, $e_host, $e_subdomain) {
		if (\is_bool($www)) {
			$thing = new domain($domain, $www);
		}
		else {
			$thing = new domain($domain);
		}

		$this->assertSame($e_host, $thing->get_host());
		$this->assertSame($e_subdomain, $thing->get_subdomain());
	}

	/**
	 * ->get_data()
	 *
	 * @dataProvider data_get_data
	 *
	 * @param string $domain Domain.
	 * @param mixed $unicode Unicode.
	 * @param mixed $expected Exected.
	 * @return void Nothing.
	 */
	function test_get_data(string $domain, $unicode, $expected) {
		// Initiate the object.
		$thing = new domain($domain);

		// Do we want Unicode?
		if (\is_bool($unicode)) {
			if ($unicode && ! \function_exists('idn_to_utf8')) {
				$this->markTestSkipped('The PHP intl extension is missing.');
			}
			$result = $thing->get_data($unicode);
		}
		else {
			$result = $thing->get_data();
		}
		$this->assertSame($expected, $result);
	}

	/**
	 * ->__get()
	 *
	 * @dataProvider data_get
	 *
	 * @param string $domain Domain.
	 * @param string $func Function.
	 * @param mixed $unicode Unicode.
	 * @param mixed $expected Expected.
	 * @return void Nothing.
	 */
	function test_get(string $domain, string $func, $unicode, $expected) {
		// Initiate the object.
		$thing = new domain($domain);

		// Only pass an argument if it isn't Null.
		if (\is_bool($unicode)) {
			if ($unicode && ! \function_exists('idn_to_utf8')) {
				$this->markTestSkipped('The PHP intl extension is missing.');
			}
			$result = $thing->{$func}($unicode);
		}
		else {
			$result = $thing->{$func}();
		}

		// Check the answer.
		$this->assertSame($expected, $result);
	}

	// ----------------------------------------------------------------- end tests



	// -----------------------------------------------------------------
	// Data
	// -----------------------------------------------------------------

	/**
	 * Data for ::parse_host()
	 *
	 * @return array Data.
	 */
	function data_parse_host() {
		return array(
			array(
				'http://☺.com',
				'xn--74h.com',
				'☺.com',
			),
			array(
				'//☺.com',
				'xn--74h.com',
				'☺.com',
			),
			array(
				'☺.com',
				'xn--74h.com',
				'☺.com',
			),
			array(
				'☺.com.',
				'xn--74h.com',
				'☺.com',
			),
			array(
				'.☺.com',
				'xn--74h.com',
				'☺.com',
			),
			array(
				'localhost',
				'localhost',
				'localhost',
			),
			array(
				'http://josh:here@[2600:3c00::f03c:91ff:feae:0ff2]:443/foobar',
				'2600:3c00::f03c:91ff:feae:ff2',
				'2600:3c00::f03c:91ff:feae:ff2',
			),
			array(
				'-localhost',
				false,
				false,
			),
			array(
				'local_host',
				false,
				false,
			),
			array(
				' localhost',
				'localhost',
				'localhost',
			),
		);
	}

	/**
	 * Data for ::parse_host_parts()
	 *
	 * @return array Data.
	 */
	function data_parse_host_parts() {
		return array(
			array(
				'.com',
				false,
			),
			array(
				'eXample.com',
				array(
					'host'=>'example.com',
					'subdomain'=>null,
					'domain'=>'example',
					'suffix'=>'com',
				),
			),
			array(
				'www.example.com',
				array(
					'host'=>'www.example.com',
					'subdomain'=>'www',
					'domain'=>'example',
					'suffix'=>'com',
				),
			),
			array(
				'www.example.co.uk',
				array(
					'host'=>'www.example.co.uk',
					'subdomain'=>'www',
					'domain'=>'example',
					'suffix'=>'co.uk',
				),
			),
			array(
				'co.uk',
				false,
			),
			array(
				'www.example.sch.uk',
				array(
					'host'=>'www.example.sch.uk',
					'subdomain'=>null,
					'domain'=>'www',
					'suffix'=>'example.sch.uk',
				),
			),
			array(
				'☺.com',
				array(
					'host'=>'xn--74h.com',
					'subdomain'=>null,
					'domain'=>'xn--74h',
					'suffix'=>'com',
				),
			),
		);
	}

	/**
	 * Data for ->toString()
	 *
	 * @return array Data.
	 */
	function data_toString() {
		return array(
			array(
				'www.example.sch.uk',
				'www.example.sch.uk',
			),
			array(
				'com',
				'',
			),
			array(
				'www.eXample.com',
				'www.example.com',
			),
			array(
				'☺.com',
				'xn--74h.com',
			),
		);
	}

	/**
	 * Data for ->is_valid()
	 *
	 * @return array Data.
	 */
	function data_is_valid() {
		return array(
			array(
				'example.com',
				null,
				true,
			),
			array(
				'com',
				null,
				false,
			),
			array(
				'blobfolio.com',
				true,
				true,
			),
			array(
				'127.0.0.1',
				null,
				true,
			),
			array(
				'127.0.0.1',
				true,
				false,
			),
			array(
				'☺.com',
				true,
				true,
			),
		);
	}

	/**
	 * Data for ->is_ascii()
	 *
	 * @return array Data.
	 */
	function data_is_ascii() {
		return array(
			array(
				'example.com',
				true,
			),
			array(
				'☺.com',
				false,
			),
			array(
				'xn--74h.com',
				false,
			),
			array(
				'127.0.0.1',
				true,
			),
			array(
				'2600:3c00::f03c:91ff:feae:0ff2',
				true,
			),
			array(
				'google.com',
				true,
			),
			array(
				'com',
				false,
			),
		);
	}

	/**
	 * Data for ->is_fqdn()
	 *
	 * @return array Data.
	 */
	function data_is_fqdn() {
		return array(
			array(
				'example.com',
				true,
			),
			array(
				'com',
				false,
			),
			array(
				'localhost',
				false,
			),
			array(
				'127.0.0.1',
				false,
			),
			array(
				'2600:3c00::f03c:91ff:feae:0ff2',
				true,
			),
		);
	}

	/**
	 * Data for ->is_ip()
	 *
	 * @return array Data.
	 */
	function data_is_ip() {
		return array(
			array(
				'example.com',
				false,
				false,
			),
			array(
				'example.com',
				null,
				false,
			),
			array(
				'127.0.0.1',
				null,
				true,
			),
			array(
				'127.0.0.1',
				false,
				false,
			),
			array(
				'127.0.0.1',
				true,
				true,
			),
			array(
				'2600:3c00::f03c:91ff:feae:0ff2',
				false,
				true,
			),
		);
	}

	/**
	 * Data for ->is_unicode()
	 *
	 * @return array Data.
	 */
	function data_is_unicode() {
		return array(
			array(
				'blobfolio.com',
				false,
			),
			array(
				'☺.com',
				true,
			),
			array(
				'xn--74h.com',
				true,
			),
			array(
				'com',
				false,
			),
			array(
				'googlE.com',
				false,
			),
			array(
				'127.0.0.1',
				false,
			),
		);
	}

	/**
	 * Data for ->has_dns()
	 *
	 * @return array Data.
	 */
	function data_has_dns() {
		return array(
			array(
				'blobfolio.com',
				true,
			),
			array(
				'asdfasfd.blobfolio.com',
				false,
			),
			array(
				'127.0.0.1',
				false,
			),
			array(
				'2600:3c00::f03c:91ff:feae:0ff2',
				true,
			),
		);
	}

	/**
	 * Data for ->strip_www()
	 *
	 * @return array Data.
	 */
	function data_strip_www() {
		return array(
			// This is a curve ball; in this case, "www" is a domain.
			array(
				'www.example.sch.uk',
				true,
				'www.example.sch.uk',
				null,
			),
			array(
				'www.google.com',
				true,
				'google.com',
				null,
			),
			array(
				'www.google.com',
				null,
				'www.google.com',
				'www',
			),
			array(
				'www.google.com',
				false,
				'www.google.com',
				'www',
			),
			array(
				'www.domains.google.com',
				true,
				'domains.google.com',
				'domains',
			),
			array(
				'www.domains.google.com',
				false,
				'www.domains.google.com',
				'www.domains',
			),
		);
	}

	/**
	 * Data for ->get_data()
	 *
	 * @return array Data.
	 */
	function data_get_data() {
		return array(
			array(
				'.com',
				null,
				false,
			),
			array(
				'eXample.com',
				null,
				array(
					'host'=>'example.com',
					'subdomain'=>null,
					'domain'=>'example',
					'suffix'=>'com',
				),
			),
			array(
				'www.eXample.com',
				null,
				array(
					'host'=>'www.example.com',
					'subdomain'=>'www',
					'domain'=>'example',
					'suffix'=>'com',
				),
			),
			array(
				'☺.com',
				null,
				array(
					'host'=>'xn--74h.com',
					'subdomain'=>null,
					'domain'=>'xn--74h',
					'suffix'=>'com',
				),
			),
			array(
				'☺.com',
				false,
				array(
					'host'=>'xn--74h.com',
					'subdomain'=>null,
					'domain'=>'xn--74h',
					'suffix'=>'com',
				),
			),
			array(
				'☺.com',
				true,
				array(
					'host'=>'☺.com',
					'subdomain'=>null,
					'domain'=>'☺',
					'suffix'=>'com',
				),
			),
		);
	}

	/**
	 * Data for ->get()
	 *
	 * @return array Data.
	 */
	function data_get() {
		return array(
			array(
				'eXample.com',
				'get_host',
				null,
				'example.com',
			),
			array(
				'eXample.com',
				'get_subdomain',
				null,
				null,
			),
			array(
				'eXample.com',
				'get_domain',
				null,
				'example',
			),
			array(
				'eXample.com',
				'get_suffix',
				null,
				'com',
			),
			array(
				'☺.com',
				'get_host',
				true,
				'☺.com',
			),
			array(
				'☺.com',
				'get_host',
				false,
				'xn--74h.com',
			),
			array(
				'☺.com',
				'get_domain',
				null,
				'xn--74h',
			),
			array(
				'☺.com',
				'get_domain',
				true,
				'☺',
			),
		);
	}

	// ----------------------------------------------------------------- end data
}


