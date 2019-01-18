<?php
/**
 * DOM Manipulation.
 *
 * Functions for manipulating the DOM.
 *
 * @package blobfolio/common
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\common;

class dom {

	/**
	 * Load SVG
	 *
	 * This creates a DOMDocument object from an SVG source.
	 *
	 * @param string $svg SVG code.
	 * @return bool|DOMDocument DOM object or false.
	 */
	public static function load_svg($svg='') {
		ref\cast::string($svg, true);

		// First thing first, lowercase all tags.
		$svg = preg_replace('/<svg/ui', '<svg', $svg);
		$svg = preg_replace('/<\/svg>/ui', '</svg>', $svg);

		// Find the start and end tags so we can cut out miscellaneous garbage.
		if (
			(false === ($start = mb::strpos($svg, '<svg'))) ||
			(false === ($end = mb::strrpos($svg, '</svg>')))
		) {
			return false;
		}
		$svg = mb::substr($svg, $start, ($end - $start + 6), true);

		// Bugs from old versions of Illustrator.
		$svg = str_replace(
			array_keys(constants::SVG_ATTR_CORRECTIONS),
			array_values(constants::SVG_ATTR_CORRECTIONS),
			$svg
		);

		// Remove XML, PHP, ASP, etc.
		$svg = preg_replace('/<\?(.*)\?>/Us', '', $svg);
		$svg = preg_replace('/<\%(.*)\%>/Us', '', $svg);

		if ((false !== strpos($svg, '<?')) || (false !== strpos($svg, '<%'))) {
			return false;
		}

		// Remove comments.
		$svg = preg_replace('/<!--(.*)-->/Us', '', $svg);
		$svg = preg_replace('/\/\*(.*)\*\//Us', '', $svg);

		if ((false !== strpos($svg, '<!--')) || (false !== strpos($svg, '/*'))) {
			return false;
		}

		// Open it.
		libxml_use_internal_errors(true);
		libxml_disable_entity_loader(true);
		$dom = new \DOMDocument('1.0', 'UTF-8');
		$dom->formatOutput = false;
		$dom->preserveWhiteSpace = false;
		$dom->loadXML(constants::SVG_HEADER . "\n{$svg}");

		// Make sure there are still SVG tags.
		$svgs = $dom->getElementsByTagName('svg');
		if (!$svgs->length) {
			return false;
		}

		return $dom;
	}

	/**
	 * Save SVG
	 *
	 * Convert a DOMDocument object containing an SVG back into a
	 * string.
	 *
	 * @param \DOMDocument $dom DOM object.
	 * @return string SVG.
	 */
	public static function save_svg(\DOMDocument $dom) {
		$svgs = $dom->getElementsByTagName('svg');
		if (!$svgs->length) {
			return '';
		}
		$svg = $svgs->item(0)->ownerDocument->saveXML(
			$svgs->item(0),
			LIBXML_NOBLANKS
		);

		// Make sure if xmlns="" exists, it is correct. Can't alter
		// that with DOMDocument, and there is only one proper value.
		$svg = preg_replace(
			'/xmlns\s*=\s*"[^"]*"/',
			'xmlns="' . constants::SVG_NAMESPACE . '"',
			$svg
		);

		// Remove XML, PHP, ASP, etc.
		$svg = preg_replace('/<\?(.*)\?>/Us', '', $svg);
		$svg = preg_replace('/<\%(.*)\%>/Us', '', $svg);

		if ((false !== strpos($svg, '<?')) || (false !== strpos($svg, '<%'))) {
			return '';
		}

		// Remove comments.
		$svg = preg_replace('/<!--(.*)-->/Us', '', $svg);
		$svg = preg_replace('/\/\*(.*)\*\//Us', '', $svg);

		if ((false !== strpos($svg, '<!--')) || (false !== strpos($svg, '/*'))) {
			return '';
		}

		// Find the start and end tags so we can cut out miscellaneous garbage.
		if (
			(false === ($start = mb::strpos($svg, '<svg'))) ||
			(false === ($end = mb::strrpos($svg, '</svg>')))
		) {
			return false;
		}
		$svg = mb::substr($svg, $start, ($end - $start + 6));

		return $svg;
	}

	/**
	 * Get Nodes By Class
	 *
	 * This will return an array of DOMNode objects containing the
	 * specified class(es). This does not use DOMXPath.
	 *
	 * @param mixed $parent DOMDocument, DOMElement, etc.
	 * @param string|array $class One or more classes.
	 * @param bool $all Matches must contain *all* passed classes instead of *any*.
	 * @return array Nodes.
	 */
	public static function get_nodes_by_class($parent, $class=null, bool $all=false) {
		$nodes = array();

		if (!method_exists($parent, 'getElementsByTagName')) {
			return $nodes;
		}

		ref\cast::array($class);
		$class = array_map('trim', $class);
		foreach ($class as $k=>$v) {
			$class[$k] = ltrim($class[$k], '.');
		}
		$class = array_filter($class, 'strlen');
		sort($class);
		$class = array_unique($class);
		if (!count($class)) {
			return $nodes;
		}

		$possible = $parent->getElementsByTagName('*');
		if ($possible->length) {
			foreach ($possible as $child) {
				if ($child->hasAttribute('class')) {
					$classes = $child->getAttribute('class');
					ref\sanitize::whitespace($classes);
					$classes = explode(' ', $classes);
					$overlap = array_intersect($classes, $class);

					if (count($overlap) && (!$all || count($overlap) === count($class))) {
						$nodes[] = $child;
					}
				}
			}
		}

		return $nodes;
	}

	/**
	 * InnerHTML
	 *
	 * Return the "innerHTML" of a DOMNode or DOMElement.
	 *
	 * @param mixed $node Node.
	 * @param bool $xml Use saveXML instead of saveHTML.
	 * @param int $flags Additional flags (XML only).
	 * @return string Content.
	 */
	public static function innerhtml($node, bool $xml=false, int $flags=null) {
		if (
			!is_a($node, 'DOMElement') &&
			!is_a($node, 'DOMNode')
		) {
			return '';
		}

		$content = '';
		$children = $node->childNodes;
		if ($children->length) {
			if ($xml) {
				foreach ($children as $child) {
					if ($flags) {
						$content .= $node->ownerDocument->saveXML($child, $flags);
					}
					else {
						$content .= $node->ownerDocument->saveXML($child);
					}
				}
			}
			else {
				foreach ($children as $child) {
					$content .= $node->ownerDocument->saveHTML($child);
				}
			}
		}

		return $content;
	}

	/**
	 * Parse Styles
	 *
	 * This will convert CSS text (from e.g. a <style> tag) into an
	 * array broken down by rules and selectors.
	 *
	 * @param string $styles Styles.
	 * @return array Parsed styles.
	 */
	public static function parse_css($styles='') {
		ref\cast::string($styles, true);

		// Remove comments.
		while (false !== ($start = mb::strpos($styles, '/*'))) {
			if (false !== ($end = mb::strpos($styles, '*/'))) {
				$styles = str_replace(
					mb::substr($styles, $start, ($end - $start + 2), true),
					'',
					$styles
				);
			}
			else {
				$styles = mb::substr($styles, 0, $start, true);
			}
		}

		// A few more types of comment wrappers we might see.
		$styles = str_replace(
			array('<!--', '//-->', '-->', '//<![CDATA[', '//]]>', '<![CDATA[', ']]>'),
			'',
			$styles
		);

		// Standardize quoting.
		ref\sanitize::quotes($styles, true);
		$styles = str_replace("'", '"', $styles);

		// Whitespace.
		ref\sanitize::whitespace($styles, 0, true);

		// Early bail.
		if (!$styles) {
			return array();
		}

		// Substitute brackets for unlikely characters to make parsing easier
		// hopefully nobody's using braille in their stylesheets...
		$styles = preg_replace('/\{(?![^"]*"(?:(?:[^"]*"){2})*[^"]*$)/u', '⠁', $styles);
		$styles = preg_replace('/\}(?![^"]*"(?:(?:[^"]*"){2})*[^"]*$)/u', '⠈', $styles);

		// Put spaces behind and after parentheses.
		$styles = preg_replace(
			'/\s*(\()\s*(?![^"]*"(?:(?:[^"]*"){2})*[^"]*$)/u',
			' (',
			$styles
		);
		$styles = preg_replace(
			'/\s*(\))\s*(?![^"]*"(?:(?:[^"]*"){2})*[^"]*$)/u',
			') ',
			$styles
		);

		// Make sure {} have no whitespace on either end.
		$styles = preg_replace('/\s*(⠁|⠈|@)\s*/u', '$1', $styles);

		// Push @ rules to their own lines.
		$styles = str_replace('@', "\n@", $styles);
		$styles = explode("\n", $styles);
		$styles = array_map('trim', $styles);

		// Push all rulesets to their own lines (leaving @media-type ones on their own for now).
		foreach ($styles as $k=>$v) {
			// @rule.
			if (0 === strpos($styles[$k], '@')) {
				// Nested, like @media.
				if (false !== strpos($styles[$k], '⠈⠈')) {
					$styles[$k] = preg_replace('/(⠈{2,})/u', "$1\n", $styles[$k]);
				}
				// Not nested, but has properties, like @font-face.
				elseif (false !== strpos($styles[$k], '⠈')) {
					$styles[$k] = str_replace('⠈', "⠈\n", $styles[$k]);
				}
				// Just a line, like @import.
				elseif (preg_match('/;(?![^"]*"(?:(?:[^"]*"){2})*[^"]*$)/', $styles[$k])) {
					$styles[$k] = preg_replace(
						'/;(?![^"]*"(?:(?:[^"]*"){2})*[^"]*$)/u',
						";\n",
						$styles[$k],
						1
					);
				}

				$tmp = explode("\n", $styles[$k]);
				$length = count($tmp);
				for ($x = 1; $x < $length; ++$x) {
					$tmp[$x] = str_replace('⠈', "⠈\n", $tmp[$x]);
				}
				$styles[$k] = implode("\n", $tmp);
			}// end @.
			else {
				$styles[$k] = str_replace('⠈', "⠈\n", $styles[$k]);
			}
		}
		$styles = implode("\n", $styles);

		// One more quick formatting thing, we can get rid of spaces
		// between closing) and punctuation.
		$styles = preg_replace(
			'/\)\s(,|;)(?![^"]*"(?:(?:[^"]*"){2})*[^"]*$)/u',
			')$1',
			$styles
		);

		// And between RGB/URL stuff.
		$styles = preg_replace('/(url|rgba?)\s+\(/', '$1(', $styles);

		$styles = explode("\n", $styles);
		$styles = array_filter($styles, 'strlen');

		$out = array();

		// One more time around!
		foreach ($styles as $k=>$v) {
			$styles[$k] = trim($styles[$k]);

			// Nested rule.
			if (
				(0 === strpos($styles[$k], '@')) &&
				(false !== strpos($styles[$k], '⠈⠈'))
			) {
				$tmp = constants::CSS_NESTED;

				// What kind of @ is this?
				preg_match_all('/^@([a-z\-]+)/ui', $styles[$k], $matches);
				$tmp['@'] = mb::strtolower($matches[1][0]);

				// Find the outermost bit.
				if (false === ($start = mb::strpos($styles[$k], '⠁'))) {
					continue;
				}

				$tmp['selector'] = mb::strtolower(
					trim(
						mb::substr($styles[$k], 0, $start, true)
					),
					false,
					true
				);
				$chunk = mb::substr($styles[$k], $start + 1, -1, true);
				$chunk = str_replace(array('⠁', '⠈'), array('{', '}'), $chunk);
				$tmp['nest'] = static::parse_css($chunk);

				// And build the raw.
				$tmp['raw'] = $tmp['selector'] . '{';
				foreach ($tmp['nest'] as $n) {
					$tmp['raw'] .= $n['raw'];
				}
				$tmp['raw'] .= '}';
			}// end @.
			else {
				$tmp = constants::CSS_FLAT;

				if (0 === strpos($styles[$k], '@')) {
					// What kind of @ is this?
					preg_match_all('/^@([a-z\-]+)/ui', $styles[$k], $matches);
					$tmp['@'] = mb::strtolower($matches[1][0], false, true);
				}

				// A normal {k:v, k:v}.
				preg_match_all('/^([^⠁]+)⠁([^⠈]*)⠈/u', $styles[$k], $matches);
				if (count($matches[0])) {
					// Sorting out selectors is easy.
					$tmp['selectors'] = explode(',', $matches[1][0]);
					$tmp['selectors'] = array_map('trim', $tmp['selectors']);

					// Rules a little trickier.
					$rules = explode(';', $matches[2][0]);
					$rules = array_map('trim', $rules);
					$rules = array_filter($rules, 'strlen');
					if (!count($rules)) {
						continue;
					}

					foreach ($rules as $k2=>$v2) {
						$rules[$k2] = rtrim($rules[$k2], ';') . ';';
						if (preg_match(
							'/:(?![^"]*"(?:(?:[^"]*"){2})*[^"]*$)/',
							$rules[$k2]
						)) {
							$rules[$k2] = preg_replace(
								'/:(?![^"]*"(?:(?:[^"]*"){2})*[^"]*$)/u',
								"\n",
								$rules[$k2],
								1
							);
							list($key, $value) = explode("\n", $rules[$k2]);
							$key = mb::strtolower(trim($key), false, true);
							$value = trim($value);
							$tmp['rules'][$key] = $value;
						}
						else {
							$tmp['rules']['__NONE__'] = $value;
						}
					}

					// Build the raw.
					$tmp['raw'] = implode(',', $tmp['selectors']) . '{';
					foreach ($tmp['rules'] as $k=>$v) {
						if ('__NONE__' === $k) {
							$tmp['raw'] .= $v;
						}
						else {
							$tmp['raw'] .= "$k:$v";
						}
					}
					$tmp['raw'] .= '}';
				}
				// Who knows?
				else {
					$styles[$k] = str_replace(array('⠁', '⠈'), array('{', '}'), $styles[$k]);
					$styles[$k] = trim(rtrim(trim($styles[$k]), ';'));
					if (substr($styles[$k], -1) !== '}') {
						$styles[$k] .= ';';
					}
					$tmp['rules'][] = $styles[$k];
					$tmp['raw'] = $styles[$k];
				}
			}

			$out[] = $tmp;
		}

		return $out;
	}

	/**
	 * Remove namespace (and attached nodes) from a DOMDocument
	 *
	 * @param \DOMDocument $dom Object.
	 * @param string $namespace Namespace.
	 * @return bool True/False.
	 */
	public static function remove_namespace(\DOMDocument $dom, string $namespace) {
		if (!$namespace) {
			return false;
		}

		$xpath = new \DOMXPath($dom);
		$nodes = $xpath->query("//*[namespace::{$namespace} and not(../namespace::{$namespace})]");
		for ($x = 0; $x < $nodes->length; ++$x) {
			$node = $nodes->item($x);
			$node->removeAttributeNS(
				$node->lookupNamespaceURI($namespace),
				$namespace
			);
		}

		return true;
	}

	/**
	 * Remove Nodes
	 *
	 * @param \DOMNodeList $nodes Nodes.
	 * @return bool True/false.
	 */
	public static function remove_nodes(\DOMNodeList $nodes) {
		while ($nodes->length) {
			static::remove_node($nodes->item(0));
		}

		return true;
	}

	/**
	 * Remove Node
	 *
	 * @param mixed $node Node.
	 * @return bool True/false.
	 */
	public static function remove_node($node) {
		if (
			!is_a($node, 'DOMElement') &&
			!is_a($node, 'DOMNode')
		) {
			return false;
		}

		$node->parentNode->removeChild($node);

		return true;
	}
}


