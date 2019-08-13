<?php
/**
 * Image Helpers
 *
 * Functions for managing images.
 *
 * @package blobfolio/common
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\common;

// PHP introduced WebP capabilities in batches; oddly this constant was
// a late arrival.
if (! \defined('IMAGETYPE_WEBP')) {
	\define('IMAGETYPE_WEBP', 18);
}

if (! \defined('IMG_WEBP')) {
	\define('IMG_WEBP', 32);
}

class image {

	protected static $_webp_gd;
	protected static $_webp_binary;
	protected static $svg_ids = array();
	protected static $svg_classes = array();

	/**
	 * Clean SVG for Inline Embedding
	 *
	 * @param string $path SVG path.
	 * @param mixed $args Arguments.
	 * @param string $output Output format.
	 *
	 * @arg bool $clean_styles Fix <style> formatting, combine tags.
	 * @arg bool $fix_dimensions Fix missing width, height, viewBox.
	 * @arg bool $namespace Generate an xmlns:svg namespace.
	 * @arg bool $random_id Randomize IDs.
	 * @arg bool $rewrite_styles Redo class assignments to group like rules.
	 * @arg bool $sanitize Sanitize content.
	 * @arg bool $save Save cleaned file for faster repeat processing.
	 * @arg bool $strip_data Remove data-x attributes.
	 * @arg bool $strip_id Remove IDs.
	 * @arg bool $strip_style Remove styles.
	 * @arg bool $strip_title Remove titles.
	 * @arg array $whitelist_attr Additional attributes to allow.
	 * @arg array $whitelist_tags Additional tags to allow.
	 * @arg array $whitelist_protocols Additional protocols to allow.
	 * @arg array $whitelist_domains Additional domains to allow.
	 *
	 * @return string|bool Clean SVG code. False on failure.
	 */
	public static function clean_svg(string $path, $args=null, string $output='HTML') {
		try {
			if (! @\is_file($path)) {
				return false;
			}

			$output = \strtoupper($output);

			$svg = @\file_get_contents($path);

			// Options.
			ref\cast::array($args);

			// The strip_js option is a deprecated alias of sanitize.
			if (isset($args['strip_js']) && ! isset($args['sanitize'])) {
				$args['sanitize'] = $args['strip_js'];
			}

			$options = data::parse_args($args, constants::SVG_CLEAN_OPTIONS);
			// Some options imply or override others.
			if ($options['strip_style']) {
				$options['clean_styles'] = false;
				$options['namespace'] = false;
				$options['rewrite_styles'] = false;
				$options['random_class'] = false;
			}
			if ($options['strip_id']) {
				$options['random_id'] = false;
			}
			if ($options['rewrite_styles']) {
				$options['clean_styles'] = true;
			}

			// If this SVG is marked "passthrough", don't process it.
			$passthrough_key = \hash('crc32', \json_encode($options));
			if (false !== \strpos($svg, 'data-cleaned="' . $passthrough_key . '"')) {
				$svg = \substr($svg, \strpos($svg, '<svg'));
				if ('DATA_URI' === $output) {
					return 'data:image/svg+xml;base64,' . \base64_encode($svg);
				}
				elseif ('HTML' === $output) {
					return $svg;
				}
				else {
					return false;
				}
			}

			// Do a quick pass through DomDoc to standardize formatting.
			$dom = dom::load_svg($svg);
			$svg = dom::save_svg($dom);
			if (! $svg) {
				return false;
			}

			// Make sure SVGs have the current standard.
			$dom = dom::load_svg($svg);
			$tmp = $dom->getElementsByTagName('svg');
			foreach ($tmp as $t) {
				$t->setAttribute('version', '1.1');
			}
			$svg = dom::save_svg($dom);

			// Let's get some early stripping done.
			if ($options['strip_data']) {
				$svg = \preg_replace('/(\sdata\-[a-z\d_\-]+\s*=\s*"[^"]*")/i', '', $svg);
			}
			if ($options['strip_id']) {
				$svg = \preg_replace('/(\sid\s*=\s*"[^"]*")/i', '', $svg);
			}
			if ($options['strip_style']) {
				$svg = \preg_replace('/(\s(style|class)\s*=\s*"[^"]*")/i', '', $svg);
			}

			// Let's do the dom tasks in one swoop.
			if ($options['strip_title'] || $options['strip_style']) {
				$dom = dom::load_svg($svg);

				if ($options['strip_style']) {
					dom::remove_nodes($dom->getElementsByTagName('style'));
				}
				if ($options['strip_title']) {
					dom::remove_nodes($dom->getElementsByTagName('title'));
				}

				$svg = dom::save_svg($dom);
			}

			if ($options['sanitize']) {
				ref\sanitize::svg(
					$svg,
					$options['whitelist_tags'],
					$options['whitelist_attr'],
					$options['whitelist_protocols'],
					$options['whitelist_domains']
				);
			}

			// Randomize IDs?
			if ($options['random_id']) {
				\preg_match_all('/\sid\s*=\s*"([^"]*)"/i', $svg, $matches);
				if (\count($matches[0])) {
					foreach ($matches[0] as $k=>$v) {
						$id_string = $v;
						$id_value = $matches[1][$k];
						$id_new = 's' . mb::strtolower(data::random_string(4), false);
						while (\in_array($id_new, static::$svg_ids, true)) {
							$id_new = 's' . mb::strtolower(data::random_string(4), false);
						}
						static::$svg_ids[] = $id_new;

						// Replace just the first occurrence.
						$svg = \preg_replace('/' . \preg_quote($id_string, '/') . '/', ' id="' . $id_new . '"', $svg, 1);
					}
				}
			}

			// Fix dimensions?
			if ($options['fix_dimensions']) {
				// Before we dive in, fix existing viewbox values.
				\preg_match_all('/\sviewbox\s*=\s*"([^"]*)"/i', $svg, $matches);
				if (\count($matches[0])) {
					foreach ($matches[0] as $k=>$v) {
						$vb_string = $v;
						$vb_value = $matches[1][$k];
						$vb_new = '';
						if (false !== \strpos($vb_value, ',')) {
							$vb_new = \explode(',', $vb_value);
						}
						else {
							$vb_new = \explode(' ', $vb_value);
						}
						$vb_new = \array_map('trim', $vb_new);
						$vb_new = \array_filter($vb_new, 'is_numeric');

						// Remove invalid entries entirely.
						if (\count($vb_new) !== 4) {
							$svg = \preg_replace('/' . \preg_quote($vb_string, '/') . '/', '', $svg, 1);
						}
						else {
							$vb_new = \implode(' ', $vb_new);
							// Update it.
							if ($vb_new !== $vb_value) {
								$svg = \preg_replace('/' . \preg_quote($vb_string, '/') . '/', ' viewBox="' . $vb_new . '"', $svg, 1);
							}
						}
					}
				}

				// Tags supporting viewBox, width, and height.
				$dom = dom::load_svg($svg);
				foreach (array('svg', 'pattern') as $tag) {
					$tmp = $dom->getElementsByTagName($tag);
					if ($tmp->length) {
						foreach ($tmp as $t) {
							$width = $t->hasAttribute('width') ? $t->getAttribute('width') : null;
							$height = $t->hasAttribute('height') ? $t->getAttribute('height') : null;
							$vb = $t->hasAttribute('viewBox') ? $t->getAttribute('viewBox') : null;

							// Make sure width and height are numbers.
							if (\is_numeric($width) || \preg_match('/^[\d\.]+px$/', $width)) {
								ref\cast::float($width, true);

								if ($width <= 0) {
									$width = null;
								}
							}
							else {
								$width = null;
							}

							if (\is_numeric($height) || \preg_match('/^[\d\.]+px$/', $height)) {
								ref\cast::float($height, true);
								if ($height <= 0) {
									$height = null;
								}
							}
							else {
								$height = null;
							}

							// All there or none there? Can't help it.
							if (
								((null !== $width) && (null !== $height) && (null !== $vb)) ||
								((null === $width) && (null === $height) && (null === $vb))
							) {
								continue;
							}

							// Width and height from viewbox.
							if (null !== $vb) {
								$d = \explode(' ', $vb);
								$t->setAttribute('width', $d[2]);
								$t->setAttribute('height', $d[3]);
							}
							// Viewbox from width and height.
							elseif (\is_numeric($width) && \is_numeric($height)) {
								$t->setAttribute('viewBox', "0 0 $width $height");
							}
						}
					}
				}
				$svg = dom::save_svg($dom);
			}

			// Now styles.
			if ($options['clean_styles'] || $options['namespace']) {
				$dom = dom::load_svg($svg);
				$svgs = $dom->getElementsByTagName('svg');
				if ($svgs->length) {
					foreach ($svgs as $s) {
						// Add namespace.
						if ($options['namespace']) {
							$s->setAttribute('xmlns:svg', 'http://www.w3.org/2000/svg');
						}

						// Store a list of classes to rewrite, if any.
						$classes_old = array();

						// Cleaning styles?
						if ($options['clean_styles']) {

							// First, combine them.
							$tmp = $s->getElementsByTagName('style');
							if ($tmp->length) {
								$parent = $tmp->item(0)->parentNode;
								$style = $dom->createElement('style');
								while ($tmp->length) {
									foreach ($tmp->item(0)->childNodes as $t) {
										$t->nodeValue .= ' ';
										$style->appendChild($t);
									}
									dom::remove_node($tmp->item(0));
								}
								$parent->appendChild($style);

								// Now fix formatting.
								$style = $s->getElementsByTagName('style')->item(0);

								// Parse the styles.
								$parsed = dom::parse_css($style->nodeValue);
								if (\is_array($parsed) && \count($parsed)) {
									$style_new = array();

									if ($options['rewrite_styles']) {
										// Let's try to join identical rules.
										$rules = array();

										foreach ($parsed as $p) {
											// If it is an @ rule, just throw it in wholesale.
											if (false !== $p['@']) {
												$rules[$p['raw']] = array();
											}
											else {
												foreach ($p['rules'] as $rk=>$rv) {
													$r = "$rk:$rv";

													if (! isset($rules[$r])) {
														$rules[$r] = array();
													}

													$rules[$r] = \array_merge($rules[$r], \array_values($p['selectors']));
												}
											}
										}

										// Clean up the rules a touch.
										foreach ($rules as $rule=>$selectors) {
											$rules[$rule] = \array_unique($rules[$rule]);
											\sort($rules[$rule]);
										}

										// Great, now build the output.
										foreach ($rules as $rule=>$selectors) {
											// Something like an @media, out of scope here.
											if (! \count($selectors)) {
												$style_new[] = $rule;
												continue;
											}

											// Look for class selectors, ignoring complex chains.
											$classes = array();
											foreach ($selectors as $k=>$selector) {
												// A valid class.
												if (\preg_match('/^\.[a-z\d_\-]+$/i', $selector)) {
													$classes[] = $selector;
													unset($selectors[$k]);
												}
												// A broken Adobe class,
												// e.g. .\38 ab9678e-54ee-493d-b19f-2215c5549034.
												else {
													$selector = \str_replace('.\\3', '.', $selector);
													// Fix weird adobe rules.
													\preg_match_all('/^\.([\d]) ([a-z\d\-]+)$/', $selector, $matches);
													if (\count($matches[0])) {
														$classes[] = \preg_replace('/\s/', '', $matches[0][0]);
														unset($selectors[$k]);
													}
												}
											}

											if (\count($classes)) {
												$class_new = mb::strtolower('c' . data::random_string(4), false);
												while (\in_array($class_new, static::$svg_classes, true)) {
													$class_new = mb::strtolower('c' . data::random_string(4), false);
												}
												$selectors[] = '.' . $class_new;

												// Add this class to all affected nodes.
												$nodes = dom::get_nodes_by_class($s, $classes);
												foreach ($nodes as $node) {
													$class = $node->getAttribute('class');
													$class .= " $class_new";
													$node->setAttribute('class', $class);
												}

												foreach ($classes as $class) {
													$classes_old[] = \ltrim($class, '.');
												}
											}

											$style_new[] = \implode(',', $selectors) . '{' . $rule . '}';
										}
									}// End cleanup/rewrite.
									else {
										// Just add the rules.
										foreach ($parsed as $p) {
											$style_new[] = $p['raw'];
										}
									}

									// And save it!
									$style->nodeValue = \implode('', $style_new);
								}// Parseable styles.
							}// If styles.
						}// Clean styles.

						// Add namespaced style.
						if ($options['namespace']) {
							$styles = $s->getElementsByTagName('style');
							if ($styles->length) {
								foreach ($styles as $style) {
									$parent = $style->parentNode;
									$svgstyle = $dom->createElement('svg:style');
									if ($style->childNodes->length) {
										foreach ($style->childNodes as $t) {
											$clone = $t->cloneNode(true);
											$svgstyle->appendChild($clone);
										}
									}
									$parent->appendChild($svgstyle);
								}
							}
						}

						// Remove old classes, if applicable.
						if (\count($classes_old)) {
							\sort($classes_old);
							$classes_old = \array_unique($classes_old);

							$nodes = dom::get_nodes_by_class($s, $classes_old);
							foreach ($nodes as $node) {
								$classes = $node->getAttribute('class');
								ref\sanitize::whitespace($classes, 0);
								$classes = \explode(' ', $classes);
								$classes = \array_unique($classes);
								$classes = \array_diff($classes, $classes_old);
								$node->setAttribute('class', \implode(' ', $classes));
							}
						}// Rewriting.
					}// Each SVG.
				}// If SVGs.
				$svg = dom::save_svg($dom);
			}

			// Get back to just the string.
			$dom = dom::load_svg($svg);
			$svg = dom::save_svg($dom);
			if (! $svg) {
				return false;
			}

			// Should we save the clean version?
			if ($options['save']) {
				// Add our passthrough header.
				$dom = dom::load_svg($svg);
				$tmp = $dom->getElementsByTagName('svg');
				$tmp->item(0)->setAttribute('data-cleaned', $passthrough_key);
				$svg = dom::save_svg($dom);

				$path_old = $path . '.dirty.' . \microtime(true);
				$num = 0;
				while (@\file_exists($path_old)) {
					++$num;
					$tmp = $path_old . "-$num";
					if (! @\file_exists($tmp)) {
						$path_old = $tmp;
					}
				}
				@\rename($path, $path_old);
				@\file_put_contents($path, constants::SVG_HEADER . "\n{$svg}");
			}

			if ('DATA_URI' === $output) {
				return 'data:image/svg+xml;base64,' . \base64_encode($svg);
			}
			elseif ('HTML' === $output) {
				return $svg;
			}

			return false;
		} catch (\Throwable $e) {
			return false;
		}
	}

	/**
	 * Image Dimensions
	 *
	 * The native PHP getimagesize() function is kind of shit. This will
	 * help parse out SVG and WebP dimensions too.
	 *
	 * @param string $file File.
	 * @return array|bool Info or false.
	 */
	public static function getimagesize(string $file) {
		if (! $file || ! \is_file($file)) {
			return false;
		}

		// Do a quick MIME check to make sure this is something image-
		// like.
		$finfo = mime::finfo($file);
		$mime = $finfo['mime'];

		if (0 !== \strpos($mime, 'image/')) {
			return false;
		}

		// If this is an SVG, let's use our own function.
		if ('image/svg+xml' === $mime) {
			if (false === ($tmp = static::svg_dimensions($file))) {
				return false;
			}

			// Fake it till you make it.
			return array(
				$tmp['width'],
				$tmp['height'],
				-1,
				\sprintf(
					'width="%d" height="%d"',
					$tmp['width'],
					$tmp['height']
				),
				'mime'=>'image/svg+xml',
			);
		}

		// Try getimagesize() first, just in case.
		if (false !== ($info = @\getimagesize($file))) {
			return $info;
		}

		// Manually parse WebP.
		if (
			('image/webp' === $mime) &&
			($handle = @\fopen($file, 'rb'))
		) {
			// The magic (and dimensions) are in the first 40 bytes.
			$magic = @\fread($handle, 40);
			\fclose($handle);

			// We should have the number of bytes we asked for.
			if (\strlen($magic) < 40) {
				return false;
			}

			$width = $height = false;

			// There are three types of WebP. Haha.
			switch (\substr($magic, 12, 4)) {
				// Lossy WebP.
				case 'VP8 ':
					$parts = \unpack('v2', \substr($magic, 26, 4));
					$width = (int) ($parts[1] & 0x3FFF);
					$height = (int) ($parts[2] & 0x3FFF);
					break;
				// Lossless WebP.
				case 'VP8L':
					$parts = \unpack('C4', \substr($magic, 21, 4));
					$width = (int) ($parts[1] | (($parts[2] & 0x3F) << 8)) + 1;
					$height = (int) ((($parts[2] & 0xC0) >> 6) | ($parts[3] << 2) | (($parts[4] & 0x03) << 10)) + 1;
					break;
				// Animated/Alpha WebP.
				case 'VP8X':
					// Padd 24-bit int.
					$width = \unpack('V', \substr($magic, 24, 3) . "\x00");
					$width = (int) ($width[1] & 0xFFFFFF) + 1;

					// Pad 24-bit int.
					$height = \unpack('V', \substr($magic, 27, 3) . "\x00");
					$height = (int) ($height[1] & 0xFFFFFF) + 1;
					break;
			}

			if ($width && $height) {
				return array(
					$width,
					$height,
					\IMAGETYPE_WEBP,
					\sprintf(
						'width="%d" height="%d"',
						$width,
						$height
					),
					'mime'=>'image/webp',
				);
			}
		}

		// No dice.
		return false;
	}

	/**
	 * Check Probable WebP Support
	 *
	 * This attempts to check whether PHP natively supports WebP, or
	 * if maybe third-party binaries are installed on the system.
	 *
	 * @param string $cwebp Path to cwebp.
	 * @param string $gif2webp Path to gif2webp.
	 * @return bool True/false.
	 */
	public static function has_webp($cwebp=null, $gif2webp=null) {
		// Gotta set it first?
		if (null === static::$_webp_gd) {
			$image_types = \imagetypes();
			static::$_webp_gd = (
				(0 !== ($image_types & \IMG_WEBP)) &&
				\function_exists('imagewebp') &&
				\function_exists('imagecreatefromwebp')
			);
		}

		// See if this system supports the binary method. In general
		// we'll just check this once, but if a previous check failed
		// and binary paths are supplied, we'll check again.
		if (
			(null === static::$_webp_binary) ||
			(
				(false === static::$_webp_binary) &&
				$cwebp &&
				$gif2webp
			)
		) {
			static::$_webp_binary = false;

			// We're using proc_open() to handle execution; if this is
			// missing or disabled, we're done.
			if (\function_exists('proc_open') && \is_callable('proc_open')) {
				// Resolve the binary paths.
				if (null === $cwebp) {
					$cwebp = constants::CWEBP;
				}
				else {
					$cwebp = (string) $cwebp;
				}
				if (null === $gif2webp) {
					$gif2webp = constants::GIF2WEBP;
				}
				else {
					$gif2webp = (string) $gif2webp;
				}

				ref\file::path($cwebp, true);
				ref\file::path($gif2webp, true);

				if (
					$cwebp &&
					$gif2webp &&
					@\is_file($cwebp) &&
					@\is_executable($cwebp) &&
					@\is_file($gif2webp) &&
					@\is_executable($gif2webp)
				) {
					static::$_webp_binary = array(
						'cwebp'=>$cwebp,
						'gif2webp'=>$gif2webp,
					);
				}
			}
		}

		return (static::$_webp_gd || (false !== static::$_webp_binary));
	}

	/**
	 * Determine SVG Dimensions
	 *
	 * @param string $svg SVG content or file path.
	 * @return array|bool Dimensions or false.
	 */
	public static function svg_dimensions($svg) {
		ref\cast::string($svg, true);

		// Make sure this is SVG-looking.
		if (false === ($start = \strpos(\strtolower($svg), '<svg'))) {
			if (@\is_file($svg)) {
				$svg = \file_get_contents($svg);
				if (false === ($start = \strpos(\strtolower($svg), '<svg'))) {
					return false;
				}
			}
			else {
				return false;
			}
		}

		// Chop the code to the first tag.
		$svg = \substr($svg, $start);
		if (false === ($end = \strpos($svg, '>'))) {
			return false;
		}
		$svg = \strtolower(\substr($svg, 0, $end + 1));

		// Hold our values.
		$out = array(
			'width'=>null,
			'height'=>null,
		);
		$viewbox = null;

		// Search for width, height, and viewbox.
		ref\sanitize::whitespace($svg, 0);

		\preg_match_all(
			'/(height|width|viewbox)\s*=\s*(["\'])((?:(?!\2).)*)\2/',
			$svg,
			$match,
			\PREG_SET_ORDER
		);

		if (\is_array($match) && \count($match)) {
			foreach ($match as $v) {
				switch ($v[1]) {
					case 'width':
					case 'height':
						ref\cast::float($v[3], true);
						ref\sanitize::to_range($v[3], 0.0);
						if ($v[3]) {
							$out[$v[1]] = $v[3];
						}
						break;
					case 'viewbox':
						// Defer processing for later.
						$viewbox = $v[3];
						break;
				}
			}
		}

		// If we have a width and height, we're done!
		if ($out['width'] && $out['height']) {
			return $out;
		}

		// Maybe pull from viewbox?
		if (isset($viewbox)) {
			$viewbox = \trim(\str_replace(',', ' ', $viewbox));
			$viewbox = \explode(' ', $viewbox);
			foreach ($viewbox as $k=>$v) {
				ref\cast::float($viewbox[$k], true);
				ref\sanitize::to_range($viewbox[$k], 0.0);
			}
			if (\count($viewbox) === 4) {
				$out['width'] = $viewbox[2];
				$out['height'] = $viewbox[3];
				return $out;
			}
		}

		return false;
	}

	/**
	 * Validate WebP From/To
	 *
	 * Our three WebP functions need to independently handle input and
	 * output source files. This private method helps prevent
	 * unnecessary code duplication.
	 *
	 * @param string $from From.
	 * @param mixed $to To.
	 * @return bool True/false.
	 */
	protected static function to_webp_sources(string &$from, &$to) {
		// Validate the source.
		ref\file::path($from, true);
		if (! $from) {
			$from = '';
			return false;
		}

		// We can only convert JPEG, PNG, and GIF sources.
		$info = mime::finfo($from);
		if (! \in_array($info['mime'], array('image/jpeg', 'image/gif', 'image/png'), true)) {
			$from = '';
			return false;
		}

		// Build a destination if we need to.
		if (null !== $to) {
			ref\cast::string($to, true);

			// If this is just a file name, throw it in from's dir.
			if (false === \strpos($to, '/')) {
				$to = "{$info['dirname']}/$to";
			}
			ref\file::path($to, false);
			if ('.webp' !== \substr(\strtolower($to), -5)) {
				$to = '';
				return false;
			}
		}
		// Just swap extensions with the source.
		else {
			$to = "{$info['dirname']}/{$info['filename']}.webp";
		}

		return true;
	}

	/**
	 * Generate WebP From Source
	 *
	 * This is a wrapper for the more specific GD and Binary methods
	 * to generate a WebP sister file.
	 *
	 * PHP isn't known for its performance, so the binaries are
	 * preferred when available.
	 *
	 * @param string $from Source file.
	 * @param string $to Output file.
	 * @param string $cwebp Path to cwebp.
	 * @param string $gif2webp Path to gif2webp.
	 * @param bool $refresh Recreate it.
	 * @return bool True/false.
	 */
	public static function to_webp(string $from, $to=null, $cwebp=null, $gif2webp=null, bool $refresh=false) {
		// Try binaries first, fallback to GD.
		return (
			static::to_webp_binary($from, $to, $cwebp, $gif2webp, $refresh) ||
			static::to_webp_gd($from, $to, $refresh)
		);
	}

	/**
	 * Generate WebP (GD)
	 *
	 * Use GD to generate a WebP sister file.
	 *
	 * @param string $from Source.
	 * @param string $to Out.
	 * @param bool $refresh Refresh.
	 * @return bool True/false.
	 */
	public static function to_webp_gd(string $from, $to=null, bool $refresh=false) {
		if (! static::to_webp_sources($from, $to)) {
			return false;
		}

		// If it exists and we aren't refreshing, let's abort.
		if (! $refresh && @\is_file($to)) {
			return true;
		}

		// If this system can't do WebP, we're done.
		if (! static::has_webp() || ! static::$_webp_gd) {
			return false;
		}

		$image = @\imagecreatefromstring(\file_get_contents($from));
		if (! $image || ! @\is_resource($image)) {
			return false;
		}

		// Try to save it.
		@\imagewebp($image, $to, 90);
		if (! @\is_file($to)) {
			return false;
		}

		// Free up some memory.
		@\imagedestroy($image);

		// Try to give it the same permissions as the original.
		if (false !== ($from_chmod = @\fileperms($from))) {
			@\chmod($to, $from_chmod);
		}
		if (false !== ($from_owner = @\fileowner($from))) {
			@\chown($to, $from_owner);
		}
		if (false !== ($from_group = @\filegroup($from))) {
			@\chgrp($to, $from_group);
		}

		return true;
	}

	/**
	 * Generate WebP (Binary)
	 *
	 * Use system binaries to generate WebP sister file.
	 *
	 * @param string $from Source.
	 * @param string $to Out.
	 * @param string $cwebp Path to cwebp.
	 * @param string $gif2webp Path to gif2webp.
	 * @param bool $refresh Recreate it.
	 * @return bool True/false.
	 */
	public static function to_webp_binary(string $from, $to=null, $cwebp=null, $gif2webp=null, bool $refresh=false) {
		if (! static::to_webp_sources($from, $to)) {
			return false;
		}

		// If it exists and we aren't refreshing, let's abort.
		if (! $refresh && @\is_file($to)) {
			return true;
		}

		// If this system can't do WebP, we're done.
		if (! static::has_webp($cwebp, $gif2webp) || (false === static::$_webp_binary)) {
			return false;
		}

		// We'll need a temporary directory for logging.
		$tmp_dir = @\sys_get_temp_dir();
		if (! \is_dir($tmp_dir)) {
			return false;
		}
		$error_log = file::trailingslash($tmp_dir) . 'cwebp-error_' . \microtime(true) . '.txt';

		// Pull the MIME info again.
		$info = mime::finfo($from);

		// Set up the command.
		if ('image/gif' === $info['mime']) {
			$cmd = \escapeshellcmd(static::$_webp_binary['gif2webp']) . ' -m 6 -quiet ' . \escapeshellarg($from) . ' -o ' . \escapeshellarg($to);
		}
		else {
			$cmd = \escapeshellcmd(static::$_webp_binary['cwebp']) . ' -mt -quiet -jpeg_like ' . \escapeshellarg($from) . ' -o ' . \escapeshellarg($to);
		}

		// Some process setup.
		$descriptors = array(
			0=>array('pipe', 'w'),				// STDOUT.
			1=>array('file', $error_log, 'a'),	// STDERR.
		);
		$cwd = $tmp_dir;
		$pipes = array();

		try {
			// Try to open the process.
			$process = @\proc_open(
				$cmd,
				$descriptors,
				$pipes,
				$cwd
			);

			// If we didn't end up with a resource, something is wrong.
			if (! @\is_resource($process)) {
				return false;
			}

			// Pull the stream contents.
			$life = @\stream_get_contents($pipes[0]);
			@\fclose($pipes[0]);
			$return_value = @\proc_close($process);

			// We don't actually want the error log; it is just supplied
			// to prevent interruptions to PHP.
			if (@\file_exists($error_log)) {
				@\unlink($error_log);
			}

			// If this isn't a file, we're done.
			if (! @\file_exists($to)) {
				return false;
			}

			// Try to give it the same permissions as the original.
			if (false !== ($from_chmod = @\fileperms($from))) {
				@\chmod($to, $from_chmod);
			}
			if (false !== ($from_owner = @\fileowner($from))) {
				@\chown($to, $from_owner);
			}
			if (false !== ($from_group = @\filegroup($from))) {
				@\chgrp($to, $from_group);
			}

			return true;
		} catch (\Throwable $e) {
			return false;
		}
	}
}
