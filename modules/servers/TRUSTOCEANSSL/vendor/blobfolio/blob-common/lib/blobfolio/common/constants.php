<?php
/**
 * Constants.
 *
 * Constants are stored here to reduce clutter elsewhere.
 *
 * @package blobfolio/common
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\common;

class constants {

	// This isn't constant so much as global, but here's as good a place
	// as any.
	public static $str_lock = false;

	// -------------------------------------------------
	// Character Sets.

	// Accented=>Regular.
	const ACCENT_CHARS = array(
		'ª'=>'a', 'º'=>'o', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A',
		'Ä'=>'A', 'Å'=>'A', 'Æ'=>'AE', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
		'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I',
		'Ð'=>'D', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O',
		'Ö'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y',
		'Þ'=>'TH', 'ß'=>'s', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a',
		'ä'=>'a', 'å'=>'a', 'æ'=>'ae', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
		'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i',
		'ð'=>'d', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
		'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u',
		'ý'=>'y', 'þ'=>'th', 'ÿ'=>'y', 'Ø'=>'O', 'Ā'=>'A', 'ā'=>'a',
		'Ă'=>'A', 'ă'=>'a', 'Ą'=>'A', 'ą'=>'a', 'Ć'=>'C', 'ć'=>'c',
		'Ĉ'=>'C', 'ĉ'=>'c', 'Ċ'=>'C', 'ċ'=>'c', 'Č'=>'C', 'č'=>'c',
		'Ď'=>'D', 'ď'=>'d', 'Đ'=>'D', 'đ'=>'d', 'Ē'=>'E', 'ē'=>'e',
		'Ĕ'=>'E', 'ĕ'=>'e', 'Ė'=>'E', 'ė'=>'e', 'Ę'=>'E', 'ę'=>'e',
		'Ě'=>'E', 'ě'=>'e', 'Ĝ'=>'G', 'ĝ'=>'g', 'Ğ'=>'G', 'ğ'=>'g',
		'Ġ'=>'G', 'ġ'=>'g', 'Ģ'=>'G', 'ģ'=>'g', 'Ĥ'=>'H', 'ĥ'=>'h',
		'Ħ'=>'H', 'ħ'=>'h', 'Ĩ'=>'I', 'ĩ'=>'i', 'Ī'=>'I', 'ī'=>'i',
		'Ĭ'=>'I', 'ĭ'=>'i', 'Į'=>'I', 'į'=>'i', 'İ'=>'I', 'ı'=>'i',
		'Ĳ'=>'IJ', 'ĳ'=>'ij', 'Ĵ'=>'J', 'ĵ'=>'j', 'Ķ'=>'K', 'ķ'=>'k',
		'ĸ'=>'k', 'Ĺ'=>'L', 'ĺ'=>'l', 'Ļ'=>'L', 'ļ'=>'l', 'Ľ'=>'L',
		'ľ'=>'l', 'Ŀ'=>'L', 'ŀ'=>'l', 'Ł'=>'L', 'ł'=>'l', 'Ń'=>'N',
		'ń'=>'n', 'Ņ'=>'N', 'ņ'=>'n', 'Ň'=>'N', 'ň'=>'n', 'ŉ'=>'N',
		'Ŋ'=>'n', 'ŋ'=>'N', 'Ō'=>'O', 'ō'=>'o', 'Ŏ'=>'O', 'ŏ'=>'o',
		'Ő'=>'O', 'ő'=>'o', 'Œ'=>'OE', 'œ'=>'oe', 'Ŕ'=>'R', 'ŕ'=>'r',
		'Ŗ'=>'R', 'ŗ'=>'r', 'Ř'=>'R', 'ř'=>'r', 'Ś'=>'S', 'ś'=>'s',
		'Ŝ'=>'S', 'ŝ'=>'s', 'Ş'=>'S', 'ş'=>'s', 'Š'=>'S', 'š'=>'s',
		'Ţ'=>'T', 'ţ'=>'t', 'Ť'=>'T', 'ť'=>'t', 'Ŧ'=>'T', 'ŧ'=>'t',
		'Ũ'=>'U', 'ũ'=>'u', 'Ū'=>'U', 'ū'=>'u', 'Ŭ'=>'U', 'ŭ'=>'u',
		'Ů'=>'U', 'ů'=>'u', 'Ű'=>'U', 'ű'=>'u', 'Ų'=>'U', 'ų'=>'u',
		'Ŵ'=>'W', 'ŵ'=>'w', 'Ŷ'=>'Y', 'ŷ'=>'y', 'Ÿ'=>'Y', 'Ź'=>'Z',
		'ź'=>'z', 'Ż'=>'Z', 'ż'=>'z', 'Ž'=>'Z', 'ž'=>'z', 'ſ'=>'s',
		'Ș'=>'S', 'ș'=>'s', 'Ț'=>'T', 'ț'=>'t', '€'=>'E', '£'=>'',
		'Ơ'=>'O', 'ơ'=>'o', 'Ư'=>'U', 'ư'=>'u', 'Ầ'=>'A', 'ầ'=>'a',
		'Ằ'=>'A', 'ằ'=>'a', 'Ề'=>'E', 'ề'=>'e', 'Ồ'=>'O', 'ồ'=>'o',
		'Ờ'=>'O', 'ờ'=>'o', 'Ừ'=>'U', 'ừ'=>'u', 'Ỳ'=>'Y', 'ỳ'=>'y',
		'Ả'=>'A', 'ả'=>'a', 'Ẩ'=>'A', 'ẩ'=>'a', 'Ẳ'=>'A', 'ẳ'=>'a',
		'Ẻ'=>'E', 'ẻ'=>'e', 'Ể'=>'E', 'ể'=>'e', 'Ỉ'=>'I', 'ỉ'=>'i',
		'Ỏ'=>'O', 'ỏ'=>'o', 'Ổ'=>'O', 'ổ'=>'o', 'Ở'=>'O', 'ở'=>'o',
		'Ủ'=>'U', 'ủ'=>'u', 'Ử'=>'U', 'ử'=>'u', 'Ỷ'=>'Y', 'ỷ'=>'y',
		'Ẫ'=>'A', 'ẫ'=>'a', 'Ẵ'=>'A', 'ẵ'=>'a', 'Ẽ'=>'E', 'ẽ'=>'e',
		'Ễ'=>'E', 'ễ'=>'e', 'Ỗ'=>'O', 'ỗ'=>'o', 'Ỡ'=>'O', 'ỡ'=>'o',
		'Ữ'=>'U', 'ữ'=>'u', 'Ỹ'=>'Y', 'ỹ'=>'y', 'Ấ'=>'A', 'ấ'=>'a',
		'Ắ'=>'A', 'ắ'=>'a', 'Ế'=>'E', 'ế'=>'e', 'Ố'=>'O', 'ố'=>'o',
		'Ớ'=>'O', 'ớ'=>'o', 'Ứ'=>'U', 'ứ'=>'u', 'Ạ'=>'A', 'ạ'=>'a',
		'Ậ'=>'A', 'ậ'=>'a', 'Ặ'=>'A', 'ặ'=>'a', 'Ẹ'=>'E', 'ẹ'=>'e',
		'Ệ'=>'E', 'ệ'=>'e', 'Ị'=>'I', 'ị'=>'i', 'Ọ'=>'O', 'ọ'=>'o',
		'Ộ'=>'O', 'ộ'=>'o', 'Ợ'=>'O', 'ợ'=>'o', 'Ụ'=>'U', 'ụ'=>'u',
		'Ự'=>'U', 'ự'=>'u', 'Ỵ'=>'Y', 'ỵ'=>'y', 'ɑ'=>'a', 'Ǖ'=>'U',
		'ǖ'=>'u', 'Ǘ'=>'U', 'ǘ'=>'u', 'Ǎ'=>'A', 'ǎ'=>'a', 'Ǐ'=>'I',
		'ǐ'=>'i', 'Ǒ'=>'O', 'ǒ'=>'o', 'Ǔ'=>'U', 'ǔ'=>'u', 'Ǚ'=>'U',
		'ǚ'=>'u', 'Ǜ'=>'U', 'ǜ'=>'u',
	);

	// Uncaught unicode upper=>lower.
	// @codingStandardsIgnoreStart
	const CASE_CHARS = array(
		"\xC7\x85"=>"\xC7\x86",			// 453=>454
		"\xC7\x88"=>"\xC7\x89",			// 456=>457
		"\xC7\x8B"=>"\xC7\x8C",			// 459=>460
		"\xC7\xB2"=>"\xC7\xB3",			// 498=>499
		"\xCF\xB7"=>"\xCF\xB8",			// 1015=>1016
		"\xCF\xB9"=>"\xCF\xB2",			// 1017=>1010
		"\xCF\xBA"=>"\xCF\xBB",			// 1018=>1019
		"\xE1\xBE\x88"=>"\xE1\xBE\x80",	// 8072=>8064
		"\xE1\xBE\x89"=>"\xE1\xBE\x81",	// 8073=>8065
		"\xE1\xBE\x8A"=>"\xE1\xBE\x82",	// 8074=>8066
		"\xE1\xBE\x8B"=>"\xE1\xBE\x83",	// 8075=>8067
		"\xE1\xBE\x8C"=>"\xE1\xBE\x84",	// 8076=>8068
		"\xE1\xBE\x8D"=>"\xE1\xBE\x85",	// 8077=>8069
		"\xE1\xBE\x8E"=>"\xE1\xBE\x86",	// 8078=>8070
		"\xE1\xBE\x8F"=>"\xE1\xBE\x87",	// 8079=>8071
		"\xE1\xBE\x98"=>"\xE1\xBE\x90",	// 8088=>8080
		"\xE1\xBE\x99"=>"\xE1\xBE\x91",	// 8089=>8081
		"\xE1\xBE\x9A"=>"\xE1\xBE\x92",	// 8090=>8082
		"\xE1\xBE\x9B"=>"\xE1\xBE\x93",	// 8091=>8083
		"\xE1\xBE\x9C"=>"\xE1\xBE\x94",	// 8092=>8084
		"\xE1\xBE\x9D"=>"\xE1\xBE\x95",	// 8093=>8085
		"\xE1\xBE\x9E"=>"\xE1\xBE\x96",	// 8094=>8086
		"\xE1\xBE\x9F"=>"\xE1\xBE\x97",	// 8095=>8087
		"\xE1\xBE\xA8"=>"\xE1\xBE\xA0",	// 8104=>8096
		"\xE1\xBE\xA9"=>"\xE1\xBE\xA1",	// 8105=>8097
		"\xE1\xBE\xAA"=>"\xE1\xBE\xA2",	// 8106=>8098
		"\xE1\xBE\xAB"=>"\xE1\xBE\xA3",	// 8107=>8099
		"\xE1\xBE\xAC"=>"\xE1\xBE\xA4",	// 8108=>8100
		"\xE1\xBE\xAD"=>"\xE1\xBE\xA5",	// 8109=>8101
		"\xE1\xBE\xAE"=>"\xE1\xBE\xA6",	// 8110=>8102
		"\xE1\xBE\xAF"=>"\xE1\xBE\xA7",	// 8111=>8103
		"\xE1\xBE\xBC"=>"\xE1\xBE\xB3",	// 8124=>8115
		"\xE1\xBF\x8C"=>"\xE1\xBF\x83",	// 8140=>8131
		"\xE1\xBF\xBC"=>"\xE1\xBF\xB3",	// 8188=>8179
		"\xE2\x85\xA0"=>"\xE2\x85\xB0",	// 8544=>8560
		"\xE2\x85\xA1"=>"\xE2\x85\xB1",	// 8545=>8561
		"\xE2\x85\xA2"=>"\xE2\x85\xB2",	// 8546=>8562
		"\xE2\x85\xA3"=>"\xE2\x85\xB3",	// 8547=>8563
		"\xE2\x85\xA4"=>"\xE2\x85\xB4",	// 8548=>8564
		"\xE2\x85\xA5"=>"\xE2\x85\xB5",	// 8549=>8565
		"\xE2\x85\xA6"=>"\xE2\x85\xB6",	// 8550=>8566
		"\xE2\x85\xA7"=>"\xE2\x85\xB7",	// 8551=>8567
		"\xE2\x85\xA8"=>"\xE2\x85\xB8",	// 8552=>8568
		"\xE2\x85\xA9"=>"\xE2\x85\xB9",	// 8553=>8569
		"\xE2\x85\xAA"=>"\xE2\x85\xBA",	// 8554=>8570
		"\xE2\x85\xAB"=>"\xE2\x85\xBB",	// 8555=>8571
		"\xE2\x85\xAC"=>"\xE2\x85\xBC",	// 8556=>8572
		"\xE2\x85\xAD"=>"\xE2\x85\xBD",	// 8557=>8573
		"\xE2\x85\xAE"=>"\xE2\x85\xBE",	// 8558=>8574
		"\xE2\x85\xAF"=>"\xE2\x85\xBF",	// 8559=>8575
		"\xE2\x92\xB6"=>"\xE2\x93\x90",	// 9398=>9424
		"\xE2\x92\xB7"=>"\xE2\x93\x91",	// 9399=>9425
		"\xE2\x92\xB8"=>"\xE2\x93\x92",	// 9400=>9426
		"\xE2\x92\xB9"=>"\xE2\x93\x93",	// 9401=>9427
		"\xE2\x92\xBA"=>"\xE2\x93\x94",	// 9402=>9428
		"\xE2\x92\xBB"=>"\xE2\x93\x95",	// 9403=>9429
		"\xE2\x92\xBC"=>"\xE2\x93\x96",	// 9404=>9430
		"\xE2\x92\xBD"=>"\xE2\x93\x97",	// 9405=>9431
		"\xE2\x92\xBE"=>"\xE2\x93\x98",	// 9406=>9432
		"\xE2\x92\xBF"=>"\xE2\x93\x99",	// 9407=>9433
		"\xE2\x93\x80"=>"\xE2\x93\x9A",	// 9408=>9434
		"\xE2\x93\x81"=>"\xE2\x93\x9B",	// 9409=>9435
		"\xE2\x93\x82"=>"\xE2\x93\x9C",	// 9410=>9436
		"\xE2\x93\x83"=>"\xE2\x93\x9D",	// 9411=>9437
		"\xE2\x93\x84"=>"\xE2\x93\x9E",	// 9412=>9438
		"\xE2\x93\x85"=>"\xE2\x93\x9F",	// 9413=>9439
		"\xE2\x93\x86"=>"\xE2\x93\xA0",	// 9414=>9440
		"\xE2\x93\x87"=>"\xE2\x93\xA1",	// 9415=>9441
		"\xE2\x93\x88"=>"\xE2\x93\xA2",	// 9416=>9442
		"\xE2\x93\x89"=>"\xE2\x93\xA3",	// 9417=>9443
		"\xE2\x93\x8A"=>"\xE2\x93\xA4",	// 9418=>9444
		"\xE2\x93\x8B"=>"\xE2\x93\xA5",	// 9419=>9445
		"\xE2\x93\x8C"=>"\xE2\x93\xA6",	// 9420=>9446
		"\xE2\x93\x8D"=>"\xE2\x93\xA7",	// 9421=>9447
		"\xE2\x93\x8E"=>"\xE2\x93\xA8",	// 9422=>9448
		"\xE2\x93\x8F"=>"\xE2\x93\xA9",	// 9423=>9449
		"\xF0\x90\xA6"=>"\xF0\x91\x8E",	// 66598=>66638
		"\xF0\x90\xA7"=>"\xF0\x91\x8F"	// 66599=>66639
	);
	// @codingStandardsIgnoreEnd

	// Weird numbers.
	const NUMBER_CHARS = array(
		"\xef\xbc\x90"=>0,
		"\xef\xbc\x91"=>1,
		"\xef\xbc\x92"=>2,
		"\xef\xbc\x93"=>3,
		"\xef\xbc\x94"=>4,
		"\xef\xbc\x95"=>5,
		"\xef\xbc\x96"=>6,
		"\xef\xbc\x97"=>7,
		"\xef\xbc\x98"=>8,
		"\xef\xbc\x99"=>9,
		"\xd9\xa0"=>0,
		"\xd9\xa1"=>1,
		"\xd9\xa2"=>2,
		"\xd9\xa3"=>3,
		"\xd9\xa4"=>4,
		"\xd9\xa5"=>5,
		"\xd9\xa6"=>6,
		"\xd9\xa7"=>7,
		"\xd9\xa8"=>8,
		"\xd9\xa9"=>9,
		"\xdb\xb0"=>0,
		"\xdb\xb1"=>1,
		"\xdb\xb2"=>2,
		"\xdb\xb3"=>3,
		"\xdb\xb4"=>4,
		"\xdb\xb5"=>5,
		"\xdb\xb6"=>6,
		"\xdb\xb7"=>7,
		"\xdb\xb8"=>8,
		"\xdb\xb9"=>9,
		"\xe1\xa0\x90"=>0,
		"\xe1\xa0\x91"=>1,
		"\xe1\xa0\x92"=>2,
		"\xe1\xa0\x93"=>3,
		"\xe1\xa0\x94"=>4,
		"\xe1\xa0\x95"=>5,
		"\xe1\xa0\x96"=>6,
		"\xe1\xa0\x97"=>7,
		"\xe1\xa0\x98"=>8,
		"\xe1\xa0\x99"=>9,
	);

	// Quote and apostrophe curly=>straight.
	const QUOTE_CHARS = array(
		// Windows codepage 1252.
		"\xC2\x82"=>"'",		// U+0082⇒U+201A single low-9 quotation mark.
		"\xC2\x84"=>'"',		// U+0084⇒U+201E double low-9 quotation mark.
		"\xC2\x8B"=>"'",		// U+008B⇒U+2039 single left-pointing angle quotation mark.
		"\xC2\x91"=>"'",		// U+0091⇒U+2018 left single quotation mark.
		"\xC2\x92"=>"'",		// U+0092⇒U+2019 right single quotation mark.
		"\xC2\x93"=>'"',		// U+0093⇒U+201C left double quotation mark.
		"\xC2\x94"=>'"',		// U+0094⇒U+201D right double quotation mark.
		"\xC2\x9B"=>"'",		// U+009B⇒U+203A single right-pointing angle quotation mark.

		// Regular Unicode.		// U+0022 quotation mark (").
								// U+0027 apostrophe     (').
		"\xC2\xAB"=>'"',		// U+00AB left-pointing double angle quotation mark.
		"\xC2\xBB"=>'"',		// U+00BB right-pointing double angle quotation mark.
		"\xE2\x80\x98"=>"'",	// U+2018 left single quotation mark.
		"\xE2\x80\x99"=>"'",	// U+2019 right single quotation mark.
		"\xE2\x80\x9A"=>"'",	// U+201A single low-9 quotation mark.
		"\xE2\x80\x9B"=>"'",	// U+201B single high-reversed-9 quotation mark.
		"\xE2\x80\x9C"=>'"',	// U+201C left double quotation mark.
		"\xE2\x80\x9D"=>'"',	// U+201D right double quotation mark.
		"\xE2\x80\x9E"=>'"',	// U+201E double low-9 quotation mark.
		"\xE2\x80\x9F"=>'"',	// U+201F double high-reversed-9 quotation mark.
		"\xE2\x80\xB9"=>"'",	// U+2039 single left-pointing angle quotation mark.
		"\xE2\x80\xBA"=>"'",	// U+203A single right-pointing angle quotation mark.
	);

	// Characters to use in random string.
	const RANDOM_CHARS = array(
		'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L',
		'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
		'Y', 'Z', '2', '3', '4', '5', '6', '7', '8', '9',
	);

	// Win-1252 to UTF-8.
	const WIN1252_CHARS = array(
		128=>"\xe2\x82\xac",
		130=>"\xe2\x80\x9a",
		131=>"\xc6\x92",
		132=>"\xe2\x80\x9e",
		133=>"\xe2\x80\xa6",
		134=>"\xe2\x80\xa0",
		135=>"\xe2\x80\xa1",
		136=>"\xcb\x86",
		137=>"\xe2\x80\xb0",
		138=>"\xc5\xa0",
		139=>"\xe2\x80\xb9",
		140=>"\xc5\x92",
		142=>"\xc5\xbd",
		145=>"\xe2\x80\x98",
		146=>"\xe2\x80\x99",
		147=>"\xe2\x80\x9c",
		148=>"\xe2\x80\x9d",
		149=>"\xe2\x80\xa2",
		150=>"\xe2\x80\x93",
		151=>"\xe2\x80\x94",
		152=>"\xcb\x9c",
		153=>"\xe2\x84\xa2",
		154=>"\xc5\xa1",
		155=>"\xe2\x80\xba",
		156=>"\xc5\x93",
		158=>"\xc5\xbe",
		159=>"\xc5\xb8",
	);



	// -------------------------------------------------
	// MIME Types.

	const MIME_DEFAULT = 'application/octet-stream';



	// -------------------------------------------------
	// Geography.

	const COUNTRIES = array(
		'US'=>array(
			'name'=>'USA',
			'region'=>'North America',
			'currency'=>'USD',
		),
		'CA'=>array(
			'name'=>'Canada',
			'region'=>'North America',
			'currency'=>'CAD',
		),
		'GB'=>array(
			'name'=>'United Kingdom',
			'region'=>'Europe',
			'currency'=>'GBP',
		),
		'AF'=>array(
			'name'=>'Afghanistan',
			'region'=>'Asia',
			'currency'=>'AFN',
		),
		'AX'=>array(
			'name'=>'Åland Islands',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'AL'=>array(
			'name'=>'Albania',
			'region'=>'Europe',
			'currency'=>'ALL',
		),
		'DZ'=>array(
			'name'=>'Algeria',
			'region'=>'Africa',
			'currency'=>'DZD',
		),
		'AS'=>array(
			'name'=>'American Samoa',
			'region'=>'Australia',
			'currency'=>'USD',
		),
		'AD'=>array(
			'name'=>'Andorra',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'AO'=>array(
			'name'=>'Angola',
			'region'=>'Africa',
			'currency'=>'AOA',
		),
		'AI'=>array(
			'name'=>'Anguilla',
			'region'=>'North America',
			'currency'=>'XCD',
		),
		'AG'=>array(
			'name'=>'Antigua and Barbuda',
			'region'=>'North America',
			'currency'=>'XCD',
		),
		'AR'=>array(
			'name'=>'Argentina',
			'region'=>'South America',
			'currency'=>'ARS',
		),
		'AM'=>array(
			'name'=>'Armenia',
			'region'=>'Europe',
			'currency'=>'AMD',
		),
		'AW'=>array(
			'name'=>'Aruba',
			'region'=>'South America',
			'currency'=>'AWG',
		),
		'AU'=>array(
			'name'=>'Australia',
			'region'=>'Australia',
			'currency'=>'AUD',
		),
		'AT'=>array(
			'name'=>'Austria',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'AZ'=>array(
			'name'=>'Azerbaijan',
			'region'=>'Asia',
			'currency'=>'AZN',
		),
		'BS'=>array(
			'name'=>'The Bahamas',
			'region'=>'North America',
			'currency'=>'BSD',
		),
		'BH'=>array(
			'name'=>'Bahrain',
			'region'=>'Asia',
			'currency'=>'BHD',
		),
		'BD'=>array(
			'name'=>'Bangladesh',
			'region'=>'Asia',
			'currency'=>'BDT',
		),
		'BB'=>array(
			'name'=>'Barbados',
			'region'=>'North America',
			'currency'=>'BBD',
		),
		'BY'=>array(
			'name'=>'Belarus',
			'region'=>'Europe',
			'currency'=>'BYN',
		),
		'BE'=>array(
			'name'=>'Belgium',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'BZ'=>array(
			'name'=>'Belize',
			'region'=>'North America',
			'currency'=>'BZD',
		),
		'BJ'=>array(
			'name'=>'Benin',
			'region'=>'Africa',
			'currency'=>'XOF',
		),
		'BM'=>array(
			'name'=>'Bermuda',
			'region'=>'North America',
			'currency'=>'BMD',
		),
		'BT'=>array(
			'name'=>'Bhutan',
			'region'=>'Asia',
			'currency'=>'BTN',
		),
		'BO'=>array(
			'name'=>'Bolivia',
			'region'=>'South America',
			'currency'=>'BOB',
		),
		'BQ'=>array(
			'name'=>'Bonaire, Sint Eustatius and Saba',
			'region'=>'South America',
			'currency'=>'USD',
		),
		'BA'=>array(
			'name'=>'Bosnia and Herzegovina',
			'region'=>'Europe',
			'currency'=>'BAM',
		),
		'BW'=>array(
			'name'=>'Botswana',
			'region'=>'Africa',
			'currency'=>'BWP',
		),
		'BR'=>array(
			'name'=>'Brazil',
			'region'=>'South America',
			'currency'=>'BRL',
		),
		'IO'=>array(
			'name'=>'British Indian Ocean Territory',
			'region'=>'Africa',
			'currency'=>'USD',
		),
		'VG'=>array(
			'name'=>'British Virgin Islands',
			'region'=>'North America',
			'currency'=>'USD',
		),
		'BN'=>array(
			'name'=>'Brunei',
			'region'=>'Asia',
			'currency'=>'BND',
		),
		'BG'=>array(
			'name'=>'Bulgaria',
			'region'=>'Europe',
			'currency'=>'BGN',
		),
		'BF'=>array(
			'name'=>'Burkina Faso',
			'region'=>'Africa',
			'currency'=>'XOF',
		),
		'BI'=>array(
			'name'=>'Burundi',
			'region'=>'Africa',
			'currency'=>'BIF',
		),
		'KH'=>array(
			'name'=>'Cambodia',
			'region'=>'Asia',
			'currency'=>'KHR',
		),
		'CM'=>array(
			'name'=>'Cameroon',
			'region'=>'Africa',
			'currency'=>'XAF',
		),
		'CV'=>array(
			'name'=>'Cape Verde',
			'region'=>'Africa',
			'currency'=>'CVE',
		),
		'KY'=>array(
			'name'=>'Cayman Islands',
			'region'=>'North America',
			'currency'=>'KYD',
		),
		'CF'=>array(
			'name'=>'Central African Republic',
			'region'=>'Africa',
			'currency'=>'XAF',
		),
		'TD'=>array(
			'name'=>'Chad',
			'region'=>'Africa',
			'currency'=>'XAF',
		),
		'CL'=>array(
			'name'=>'Chile',
			'region'=>'South America',
			'currency'=>'CLP',
		),
		'CN'=>array(
			'name'=>'China',
			'region'=>'Asia',
			'currency'=>'CNY',
		),
		'CX'=>array(
			'name'=>'Christmas Island',
			'region'=>'Australia',
			'currency'=>'AUD',
		),
		'CC'=>array(
			'name'=>'Cocos (Keeling) Islands',
			'region'=>'Australia',
			'currency'=>'AUD',
		),
		'CO'=>array(
			'name'=>'Colombia',
			'region'=>'South America',
			'currency'=>'COU',
		),
		'KM'=>array(
			'name'=>'Comoros',
			'region'=>'Africa',
			'currency'=>'KMF',
		),
		'CG'=>array(
			'name'=>'Congo',
			'region'=>'Africa',
			'currency'=>'XAF',
		),
		'CK'=>array(
			'name'=>'Cook Islands',
			'region'=>'Australia',
			'currency'=>'NZD',
		),
		'CR'=>array(
			'name'=>'Costa Rica',
			'region'=>'South America',
			'currency'=>'CRC',
		),
		'CI'=>array(
			'name'=>"Côte d'Ivoire",
			'region'=>'Africa',
			'currency'=>'XOF',
		),
		'HR'=>array(
			'name'=>'Croatia',
			'region'=>'Europe',
			'currency'=>'HRK',
		),
		'CU'=>array(
			'name'=>'Cuba',
			'region'=>'North America',
			'currency'=>'CUP',
		),
		'CW'=>array(
			'name'=>'Curaçao',
			'region'=>'South America',
			'currency'=>'ANG',
		),
		'CY'=>array(
			'name'=>'Cyprus',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'CZ'=>array(
			'name'=>'Czech Republic',
			'region'=>'Europe',
			'currency'=>'CZK',
		),
		'CD'=>array(
			'name'=>'Democratic Republic of the Congo',
			'region'=>'Africa',
			'currency'=>'CDF',
		),
		'DK'=>array(
			'name'=>'Denmark',
			'region'=>'Europe',
			'currency'=>'DKK',
		),
		'DJ'=>array(
			'name'=>'Djibouti',
			'region'=>'Africa',
			'currency'=>'DJF',
		),
		'DM'=>array(
			'name'=>'Dominica',
			'region'=>'North America',
			'currency'=>'XCD',
		),
		'DO'=>array(
			'name'=>'Dominican Republic',
			'region'=>'North America',
			'currency'=>'DOP',
		),
		'TL'=>array(
			'name'=>'East Timor',
			'region'=>'Asia',
			'currency'=>'USD',
		),
		'EC'=>array(
			'name'=>'Ecuador',
			'region'=>'South America',
			'currency'=>'USD',
		),
		'EG'=>array(
			'name'=>'Egypt',
			'region'=>'Africa',
			'currency'=>'EGP',
		),
		'SV'=>array(
			'name'=>'El Salvador',
			'region'=>'North America',
			'currency'=>'SVC',
		),
		'GQ'=>array(
			'name'=>'Equatorial Guinea',
			'region'=>'Africa',
			'currency'=>'XAF',
		),
		'ER'=>array(
			'name'=>'Eritrea',
			'region'=>'Africa',
			'currency'=>'ERN',
		),
		'EE'=>array(
			'name'=>'Estonia',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'ET'=>array(
			'name'=>'Ethiopia',
			'region'=>'Africa',
			'currency'=>'ETB',
		),
		'FK'=>array(
			'name'=>'Falkland Islands',
			'region'=>'South America',
			'currency'=>'FKP',
		),
		'FO'=>array(
			'name'=>'Faroe Islands',
			'region'=>'Europe',
			'currency'=>'DKK',
		),
		'FM'=>array(
			'name'=>'Federated States of Micronesia',
			'region'=>'Australia',
			'currency'=>'USD',
		),
		'FJ'=>array(
			'name'=>'Fiji',
			'region'=>'Australia',
			'currency'=>'FJD',
		),
		'FI'=>array(
			'name'=>'Finland',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'FR'=>array(
			'name'=>'France',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'GF'=>array(
			'name'=>'French Guiana',
			'region'=>'South America',
			'currency'=>'EUR',
		),
		'PF'=>array(
			'name'=>'French Polynesia',
			'region'=>'Australia',
			'currency'=>'XPF',
		),
		'GA'=>array(
			'name'=>'Gabon',
			'region'=>'Africa',
			'currency'=>'XAF',
		),
		'GM'=>array(
			'name'=>'Gambia',
			'region'=>'Africa',
			'currency'=>'GMD',
		),
		'GE'=>array(
			'name'=>'Georgia',
			'region'=>'Asia',
			'currency'=>'GEL',
		),
		'DE'=>array(
			'name'=>'Germany',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'GH'=>array(
			'name'=>'Ghana',
			'region'=>'Africa',
			'currency'=>'GHS',
		),
		'GI'=>array(
			'name'=>'Gibraltar',
			'region'=>'Europe',
			'currency'=>'GIP',
		),
		'GR'=>array(
			'name'=>'Greece',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'GL'=>array(
			'name'=>'Greenland',
			'region'=>'North America',
			'currency'=>'DKK',
		),
		'GD'=>array(
			'name'=>'Grenada',
			'region'=>'North America',
			'currency'=>'XCD',
		),
		'GP'=>array(
			'name'=>'Guadeloupe',
			'region'=>'North America',
			'currency'=>'EUR',
		),
		'GU'=>array(
			'name'=>'Guam',
			'region'=>'Australia',
			'currency'=>'USD',
		),
		'GT'=>array(
			'name'=>'Guatemala',
			'region'=>'North America',
			'currency'=>'GTQ',
		),
		'GG'=>array(
			'name'=>'Guernsey',
			'region'=>'Europe',
			'currency'=>'GBP',
		),
		'GN'=>array(
			'name'=>'Guinea',
			'region'=>'Africa',
			'currency'=>'GNF',
		),
		'GW'=>array(
			'name'=>'Guinea-Bissau',
			'region'=>'Africa',
			'currency'=>'XOF',
		),
		'GY'=>array(
			'name'=>'Guyana',
			'region'=>'South America',
			'currency'=>'GYD',
		),
		'HT'=>array(
			'name'=>'Haiti',
			'region'=>'North America',
			'currency'=>'USD',
		),
		'HN'=>array(
			'name'=>'Honduras',
			'region'=>'North America',
			'currency'=>'HNL',
		),
		'HK'=>array(
			'name'=>'Hong Kong',
			'region'=>'Asia',
			'currency'=>'HKD',
		),
		'HU'=>array(
			'name'=>'Hungary',
			'region'=>'Europe',
			'currency'=>'HUF',
		),
		'IS'=>array(
			'name'=>'Iceland',
			'region'=>'Europe',
			'currency'=>'ISK',
		),
		'IN'=>array(
			'name'=>'India',
			'region'=>'Asia',
			'currency'=>'INR',
		),
		'ID'=>array(
			'name'=>'Indonesia',
			'region'=>'Asia',
			'currency'=>'IDR',
		),
		'IR'=>array(
			'name'=>'Iran',
			'region'=>'Asia',
			'currency'=>'IRR',
		),
		'IQ'=>array(
			'name'=>'Iraq',
			'region'=>'Asia',
			'currency'=>'IQD',
		),
		'IE'=>array(
			'name'=>'Ireland',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'IM'=>array(
			'name'=>'Isle of Man',
			'region'=>'Europe',
			'currency'=>'GBP',
		),
		'IL'=>array(
			'name'=>'Israel',
			'region'=>'Asia',
			'currency'=>'ILS',
		),
		'IT'=>array(
			'name'=>'Italy',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'JM'=>array(
			'name'=>'Jamaica',
			'region'=>'North America',
			'currency'=>'JMD',
		),
		'JP'=>array(
			'name'=>'Japan',
			'region'=>'Asia',
			'currency'=>'JPY',
		),
		'JE'=>array(
			'name'=>'Jersey',
			'region'=>'Europe',
			'currency'=>'GBP',
		),
		'JO'=>array(
			'name'=>'Jordan',
			'region'=>'Asia',
			'currency'=>'JOD',
		),
		'KZ'=>array(
			'name'=>'Kazakhstan',
			'region'=>'Asia',
			'currency'=>'KZT',
		),
		'KE'=>array(
			'name'=>'Kenya',
			'region'=>'Africa',
			'currency'=>'KES',
		),
		'KI'=>array(
			'name'=>'Kiribati',
			'region'=>'Australia',
			'currency'=>'AUD',
		),
		'KW'=>array(
			'name'=>'Kuwait',
			'region'=>'Asia',
			'currency'=>'KWD',
		),
		'KG'=>array(
			'name'=>'Kyrgyzstan',
			'region'=>'Asia',
			'currency'=>'KGS',
		),
		'LA'=>array(
			'name'=>'Laos',
			'region'=>'Asia',
			'currency'=>'LAK',
		),
		'LV'=>array(
			'name'=>'Latvia',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'LB'=>array(
			'name'=>'Lebanon',
			'region'=>'Asia',
			'currency'=>'LBP',
		),
		'LS'=>array(
			'name'=>'Lesotho',
			'region'=>'Africa',
			'currency'=>'LSL',
		),
		'LR'=>array(
			'name'=>'Liberia',
			'region'=>'Africa',
			'currency'=>'LRD',
		),
		'LY'=>array(
			'name'=>'Libya',
			'region'=>'Africa',
			'currency'=>'LYD',
		),
		'LI'=>array(
			'name'=>'Liechtenstein',
			'region'=>'Europe',
			'currency'=>'CHF',
		),
		'LT'=>array(
			'name'=>'Lithuania',
			'region'=>'Europe',
			'currency'=>'LTL',
		),
		'LU'=>array(
			'name'=>'Luxembourg',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'MO'=>array(
			'name'=>'Macao',
			'region'=>'Asia',
			'currency'=>'MOP',
		),
		'MK'=>array(
			'name'=>'Macedonia',
			'region'=>'Europe',
			'currency'=>'MKD',
		),
		'MG'=>array(
			'name'=>'Madagascar',
			'region'=>'Africa',
			'currency'=>'MGA',
		),
		'MW'=>array(
			'name'=>'Malawi',
			'region'=>'Africa',
			'currency'=>'MWK',
		),
		'MY'=>array(
			'name'=>'Malaysia',
			'region'=>'Asia',
			'currency'=>'MYR',
		),
		'MV'=>array(
			'name'=>'Maldives',
			'region'=>'Asia',
			'currency'=>'MVR',
		),
		'ML'=>array(
			'name'=>'Mali',
			'region'=>'Africa',
			'currency'=>'XOF',
		),
		'MT'=>array(
			'name'=>'Malta',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'MH'=>array(
			'name'=>'Marshall Islands',
			'region'=>'Australia',
			'currency'=>'USD',
		),
		'MQ'=>array(
			'name'=>'Martinique',
			'region'=>'South America',
			'currency'=>'EUR',
		),
		'MR'=>array(
			'name'=>'Mauritania',
			'region'=>'Africa',
			'currency'=>'MRO',
		),
		'MU'=>array(
			'name'=>'Mauritius',
			'region'=>'Africa',
			'currency'=>'MUR',
		),
		'YT'=>array(
			'name'=>'Mayotte',
			'region'=>'Africa',
			'currency'=>'EUR',
		),
		'MX'=>array(
			'name'=>'Mexico',
			'region'=>'North America',
			'currency'=>'MXV',
		),
		'MD'=>array(
			'name'=>'Moldova',
			'region'=>'Europe',
			'currency'=>'MDL',
		),
		'MC'=>array(
			'name'=>'Monaco',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'MN'=>array(
			'name'=>'Mongolia',
			'region'=>'Asia',
			'currency'=>'MNT',
		),
		'ME'=>array(
			'name'=>'Montenegro',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'MS'=>array(
			'name'=>'Montserrat',
			'region'=>'North America',
			'currency'=>'XCD',
		),
		'MA'=>array(
			'name'=>'Morocco',
			'region'=>'Africa',
			'currency'=>'MAD',
		),
		'MZ'=>array(
			'name'=>'Mozambique',
			'region'=>'Africa',
			'currency'=>'MZN',
		),
		'MM'=>array(
			'name'=>'Myanmar (Burma)',
			'region'=>'Asia',
			'currency'=>'MMK',
		),
		'NA'=>array(
			'name'=>'Namibia',
			'region'=>'Africa',
			'currency'=>'NAD',
		),
		'NR'=>array(
			'name'=>'Nauru',
			'region'=>'Australia',
			'currency'=>'AUD',
		),
		'NP'=>array(
			'name'=>'Nepal',
			'region'=>'Asia',
			'currency'=>'NPR',
		),
		'NL'=>array(
			'name'=>'Netherlands',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'NC'=>array(
			'name'=>'New Caledonia',
			'region'=>'Australia',
			'currency'=>'XPF',
		),
		'NZ'=>array(
			'name'=>'New Zealand',
			'region'=>'Australia',
			'currency'=>'NZD',
		),
		'NI'=>array(
			'name'=>'Nicaragua',
			'region'=>'North America',
			'currency'=>'NIO',
		),
		'NE'=>array(
			'name'=>'Niger',
			'region'=>'Africa',
			'currency'=>'XOF',
		),
		'NG'=>array(
			'name'=>'Nigeria',
			'region'=>'Africa',
			'currency'=>'NGN',
		),
		'NU'=>array(
			'name'=>'Niue',
			'region'=>'Australia',
			'currency'=>'NZD',
		),
		'NF'=>array(
			'name'=>'Norfolk Island',
			'region'=>'Australia',
			'currency'=>'AUD',
		),
		'KP'=>array(
			'name'=>'North Korea',
			'region'=>'Asia',
			'currency'=>'KPW',
		),
		'MP'=>array(
			'name'=>'Northern Mariana Islands',
			'region'=>'Australia',
			'currency'=>'USD',
		),
		'NO'=>array(
			'name'=>'Norway',
			'region'=>'Europe',
			'currency'=>'NOK',
		),
		'OM'=>array(
			'name'=>'Oman',
			'region'=>'Asia',
			'currency'=>'OMR',
		),
		'PK'=>array(
			'name'=>'Pakistan',
			'region'=>'Asia',
			'currency'=>'PKR',
		),
		'PW'=>array(
			'name'=>'Palau',
			'region'=>'Australia',
			'currency'=>'USD',
		),
		'PS'=>array(
			'name'=>'Palestine',
			'region'=>'Asia',
			'currency'=>'EGP',
		),
		'PA'=>array(
			'name'=>'Panama',
			'region'=>'North America',
			'currency'=>'PAB',
		),
		'PG'=>array(
			'name'=>'Papua New Guinea',
			'region'=>'Australia',
			'currency'=>'PGK',
		),
		'PY'=>array(
			'name'=>'Paraguay',
			'region'=>'South America',
			'currency'=>'PYG',
		),
		'PE'=>array(
			'name'=>'Peru',
			'region'=>'South America',
			'currency'=>'PEN',
		),
		'PH'=>array(
			'name'=>'Philippines',
			'region'=>'Asia',
			'currency'=>'PHP',
		),
		'PL'=>array(
			'name'=>'Poland',
			'region'=>'Europe',
			'currency'=>'PLN',
		),
		'PT'=>array(
			'name'=>'Portugal',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'PR'=>array(
			'name'=>'Puerto Rico',
			'region'=>'North America',
			'currency'=>'USD',
		),
		'QA'=>array(
			'name'=>'Qatar',
			'region'=>'Asia',
			'currency'=>'QAR',
		),
		'RE'=>array(
			'name'=>'Réunion',
			'region'=>'Africa',
			'currency'=>'EUR',
		),
		'RO'=>array(
			'name'=>'Romania',
			'region'=>'Europe',
			'currency'=>'RON',
		),
		'RU'=>array(
			'name'=>'Russia',
			'region'=>'Europe',
			'currency'=>'RUB',
		),
		'RW'=>array(
			'name'=>'Rwanda',
			'region'=>'Africa',
			'currency'=>'RWF',
		),
		'BL'=>array(
			'name'=>'Saint Barthélemy',
			'region'=>'North America',
			'currency'=>'EUR',
		),
		'SH'=>array(
			'name'=>'Saint Helena, Ascension, and Tristan da Cunha',
			'region'=>'Africa',
			'currency'=>'SHP',
		),
		'KN'=>array(
			'name'=>'Saint Kitts and Nevis',
			'region'=>'North America',
			'currency'=>'XCD',
		),
		'LC'=>array(
			'name'=>'Saint Lucia',
			'region'=>'North America',
			'currency'=>'XCD',
		),
		'MF'=>array(
			'name'=>'Saint Martin',
			'region'=>'North America',
			'currency'=>'EUR',
		),
		'PM'=>array(
			'name'=>'Saint Pierre and Miquelon',
			'region'=>'North America',
			'currency'=>'EUR',
		),
		'VC'=>array(
			'name'=>'Saint Vincent and the Grenadines',
			'region'=>'North America',
			'currency'=>'XCD',
		),
		'WS'=>array(
			'name'=>'Samoa',
			'region'=>'Australia',
			'currency'=>'WST',
		),
		'SM'=>array(
			'name'=>'San Marino',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'ST'=>array(
			'name'=>'Sao Tomé and Príncipe',
			'region'=>'Africa',
			'currency'=>'STD',
		),
		'SA'=>array(
			'name'=>'Saudi Arabia',
			'region'=>'Asia',
			'currency'=>'SAR',
		),
		'SN'=>array(
			'name'=>'Senegal',
			'region'=>'Africa',
			'currency'=>'XOF',
		),
		'RS'=>array(
			'name'=>'Serbia',
			'region'=>'Europe',
			'currency'=>'RSD',
		),
		'SC'=>array(
			'name'=>'Seychelles',
			'region'=>'Africa',
			'currency'=>'SCR',
		),
		'SL'=>array(
			'name'=>'Sierra Leone',
			'region'=>'Africa',
			'currency'=>'SLL',
		),
		'SG'=>array(
			'name'=>'Singapore',
			'region'=>'Asia',
			'currency'=>'SGD',
		),
		'SX'=>array(
			'name'=>'Sint Maarten',
			'region'=>'North America',
			'currency'=>'ANG',
		),
		'SK'=>array(
			'name'=>'Slovakia',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'SI'=>array(
			'name'=>'Slovenia',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'SB'=>array(
			'name'=>'Solomon Islands',
			'region'=>'Australia',
			'currency'=>'SBD',
		),
		'SO'=>array(
			'name'=>'Somalia',
			'region'=>'Africa',
			'currency'=>'SOS',
		),
		'ZA'=>array(
			'name'=>'South Africa',
			'region'=>'Africa',
			'currency'=>'ZAR',
		),
		'KR'=>array(
			'name'=>'South Korea',
			'region'=>'Asia',
			'currency'=>'KRW',
		),
		'SS'=>array(
			'name'=>'South Sudan',
			'region'=>'Africa',
			'currency'=>'SSP',
		),
		'ES'=>array(
			'name'=>'Spain',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'LK'=>array(
			'name'=>'Sri Lanka',
			'region'=>'Asia',
			'currency'=>'LKR',
		),
		'SD'=>array(
			'name'=>'Sudan',
			'region'=>'Africa',
			'currency'=>'SDG',
		),
		'SR'=>array(
			'name'=>'Suriname',
			'region'=>'South America',
			'currency'=>'SRD',
		),
		'SJ'=>array(
			'name'=>'Svalbard and Jan Mayen',
			'region'=>'Europe',
			'currency'=>'NOK',
		),
		'SZ'=>array(
			'name'=>'Swaziland',
			'region'=>'Africa',
			'currency'=>'SZL',
		),
		'SE'=>array(
			'name'=>'Sweden',
			'region'=>'Europe',
			'currency'=>'SEK',
		),
		'CH'=>array(
			'name'=>'Switzerland',
			'region'=>'Europe',
			'currency'=>'CHW',
		),
		'SY'=>array(
			'name'=>'Syrian Arab Republic',
			'region'=>'Asia',
			'currency'=>'SYP',
		),
		'TW'=>array(
			'name'=>'Taiwan',
			'region'=>'Asia',
			'currency'=>'TWD',
		),
		'TJ'=>array(
			'name'=>'Tajikistan',
			'region'=>'Asia',
			'currency'=>'TJS',
		),
		'TZ'=>array(
			'name'=>'Tanzania',
			'region'=>'Africa',
			'currency'=>'TZS',
		),
		'TH'=>array(
			'name'=>'Thailand',
			'region'=>'Asia',
			'currency'=>'THB',
		),
		'TK'=>array(
			'name'=>'Tokelau',
			'region'=>'Australia',
			'currency'=>'NZD',
		),
		'TO'=>array(
			'name'=>'Tonga',
			'region'=>'Australia',
			'currency'=>'TOP',
		),
		'TG'=>array(
			'name'=>'Togo',
			'region'=>'Africa',
			'currency'=>'XOF',
		),
		'TT'=>array(
			'name'=>'Trinidad and Tobago',
			'region'=>'North America',
			'currency'=>'TTD',
		),
		'TN'=>array(
			'name'=>'Tunisia',
			'region'=>'Africa',
			'currency'=>'TND',
		),
		'TR'=>array(
			'name'=>'Turkey',
			'region'=>'Europe',
			'currency'=>'TRY',
		),
		'TM'=>array(
			'name'=>'Turkmenistan',
			'region'=>'Asia',
			'currency'=>'TMT',
		),
		'TC'=>array(
			'name'=>'Turks and Caicos Islands',
			'region'=>'North America',
			'currency'=>'USD',
		),
		'TV'=>array(
			'name'=>'Tuvalu',
			'region'=>'Australia',
			'currency'=>'AUD',
		),
		'UG'=>array(
			'name'=>'Uganda',
			'region'=>'Africa',
			'currency'=>'UGX',
		),
		'UA'=>array(
			'name'=>'Ukraine',
			'region'=>'Europe',
			'currency'=>'UAH',
		),
		'AE'=>array(
			'name'=>'United Arab Emirates',
			'region'=>'Asia',
			'currency'=>'AED',
		),
		'UY'=>array(
			'name'=>'Uruguay',
			'region'=>'South America',
			'currency'=>'UYU',
		),
		'VI'=>array(
			'name'=>'U.S. Virgin Islands',
			'region'=>'North America',
			'currency'=>'USD',
		),
		'UZ'=>array(
			'name'=>'Uzbekistan',
			'region'=>'Asia',
			'currency'=>'UZS',
		),
		'VA'=>array(
			'name'=>'Vatican City',
			'region'=>'Europe',
			'currency'=>'EUR',
		),
		'VE'=>array(
			'name'=>'Venezuela',
			'region'=>'South America',
			'currency'=>'VEF',
		),
		'VN'=>array(
			'name'=>'Vietnam',
			'region'=>'Asia',
			'currency'=>'VND',
		),
		'WF'=>array(
			'name'=>'Wallis and Futuna',
			'region'=>'Australia',
			'currency'=>'XPF',
		),
		'EH'=>array(
			'name'=>'Western Sahara',
			'region'=>'Africa',
			'currency'=>'MAD',
		),
		'YE'=>array(
			'name'=>'Yemen',
			'region'=>'Asia',
			'currency'=>'YER',
		),
		'ZM'=>array(
			'name'=>'Zambia',
			'region'=>'Africa',
			'currency'=>'ZMW',
		),
		'ZW'=>array(
			'name'=>'Zimbabwe',
			'region'=>'Africa',
			'currency'=>'USD',
		),
	);

	const PROVINCES = array(
		'AB'=>'Alberta',
		'BC'=>'British Columbia',
		'MB'=>'Manitoba',
		'NB'=>'New Brunswick',
		'NL'=>'Newfoundland',
		'NT'=>'Northwest Territories',
		'NS'=>'Nova Scotia',
		'NU'=>'Nunavut',
		'ON'=>'Ontario',
		'PE'=>'Prince Edward Island',
		'QC'=>'Quebec',
		'SK'=>'Saskatchewan',
		'YT'=>'Yukon',
	);

	const REGIONS = array(
		'Africa',
		'Asia',
		'Australia',
		'Europe',
		'North America',
		'South America',
	);

	const STATES = array(
		'AL'=>'Alabama',
		'AK'=>'Alaska',
		'AZ'=>'Arizona',
		'AR'=>'Arkansas',
		'CA'=>'California',
		'CO'=>'Colorado',
		'CT'=>'Connecticut',
		'DE'=>'Delaware',
		'DC'=>'District of Columbia',
		'FL'=>'Florida',
		'GA'=>'Georgia',
		'HI'=>'Hawaii',
		'ID'=>'Idaho',
		'IL'=>'Illinois',
		'IN'=>'Indiana',
		'IA'=>'Iowa',
		'KS'=>'Kansas',
		'KY'=>'Kentucky',
		'LA'=>'Louisiana',
		'ME'=>'Maine',
		'MD'=>'Maryland',
		'MA'=>'Massachusetts',
		'MI'=>'Michigan',
		'MN'=>'Minnesota',
		'MS'=>'Mississippi',
		'MO'=>'Missouri',
		'MT'=>'Montana',
		'NE'=>'Nebraska',
		'NV'=>'Nevada',
		'NH'=>'New Hampshire',
		'NJ'=>'New Jersey',
		'NM'=>'New Mexico',
		'NY'=>'New York',
		'NC'=>'North Carolina',
		'ND'=>'North Dakota',
		'OH'=>'Ohio',
		'OK'=>'Oklahoma',
		'OR'=>'Oregon',
		'PA'=>'Pennsylvania',
		'RI'=>'Rhode Island',
		'SC'=>'South Carolina',
		'SD'=>'South Dakota',
		'TN'=>'Tennessee',
		'TX'=>'Texas',
		'UT'=>'Utah',
		'VT'=>'Vermont',
		'VA'=>'Virginia',
		'WA'=>'Washington',
		'WV'=>'West Virginia',
		'WI'=>'Wisconsin',
		'WY'=>'Wyoming',
		'AA'=>'Armed Forces Americas',
		'AE'=>'Armed Forces Europe',
		'AP'=>'Armed Forces Pacific',
		'AS'=>'American Samoa',
		'FM'=>'Federated States of Micronesia',
		'GU'=>'Guam Gu',
		'MH'=>'Marshall Islands',
		'MP'=>'Northern Mariana Islands',
		'PW'=>'Palau',
		'PR'=>'Puerto Rico',
		'VI'=>'Virgin Islands',
	);

	const STATES_AU = array(
		'NSW'=>'New South Wales',
		'QLD'=>'Queensland',
		'SA'=>'South Australia',
		'TAS'=>'Tasmania',
		'VIC'=>'Victoria',
		'WA'=>'Western Australia',
		'ACT'=>'Australian Capital Territory',
		'JBT'=>'Jervis Bay Territory',
		'NT'=>'Northern Territory',
	);

	// Timezones.
	const TIMEZONES = array(
		'AFRICA/ABIDJAN'=>'Africa/Abidjan',
		'AFRICA/ACCRA'=>'Africa/Accra',
		'AFRICA/ADDIS_ABABA'=>'Africa/Addis_Ababa',
		'AFRICA/ALGIERS'=>'Africa/Algiers',
		'AFRICA/ASMARA'=>'Africa/Asmara',
		'AFRICA/BAMAKO'=>'Africa/Bamako',
		'AFRICA/BANGUI'=>'Africa/Bangui',
		'AFRICA/BANJUL'=>'Africa/Banjul',
		'AFRICA/BISSAU'=>'Africa/Bissau',
		'AFRICA/BLANTYRE'=>'Africa/Blantyre',
		'AFRICA/BRAZZAVILLE'=>'Africa/Brazzaville',
		'AFRICA/BUJUMBURA'=>'Africa/Bujumbura',
		'AFRICA/CAIRO'=>'Africa/Cairo',
		'AFRICA/CASABLANCA'=>'Africa/Casablanca',
		'AFRICA/CEUTA'=>'Africa/Ceuta',
		'AFRICA/CONAKRY'=>'Africa/Conakry',
		'AFRICA/DAKAR'=>'Africa/Dakar',
		'AFRICA/DAR_ES_SALAAM'=>'Africa/Dar_es_Salaam',
		'AFRICA/DJIBOUTI'=>'Africa/Djibouti',
		'AFRICA/DOUALA'=>'Africa/Douala',
		'AFRICA/EL_AAIUN'=>'Africa/El_Aaiun',
		'AFRICA/FREETOWN'=>'Africa/Freetown',
		'AFRICA/GABORONE'=>'Africa/Gaborone',
		'AFRICA/HARARE'=>'Africa/Harare',
		'AFRICA/JOHANNESBURG'=>'Africa/Johannesburg',
		'AFRICA/JUBA'=>'Africa/Juba',
		'AFRICA/KAMPALA'=>'Africa/Kampala',
		'AFRICA/KHARTOUM'=>'Africa/Khartoum',
		'AFRICA/KIGALI'=>'Africa/Kigali',
		'AFRICA/KINSHASA'=>'Africa/Kinshasa',
		'AFRICA/LAGOS'=>'Africa/Lagos',
		'AFRICA/LIBREVILLE'=>'Africa/Libreville',
		'AFRICA/LOME'=>'Africa/Lome',
		'AFRICA/LUANDA'=>'Africa/Luanda',
		'AFRICA/LUBUMBASHI'=>'Africa/Lubumbashi',
		'AFRICA/LUSAKA'=>'Africa/Lusaka',
		'AFRICA/MALABO'=>'Africa/Malabo',
		'AFRICA/MAPUTO'=>'Africa/Maputo',
		'AFRICA/MASERU'=>'Africa/Maseru',
		'AFRICA/MBABANE'=>'Africa/Mbabane',
		'AFRICA/MOGADISHU'=>'Africa/Mogadishu',
		'AFRICA/MONROVIA'=>'Africa/Monrovia',
		'AFRICA/NAIROBI'=>'Africa/Nairobi',
		'AFRICA/NDJAMENA'=>'Africa/Ndjamena',
		'AFRICA/NIAMEY'=>'Africa/Niamey',
		'AFRICA/NOUAKCHOTT'=>'Africa/Nouakchott',
		'AFRICA/OUAGADOUGOU'=>'Africa/Ouagadougou',
		'AFRICA/PORTO-NOVO'=>'Africa/Porto-Novo',
		'AFRICA/SAO_TOME'=>'Africa/Sao_Tome',
		'AFRICA/TRIPOLI'=>'Africa/Tripoli',
		'AFRICA/TUNIS'=>'Africa/Tunis',
		'AFRICA/WINDHOEK'=>'Africa/Windhoek',
		'AMERICA/ADAK'=>'America/Adak',
		'AMERICA/ANCHORAGE'=>'America/Anchorage',
		'AMERICA/ANGUILLA'=>'America/Anguilla',
		'AMERICA/ANTIGUA'=>'America/Antigua',
		'AMERICA/ARAGUAINA'=>'America/Araguaina',
		'AMERICA/ARGENTINA/BUENOS_AIRES'=>'America/Argentina/Buenos_Aires',
		'AMERICA/ARGENTINA/CATAMARCA'=>'America/Argentina/Catamarca',
		'AMERICA/ARGENTINA/CORDOBA'=>'America/Argentina/Cordoba',
		'AMERICA/ARGENTINA/JUJUY'=>'America/Argentina/Jujuy',
		'AMERICA/ARGENTINA/LA_RIOJA'=>'America/Argentina/La_Rioja',
		'AMERICA/ARGENTINA/MENDOZA'=>'America/Argentina/Mendoza',
		'AMERICA/ARGENTINA/RIO_GALLEGOS'=>'America/Argentina/Rio_Gallegos',
		'AMERICA/ARGENTINA/SALTA'=>'America/Argentina/Salta',
		'AMERICA/ARGENTINA/SAN_JUAN'=>'America/Argentina/San_Juan',
		'AMERICA/ARGENTINA/SAN_LUIS'=>'America/Argentina/San_Luis',
		'AMERICA/ARGENTINA/TUCUMAN'=>'America/Argentina/Tucuman',
		'AMERICA/ARGENTINA/USHUAIA'=>'America/Argentina/Ushuaia',
		'AMERICA/ARUBA'=>'America/Aruba',
		'AMERICA/ASUNCION'=>'America/Asuncion',
		'AMERICA/ATIKOKAN'=>'America/Atikokan',
		'AMERICA/BAHIA'=>'America/Bahia',
		'AMERICA/BAHIA_BANDERAS'=>'America/Bahia_Banderas',
		'AMERICA/BARBADOS'=>'America/Barbados',
		'AMERICA/BELEM'=>'America/Belem',
		'AMERICA/BELIZE'=>'America/Belize',
		'AMERICA/BLANC-SABLON'=>'America/Blanc-Sablon',
		'AMERICA/BOA_VISTA'=>'America/Boa_Vista',
		'AMERICA/BOGOTA'=>'America/Bogota',
		'AMERICA/BOISE'=>'America/Boise',
		'AMERICA/CAMBRIDGE_BAY'=>'America/Cambridge_Bay',
		'AMERICA/CAMPO_GRANDE'=>'America/Campo_Grande',
		'AMERICA/CANCUN'=>'America/Cancun',
		'AMERICA/CARACAS'=>'America/Caracas',
		'AMERICA/CAYENNE'=>'America/Cayenne',
		'AMERICA/CAYMAN'=>'America/Cayman',
		'AMERICA/CHICAGO'=>'America/Chicago',
		'AMERICA/CHIHUAHUA'=>'America/Chihuahua',
		'AMERICA/COSTA_RICA'=>'America/Costa_Rica',
		'AMERICA/CRESTON'=>'America/Creston',
		'AMERICA/CUIABA'=>'America/Cuiaba',
		'AMERICA/CURACAO'=>'America/Curacao',
		'AMERICA/DANMARKSHAVN'=>'America/Danmarkshavn',
		'AMERICA/DAWSON'=>'America/Dawson',
		'AMERICA/DAWSON_CREEK'=>'America/Dawson_Creek',
		'AMERICA/DENVER'=>'America/Denver',
		'AMERICA/DETROIT'=>'America/Detroit',
		'AMERICA/DOMINICA'=>'America/Dominica',
		'AMERICA/EDMONTON'=>'America/Edmonton',
		'AMERICA/EIRUNEPE'=>'America/Eirunepe',
		'AMERICA/EL_SALVADOR'=>'America/El_Salvador',
		'AMERICA/FORT_NELSON'=>'America/Fort_Nelson',
		'AMERICA/FORTALEZA'=>'America/Fortaleza',
		'AMERICA/GLACE_BAY'=>'America/Glace_Bay',
		'AMERICA/GODTHAB'=>'America/Godthab',
		'AMERICA/GOOSE_BAY'=>'America/Goose_Bay',
		'AMERICA/GRAND_TURK'=>'America/Grand_Turk',
		'AMERICA/GRENADA'=>'America/Grenada',
		'AMERICA/GUADELOUPE'=>'America/Guadeloupe',
		'AMERICA/GUATEMALA'=>'America/Guatemala',
		'AMERICA/GUAYAQUIL'=>'America/Guayaquil',
		'AMERICA/GUYANA'=>'America/Guyana',
		'AMERICA/HALIFAX'=>'America/Halifax',
		'AMERICA/HAVANA'=>'America/Havana',
		'AMERICA/HERMOSILLO'=>'America/Hermosillo',
		'AMERICA/INDIANA/INDIANAPOLIS'=>'America/Indiana/Indianapolis',
		'AMERICA/INDIANA/KNOX'=>'America/Indiana/Knox',
		'AMERICA/INDIANA/MARENGO'=>'America/Indiana/Marengo',
		'AMERICA/INDIANA/PETERSBURG'=>'America/Indiana/Petersburg',
		'AMERICA/INDIANA/TELL_CITY'=>'America/Indiana/Tell_City',
		'AMERICA/INDIANA/VEVAY'=>'America/Indiana/Vevay',
		'AMERICA/INDIANA/VINCENNES'=>'America/Indiana/Vincennes',
		'AMERICA/INDIANA/WINAMAC'=>'America/Indiana/Winamac',
		'AMERICA/INUVIK'=>'America/Inuvik',
		'AMERICA/IQALUIT'=>'America/Iqaluit',
		'AMERICA/JAMAICA'=>'America/Jamaica',
		'AMERICA/JUNEAU'=>'America/Juneau',
		'AMERICA/KENTUCKY/LOUISVILLE'=>'America/Kentucky/Louisville',
		'AMERICA/KENTUCKY/MONTICELLO'=>'America/Kentucky/Monticello',
		'AMERICA/KRALENDIJK'=>'America/Kralendijk',
		'AMERICA/LA_PAZ'=>'America/La_Paz',
		'AMERICA/LIMA'=>'America/Lima',
		'AMERICA/LOS_ANGELES'=>'America/Los_Angeles',
		'AMERICA/LOWER_PRINCES'=>'America/Lower_Princes',
		'AMERICA/MACEIO'=>'America/Maceio',
		'AMERICA/MANAGUA'=>'America/Managua',
		'AMERICA/MANAUS'=>'America/Manaus',
		'AMERICA/MARIGOT'=>'America/Marigot',
		'AMERICA/MARTINIQUE'=>'America/Martinique',
		'AMERICA/MATAMOROS'=>'America/Matamoros',
		'AMERICA/MAZATLAN'=>'America/Mazatlan',
		'AMERICA/MENOMINEE'=>'America/Menominee',
		'AMERICA/MERIDA'=>'America/Merida',
		'AMERICA/METLAKATLA'=>'America/Metlakatla',
		'AMERICA/MEXICO_CITY'=>'America/Mexico_City',
		'AMERICA/MIQUELON'=>'America/Miquelon',
		'AMERICA/MONCTON'=>'America/Moncton',
		'AMERICA/MONTERREY'=>'America/Monterrey',
		'AMERICA/MONTEVIDEO'=>'America/Montevideo',
		'AMERICA/MONTSERRAT'=>'America/Montserrat',
		'AMERICA/NASSAU'=>'America/Nassau',
		'AMERICA/NEW_YORK'=>'America/New_York',
		'AMERICA/NIPIGON'=>'America/Nipigon',
		'AMERICA/NOME'=>'America/Nome',
		'AMERICA/NORONHA'=>'America/Noronha',
		'AMERICA/NORTH_DAKOTA/BEULAH'=>'America/North_Dakota/Beulah',
		'AMERICA/NORTH_DAKOTA/CENTER'=>'America/North_Dakota/Center',
		'AMERICA/NORTH_DAKOTA/NEW_SALEM'=>'America/North_Dakota/New_Salem',
		'AMERICA/OJINAGA'=>'America/Ojinaga',
		'AMERICA/PANAMA'=>'America/Panama',
		'AMERICA/PANGNIRTUNG'=>'America/Pangnirtung',
		'AMERICA/PARAMARIBO'=>'America/Paramaribo',
		'AMERICA/PHOENIX'=>'America/Phoenix',
		'AMERICA/PORT-AU-PRINCE'=>'America/Port-au-Prince',
		'AMERICA/PORT_OF_SPAIN'=>'America/Port_of_Spain',
		'AMERICA/PORTO_VELHO'=>'America/Porto_Velho',
		'AMERICA/PUERTO_RICO'=>'America/Puerto_Rico',
		'AMERICA/PUNTA_ARENAS'=>'America/Punta_Arenas',
		'AMERICA/RAINY_RIVER'=>'America/Rainy_River',
		'AMERICA/RANKIN_INLET'=>'America/Rankin_Inlet',
		'AMERICA/RECIFE'=>'America/Recife',
		'AMERICA/REGINA'=>'America/Regina',
		'AMERICA/RESOLUTE'=>'America/Resolute',
		'AMERICA/RIO_BRANCO'=>'America/Rio_Branco',
		'AMERICA/SANTAREM'=>'America/Santarem',
		'AMERICA/SANTIAGO'=>'America/Santiago',
		'AMERICA/SANTO_DOMINGO'=>'America/Santo_Domingo',
		'AMERICA/SAO_PAULO'=>'America/Sao_Paulo',
		'AMERICA/SCORESBYSUND'=>'America/Scoresbysund',
		'AMERICA/SITKA'=>'America/Sitka',
		'AMERICA/ST_BARTHELEMY'=>'America/St_Barthelemy',
		'AMERICA/ST_JOHNS'=>'America/St_Johns',
		'AMERICA/ST_KITTS'=>'America/St_Kitts',
		'AMERICA/ST_LUCIA'=>'America/St_Lucia',
		'AMERICA/ST_THOMAS'=>'America/St_Thomas',
		'AMERICA/ST_VINCENT'=>'America/St_Vincent',
		'AMERICA/SWIFT_CURRENT'=>'America/Swift_Current',
		'AMERICA/TEGUCIGALPA'=>'America/Tegucigalpa',
		'AMERICA/THULE'=>'America/Thule',
		'AMERICA/THUNDER_BAY'=>'America/Thunder_Bay',
		'AMERICA/TIJUANA'=>'America/Tijuana',
		'AMERICA/TORONTO'=>'America/Toronto',
		'AMERICA/TORTOLA'=>'America/Tortola',
		'AMERICA/VANCOUVER'=>'America/Vancouver',
		'AMERICA/WHITEHORSE'=>'America/Whitehorse',
		'AMERICA/WINNIPEG'=>'America/Winnipeg',
		'AMERICA/YAKUTAT'=>'America/Yakutat',
		'AMERICA/YELLOWKNIFE'=>'America/Yellowknife',
		'ANTARCTICA/CASEY'=>'Antarctica/Casey',
		'ANTARCTICA/DAVIS'=>'Antarctica/Davis',
		'ANTARCTICA/DUMONTDURVILLE'=>'Antarctica/DumontDUrville',
		'ANTARCTICA/MACQUARIE'=>'Antarctica/Macquarie',
		'ANTARCTICA/MAWSON'=>'Antarctica/Mawson',
		'ANTARCTICA/MCMURDO'=>'Antarctica/McMurdo',
		'ANTARCTICA/PALMER'=>'Antarctica/Palmer',
		'ANTARCTICA/ROTHERA'=>'Antarctica/Rothera',
		'ANTARCTICA/SYOWA'=>'Antarctica/Syowa',
		'ANTARCTICA/TROLL'=>'Antarctica/Troll',
		'ANTARCTICA/VOSTOK'=>'Antarctica/Vostok',
		'ARCTIC/LONGYEARBYEN'=>'Arctic/Longyearbyen',
		'ASIA/ADEN'=>'Asia/Aden',
		'ASIA/ALMATY'=>'Asia/Almaty',
		'ASIA/AMMAN'=>'Asia/Amman',
		'ASIA/ANADYR'=>'Asia/Anadyr',
		'ASIA/AQTAU'=>'Asia/Aqtau',
		'ASIA/AQTOBE'=>'Asia/Aqtobe',
		'ASIA/ASHGABAT'=>'Asia/Ashgabat',
		'ASIA/ATYRAU'=>'Asia/Atyrau',
		'ASIA/BAGHDAD'=>'Asia/Baghdad',
		'ASIA/BAHRAIN'=>'Asia/Bahrain',
		'ASIA/BAKU'=>'Asia/Baku',
		'ASIA/BANGKOK'=>'Asia/Bangkok',
		'ASIA/BARNAUL'=>'Asia/Barnaul',
		'ASIA/BEIRUT'=>'Asia/Beirut',
		'ASIA/BISHKEK'=>'Asia/Bishkek',
		'ASIA/BRUNEI'=>'Asia/Brunei',
		'ASIA/CHITA'=>'Asia/Chita',
		'ASIA/CHOIBALSAN'=>'Asia/Choibalsan',
		'ASIA/COLOMBO'=>'Asia/Colombo',
		'ASIA/DAMASCUS'=>'Asia/Damascus',
		'ASIA/DHAKA'=>'Asia/Dhaka',
		'ASIA/DILI'=>'Asia/Dili',
		'ASIA/DUBAI'=>'Asia/Dubai',
		'ASIA/DUSHANBE'=>'Asia/Dushanbe',
		'ASIA/FAMAGUSTA'=>'Asia/Famagusta',
		'ASIA/GAZA'=>'Asia/Gaza',
		'ASIA/HEBRON'=>'Asia/Hebron',
		'ASIA/HO_CHI_MINH'=>'Asia/Ho_Chi_Minh',
		'ASIA/HONG_KONG'=>'Asia/Hong_Kong',
		'ASIA/HOVD'=>'Asia/Hovd',
		'ASIA/IRKUTSK'=>'Asia/Irkutsk',
		'ASIA/JAKARTA'=>'Asia/Jakarta',
		'ASIA/JAYAPURA'=>'Asia/Jayapura',
		'ASIA/JERUSALEM'=>'Asia/Jerusalem',
		'ASIA/KABUL'=>'Asia/Kabul',
		'ASIA/KAMCHATKA'=>'Asia/Kamchatka',
		'ASIA/KARACHI'=>'Asia/Karachi',
		'ASIA/KATHMANDU'=>'Asia/Kathmandu',
		'ASIA/KHANDYGA'=>'Asia/Khandyga',
		'ASIA/KOLKATA'=>'Asia/Kolkata',
		'ASIA/KRASNOYARSK'=>'Asia/Krasnoyarsk',
		'ASIA/KUALA_LUMPUR'=>'Asia/Kuala_Lumpur',
		'ASIA/KUCHING'=>'Asia/Kuching',
		'ASIA/KUWAIT'=>'Asia/Kuwait',
		'ASIA/MACAU'=>'Asia/Macau',
		'ASIA/MAGADAN'=>'Asia/Magadan',
		'ASIA/MAKASSAR'=>'Asia/Makassar',
		'ASIA/MANILA'=>'Asia/Manila',
		'ASIA/MUSCAT'=>'Asia/Muscat',
		'ASIA/NICOSIA'=>'Asia/Nicosia',
		'ASIA/NOVOKUZNETSK'=>'Asia/Novokuznetsk',
		'ASIA/NOVOSIBIRSK'=>'Asia/Novosibirsk',
		'ASIA/OMSK'=>'Asia/Omsk',
		'ASIA/ORAL'=>'Asia/Oral',
		'ASIA/PHNOM_PENH'=>'Asia/Phnom_Penh',
		'ASIA/PONTIANAK'=>'Asia/Pontianak',
		'ASIA/PYONGYANG'=>'Asia/Pyongyang',
		'ASIA/QATAR'=>'Asia/Qatar',
		'ASIA/QYZYLORDA'=>'Asia/Qyzylorda',
		'ASIA/RIYADH'=>'Asia/Riyadh',
		'ASIA/SAKHALIN'=>'Asia/Sakhalin',
		'ASIA/SAMARKAND'=>'Asia/Samarkand',
		'ASIA/SEOUL'=>'Asia/Seoul',
		'ASIA/SHANGHAI'=>'Asia/Shanghai',
		'ASIA/SINGAPORE'=>'Asia/Singapore',
		'ASIA/SREDNEKOLYMSK'=>'Asia/Srednekolymsk',
		'ASIA/TAIPEI'=>'Asia/Taipei',
		'ASIA/TASHKENT'=>'Asia/Tashkent',
		'ASIA/TBILISI'=>'Asia/Tbilisi',
		'ASIA/TEHRAN'=>'Asia/Tehran',
		'ASIA/THIMPHU'=>'Asia/Thimphu',
		'ASIA/TOKYO'=>'Asia/Tokyo',
		'ASIA/TOMSK'=>'Asia/Tomsk',
		'ASIA/ULAANBAATAR'=>'Asia/Ulaanbaatar',
		'ASIA/URUMQI'=>'Asia/Urumqi',
		'ASIA/UST-NERA'=>'Asia/Ust-Nera',
		'ASIA/VIENTIANE'=>'Asia/Vientiane',
		'ASIA/VLADIVOSTOK'=>'Asia/Vladivostok',
		'ASIA/YAKUTSK'=>'Asia/Yakutsk',
		'ASIA/YANGON'=>'Asia/Yangon',
		'ASIA/YEKATERINBURG'=>'Asia/Yekaterinburg',
		'ASIA/YEREVAN'=>'Asia/Yerevan',
		'ATLANTIC/AZORES'=>'Atlantic/Azores',
		'ATLANTIC/BERMUDA'=>'Atlantic/Bermuda',
		'ATLANTIC/CANARY'=>'Atlantic/Canary',
		'ATLANTIC/CAPE_VERDE'=>'Atlantic/Cape_Verde',
		'ATLANTIC/FAROE'=>'Atlantic/Faroe',
		'ATLANTIC/MADEIRA'=>'Atlantic/Madeira',
		'ATLANTIC/REYKJAVIK'=>'Atlantic/Reykjavik',
		'ATLANTIC/SOUTH_GEORGIA'=>'Atlantic/South_Georgia',
		'ATLANTIC/ST_HELENA'=>'Atlantic/St_Helena',
		'ATLANTIC/STANLEY'=>'Atlantic/Stanley',
		'AUSTRALIA/ADELAIDE'=>'Australia/Adelaide',
		'AUSTRALIA/BRISBANE'=>'Australia/Brisbane',
		'AUSTRALIA/BROKEN_HILL'=>'Australia/Broken_Hill',
		'AUSTRALIA/CURRIE'=>'Australia/Currie',
		'AUSTRALIA/DARWIN'=>'Australia/Darwin',
		'AUSTRALIA/EUCLA'=>'Australia/Eucla',
		'AUSTRALIA/HOBART'=>'Australia/Hobart',
		'AUSTRALIA/LINDEMAN'=>'Australia/Lindeman',
		'AUSTRALIA/LORD_HOWE'=>'Australia/Lord_Howe',
		'AUSTRALIA/MELBOURNE'=>'Australia/Melbourne',
		'AUSTRALIA/PERTH'=>'Australia/Perth',
		'AUSTRALIA/SYDNEY'=>'Australia/Sydney',
		'EUROPE/AMSTERDAM'=>'Europe/Amsterdam',
		'EUROPE/ANDORRA'=>'Europe/Andorra',
		'EUROPE/ASTRAKHAN'=>'Europe/Astrakhan',
		'EUROPE/ATHENS'=>'Europe/Athens',
		'EUROPE/BELGRADE'=>'Europe/Belgrade',
		'EUROPE/BERLIN'=>'Europe/Berlin',
		'EUROPE/BRATISLAVA'=>'Europe/Bratislava',
		'EUROPE/BRUSSELS'=>'Europe/Brussels',
		'EUROPE/BUCHAREST'=>'Europe/Bucharest',
		'EUROPE/BUDAPEST'=>'Europe/Budapest',
		'EUROPE/BUSINGEN'=>'Europe/Busingen',
		'EUROPE/CHISINAU'=>'Europe/Chisinau',
		'EUROPE/COPENHAGEN'=>'Europe/Copenhagen',
		'EUROPE/DUBLIN'=>'Europe/Dublin',
		'EUROPE/GIBRALTAR'=>'Europe/Gibraltar',
		'EUROPE/GUERNSEY'=>'Europe/Guernsey',
		'EUROPE/HELSINKI'=>'Europe/Helsinki',
		'EUROPE/ISLE_OF_MAN'=>'Europe/Isle_of_Man',
		'EUROPE/ISTANBUL'=>'Europe/Istanbul',
		'EUROPE/JERSEY'=>'Europe/Jersey',
		'EUROPE/KALININGRAD'=>'Europe/Kaliningrad',
		'EUROPE/KIEV'=>'Europe/Kiev',
		'EUROPE/KIROV'=>'Europe/Kirov',
		'EUROPE/LISBON'=>'Europe/Lisbon',
		'EUROPE/LJUBLJANA'=>'Europe/Ljubljana',
		'EUROPE/LONDON'=>'Europe/London',
		'EUROPE/LUXEMBOURG'=>'Europe/Luxembourg',
		'EUROPE/MADRID'=>'Europe/Madrid',
		'EUROPE/MALTA'=>'Europe/Malta',
		'EUROPE/MARIEHAMN'=>'Europe/Mariehamn',
		'EUROPE/MINSK'=>'Europe/Minsk',
		'EUROPE/MONACO'=>'Europe/Monaco',
		'EUROPE/MOSCOW'=>'Europe/Moscow',
		'EUROPE/OSLO'=>'Europe/Oslo',
		'EUROPE/PARIS'=>'Europe/Paris',
		'EUROPE/PODGORICA'=>'Europe/Podgorica',
		'EUROPE/PRAGUE'=>'Europe/Prague',
		'EUROPE/RIGA'=>'Europe/Riga',
		'EUROPE/ROME'=>'Europe/Rome',
		'EUROPE/SAMARA'=>'Europe/Samara',
		'EUROPE/SAN_MARINO'=>'Europe/San_Marino',
		'EUROPE/SARAJEVO'=>'Europe/Sarajevo',
		'EUROPE/SARATOV'=>'Europe/Saratov',
		'EUROPE/SIMFEROPOL'=>'Europe/Simferopol',
		'EUROPE/SKOPJE'=>'Europe/Skopje',
		'EUROPE/SOFIA'=>'Europe/Sofia',
		'EUROPE/STOCKHOLM'=>'Europe/Stockholm',
		'EUROPE/TALLINN'=>'Europe/Tallinn',
		'EUROPE/TIRANE'=>'Europe/Tirane',
		'EUROPE/ULYANOVSK'=>'Europe/Ulyanovsk',
		'EUROPE/UZHGOROD'=>'Europe/Uzhgorod',
		'EUROPE/VADUZ'=>'Europe/Vaduz',
		'EUROPE/VATICAN'=>'Europe/Vatican',
		'EUROPE/VIENNA'=>'Europe/Vienna',
		'EUROPE/VILNIUS'=>'Europe/Vilnius',
		'EUROPE/VOLGOGRAD'=>'Europe/Volgograd',
		'EUROPE/WARSAW'=>'Europe/Warsaw',
		'EUROPE/ZAGREB'=>'Europe/Zagreb',
		'EUROPE/ZAPOROZHYE'=>'Europe/Zaporozhye',
		'EUROPE/ZURICH'=>'Europe/Zurich',
		'INDIAN/ANTANANARIVO'=>'Indian/Antananarivo',
		'INDIAN/CHAGOS'=>'Indian/Chagos',
		'INDIAN/CHRISTMAS'=>'Indian/Christmas',
		'INDIAN/COCOS'=>'Indian/Cocos',
		'INDIAN/COMORO'=>'Indian/Comoro',
		'INDIAN/KERGUELEN'=>'Indian/Kerguelen',
		'INDIAN/MAHE'=>'Indian/Mahe',
		'INDIAN/MALDIVES'=>'Indian/Maldives',
		'INDIAN/MAURITIUS'=>'Indian/Mauritius',
		'INDIAN/MAYOTTE'=>'Indian/Mayotte',
		'INDIAN/REUNION'=>'Indian/Reunion',
		'PACIFIC/APIA'=>'Pacific/Apia',
		'PACIFIC/AUCKLAND'=>'Pacific/Auckland',
		'PACIFIC/BOUGAINVILLE'=>'Pacific/Bougainville',
		'PACIFIC/CHATHAM'=>'Pacific/Chatham',
		'PACIFIC/CHUUK'=>'Pacific/Chuuk',
		'PACIFIC/EASTER'=>'Pacific/Easter',
		'PACIFIC/EFATE'=>'Pacific/Efate',
		'PACIFIC/ENDERBURY'=>'Pacific/Enderbury',
		'PACIFIC/FAKAOFO'=>'Pacific/Fakaofo',
		'PACIFIC/FIJI'=>'Pacific/Fiji',
		'PACIFIC/FUNAFUTI'=>'Pacific/Funafuti',
		'PACIFIC/GALAPAGOS'=>'Pacific/Galapagos',
		'PACIFIC/GAMBIER'=>'Pacific/Gambier',
		'PACIFIC/GUADALCANAL'=>'Pacific/Guadalcanal',
		'PACIFIC/GUAM'=>'Pacific/Guam',
		'PACIFIC/HONOLULU'=>'Pacific/Honolulu',
		'PACIFIC/KIRITIMATI'=>'Pacific/Kiritimati',
		'PACIFIC/KOSRAE'=>'Pacific/Kosrae',
		'PACIFIC/KWAJALEIN'=>'Pacific/Kwajalein',
		'PACIFIC/MAJURO'=>'Pacific/Majuro',
		'PACIFIC/MARQUESAS'=>'Pacific/Marquesas',
		'PACIFIC/MIDWAY'=>'Pacific/Midway',
		'PACIFIC/NAURU'=>'Pacific/Nauru',
		'PACIFIC/NIUE'=>'Pacific/Niue',
		'PACIFIC/NORFOLK'=>'Pacific/Norfolk',
		'PACIFIC/NOUMEA'=>'Pacific/Noumea',
		'PACIFIC/PAGO_PAGO'=>'Pacific/Pago_Pago',
		'PACIFIC/PALAU'=>'Pacific/Palau',
		'PACIFIC/PITCAIRN'=>'Pacific/Pitcairn',
		'PACIFIC/POHNPEI'=>'Pacific/Pohnpei',
		'PACIFIC/PORT_MORESBY'=>'Pacific/Port_Moresby',
		'PACIFIC/RAROTONGA'=>'Pacific/Rarotonga',
		'PACIFIC/SAIPAN'=>'Pacific/Saipan',
		'PACIFIC/TAHITI'=>'Pacific/Tahiti',
		'PACIFIC/TARAWA'=>'Pacific/Tarawa',
		'PACIFIC/TONGATAPU'=>'Pacific/Tongatapu',
		'PACIFIC/WAKE'=>'Pacific/Wake',
		'PACIFIC/WALLIS'=>'Pacific/Wallis',
		'UTC'=>'UTC',
	);



	// -------------------------------------------------
	// Miscellaneous.

	// From e.g. parse_url.
	const URL_PARTS = array(
		'scheme'=>'',
		'host'=>'',
		'user'=>'',
		'pass'=>'',
		'port'=>'',
		'path'=>'',
		'query'=>'',
		'fragment'=>'',
	);

	const SVG_HEADER = '<?xml version="1.0" encoding="utf-8" ?>' . "\n" . '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">';

	const SVG_NAMESPACE = 'http://www.w3.org/2000/svg';

	// SVG attribute corrections.
	const SVG_ATTR_CORRECTIONS = array(
		'xmlns="&ns_svg;"'=>'xmlns="http://www.w3.org/2000/svg"',
		'xmlns:xlink="&ns_xlink;"'=>'xmlns:xlink="http://www.w3.org/1999/xlink"',
		'id="Layer_1"'=>'',
	);

	// Clean svg options.
	const SVG_CLEAN_OPTIONS = array(
		'clean_styles'=>false,			// Consistent formatting, group like rules.
		'fix_dimensions'=>false,		// Supply missing width, height, viewBox.
		'namespace'=>false,				// Add an svg: namespace.
		'random_id'=>false,				// Randomize IDs.
		'rewrite_styles'=>false,		// Redo classes for overlaps.
		'sanitize'=>true,				// Remove invalid/dangerous bits.
		'save'=>false,					// Overwrite the original file with a cleaned version.
		'strip_data'=>false,			// Remove data-X attributes.
		'strip_id'=>false,				// Remove all IDs.
		'strip_style'=>false,			// Remove all styles.
		'strip_title'=>false,			// Remove all titles.
		'whitelist_attr'=>array(),		// Additional attributes to allow.
		'whitelist_tags'=>array(),		// Additional tags to allow.
		'whitelist_protocols'=>array(), // Additional protocols to allow.
		'whitelist_domains'=>array(),	// Additional domains to allow.
	);

	// SVG whitelisted tags.
	/* @see {https://developer.mozilla.org/en-US/docs/Web/SVG/Element} */
	const SVG_WHITELIST_TAGS = array(
		'a',
		'altglyph',
		'altglyphdef',
		'altglyphitem',
		'animate',
		'animatecolor',
		'animatemotion',
		'animatetransform',
		'audio',
		'canvas',
		'circle',
		'clippath',
		'color-profile',
		'cursor',
		'defs',
		'desc',
		'discard',
		'ellipse',
		'feblend',
		'fecolormatrix',
		'fecomponenttransfer',
		'fecomposite',
		'feconvolvematrix',
		'fediffuselighting',
		'fedisplacementmap',
		'fedistantlight',
		'fedropshadow',
		'feflood',
		'fefunca',
		'fefuncb',
		'fefuncg',
		'fefuncr',
		'fegaussianblur',
		'feimage',
		'femerge',
		'femergenode',
		'femorphology',
		'feoffset',
		'fepointlight',
		'fespecularlighting',
		'fespotlight',
		'fetile',
		'feturbulence',
		'filter',
		'font',
		'font-face',
		'font-face-format',
		'font-face-name',
		'font-face-src',
		'font-face-uri',
		'g',
		'glyph',
		'glyphref',
		'hatch',
		'hatchpath',
		'hkern',
		'image',
		'line',
		'lineargradient',
		'marker',
		'mask',
		'mesh',
		'meshgradient',
		'meshpatch',
		'meshrow',
		'metadata',
		'missing-glyph',
		'mpath',
		'path',
		'pattern',
		'polygon',
		'polyline',
		'radialgradient',
		'rect',
		'set',
		'solidcolor',
		'stop',
		'style',
		'svg',
		'switch',
		'symbol',
		'text',
		'textpath',
		'title',
		'tref',
		'tspan',
		'unknown',
		'use',
		'video',
		'view',
		'vkern',
	);

	// SVG whitelisted attributes.
	/* @see {https://developer.mozilla.org/en-US/docs/Web/SVG/Attribute} */
	const SVG_WHITELIST_ATTR = array(
		'accent-height',
		'accumulate',
		'additive',
		'alignment-baseline',
		'allowreorder',
		'alphabetic',
		'amplitude',
		'arabic-form',
		'ascent',
		'attributename',
		'attributetype',
		'autoreverse',
		'azimuth',
		'basefrequency',
		'baseline-shift',
		'baseprofile',
		'bbox',
		'begin',
		'bias',
		'by',
		'calcmode',
		'cap-height',
		'class',
		'clip',
		'clippathunits',
		'clip-path',
		'clip-rule',
		'color',
		'color-interpolation',
		'color-interpolation-filters',
		'color-profile',
		'color-rendering',
		'contentstyletype',
		'cursor',
		'cx',
		'cy',
		'd',
		'decelerate',
		'descent',
		'diffuseconstant',
		'direction',
		'display',
		'divisor',
		'dominant-baseline',
		'dur',
		'dx',
		'dy',
		'edgemode',
		'elevation',
		'enable-background',
		'end',
		'exponent',
		'externalresourcesrequired',
		'fill',
		'fill-opacity',
		'fill-rule',
		'filter',
		'filterres',
		'filterunits',
		'flood-color',
		'flood-opacity',
		'font-family',
		'font-size',
		'font-size-adjust',
		'font-stretch',
		'font-style',
		'font-variant',
		'font-weight',
		'format',
		'from',
		'fx',
		'fy',
		'g1',
		'g2',
		'glyph-name',
		'glyph-orientation-horizontal',
		'glyph-orientation-vertical',
		'glyphref',
		'gradienttransform',
		'gradientunits',
		'hanging',
		'height',
		'href',
		'horiz-adv-x',
		'horiz-origin-x',
		'id',
		'ideographic',
		'image-rendering',
		'in',
		'in2',
		'intercept',
		'k',
		'k1',
		'k2',
		'k3',
		'k4',
		'kernelmatrix',
		'kernelunitlength',
		'kerning',
		'keypoints',
		'keysplines',
		'keytimes',
		'lang',
		'lengthadjust',
		'letter-spacing',
		'lighting-color',
		'limitingconeangle',
		'local',
		'marker-end',
		'marker-mid',
		'marker-start',
		'markerheight',
		'markerunits',
		'markerwidth',
		'mask',
		'maskcontentunits',
		'maskunits',
		'mathematical',
		'max',
		'media',
		'method',
		'min',
		'mode',
		'name',
		'numoctaves',
		'offset',
		'opacity',
		'operator',
		'order',
		'orient',
		'orientation',
		'origin',
		'overflow',
		'overline-position',
		'overline-thickness',
		'panose-1',
		'paint-order',
		'pathlength',
		'patterncontentunits',
		'patterntransform',
		'patternunits',
		'pointer-events',
		'points',
		'pointsatx',
		'pointsaty',
		'pointsatz',
		'preservealpha',
		'preserveaspectratio',
		'primitiveunits',
		'r',
		'radius',
		'refx',
		'refy',
		'rendering-intent',
		'repeatcount',
		'repeatdur',
		'requiredextensions',
		'requiredfeatures',
		'restart',
		'result',
		'rotate',
		'rx',
		'ry',
		'scale',
		'seed',
		'shape-rendering',
		'slope',
		'spacing',
		'specularconstant',
		'specularexponent',
		'speed',
		'spreadmethod',
		'startoffset',
		'stddeviation',
		'stemh',
		'stemv',
		'stitchtiles',
		'stop-color',
		'stop-opacity',
		'strikethrough-position',
		'strikethrough-thickness',
		'string',
		'stroke',
		'stroke-dasharray',
		'stroke-dashoffset',
		'stroke-linecap',
		'stroke-linejoin',
		'stroke-miterlimit',
		'stroke-opacity',
		'stroke-width',
		'style',
		'surfacescale',
		'systemlanguage',
		'tabindex',
		'tablevalues',
		'target',
		'targetx',
		'targety',
		'text-anchor',
		'text-decoration',
		'text-rendering',
		'textlength',
		'to',
		'transform',
		'type',
		'u1',
		'u2',
		'underline-position',
		'underline-thickness',
		'unicode',
		'unicode-bidi',
		'unicode-range',
		'units-per-em',
		'v-alphabetic',
		'v-hanging',
		'v-ideographic',
		'v-mathematical',
		'values',
		'version',
		'vert-adv-y',
		'vert-origin-x',
		'vert-origin-y',
		'viewbox',
		'viewtarget',
		'visibility',
		'width',
		'widths',
		'word-spacing',
		'writing-mode',
		'x',
		'x-height',
		'x1',
		'x2',
		'xchannelselector',
		'xlink:actuate',
		'xlink:arcrole',
		'xlink:href',
		'xlink:role',
		'xlink:show',
		'xlink:title',
		'xlink:type',
		'xml:base',
		'xml:lang',
		'xml:space',
		'xmlns',
		'xmlns:xlink',
		'xmlns:xml',
		'y',
		'y1',
		'y2',
		'ychannelselector',
		'z',
		'zoomandpan',
	);

	const SVG_WHITELIST_PROTOCOLS = array(
		'http',
		'https',
	);

	const SVG_WHITELIST_DOMAINS = array(
		'creativecommons.org',
		'inkscape.org',
		'sodipodi.sourceforge.net',
		'w3.org',
	);

	// SVG IRI attributes.
	/* @see {https://www.w3.org/TR/SVG/linking.html} */
	const SVG_IRI_ATTRIBUTES = array(
		'href',
		'src',
		'xlink:arcrole',
		'xlink:href',
		'xlink:role',
		'xml:base',
		'xmlns',
		'xmlns:xlink',
	);

	// Blank image.
	const BLANK_IMAGE = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACwAAAAAAQABAAACAkQBADs=';

	// Default binary paths for WebP.
	const CWEBP = '/usr/bin/cwebp';
	const GIF2WEBP = '/usr/bin/gif2webp';

	// Excerpt arguments.
	const EXCERPT = array(
		'length'=>200,
		'suffix'=>'…',
		'unit'=>'character',
	);

	// Truthy bools.
	const TRUE_BOOLS = array(
		'1',
		'on',
		'true',
		'yes',
	);

	// Falsey bools.
	const FALSE_BOOLS = array(
		'0',
		'off',
		'false',
		'no',
	);

	// Flat CSS rule.
	const CSS_FLAT = array(
		'@'=>false,
		'nested'=>false,
		'selectors'=>array(),
		'rules'=>array(),
		'raw'=>'',
	);

	// Nested CSS rule.
	const CSS_NESTED = array(
		'@'=>false,
		'nested'=>true,
		'selector'=>'',
		'nest'=>array(),
		'raw'=>'',
	);

	// Link Blacklist (i.e. tags whose content shouldn't be linkified).
	const LINKS_BLACKLIST = array(
		'a',
		'audio',
		'button',
		'code',
		'embed',
		'frame',
		'head',
		'link',
		'object',
		'picture',
		'pre',
		'script',
		'select',
		'style',
		'svg',
		'textarea',
		'video',
	);

	// Map variable types to the appropriate cast function.
	const CAST_TYPES = array(
		'array'=>'to_array',
		'bool'=>'to_bool',
		'boolean'=>'to_bool',
		'double'=>'to_float',
		'float'=>'to_float',
		'int'=>'to_int',
		'integer'=>'to_int',
		'number'=>'to_number',
		'string'=>'to_string',
	);

	// Bitwise Operators.
	const BITWISE_OPERATORS = array(
		'&'=>'AND',
		'<<'=>'LEFT',
		'>>'=>'RIGHT',
		'^'=>'XOR',
		'|'=>'OR',
		'~'=>'NOT',
	);

	// Options for list-to-array.
	const LIST_TO_ARRAY = array(
		'cast'=>'string',
		'delimiter'=>',',
		'max'=>null,
		'min'=>null,
		'sort'=>false,
		'trim'=>true,
		'unique'=>true,
	);
}


