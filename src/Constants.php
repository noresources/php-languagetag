<?php
/**
 * Copyright © 2012 - 2020 by Renaud Guillard (dev@nore.fr)
 * Distributed under the terms of the MIT License, see LICENSE
 *
 * @package Language
 */
namespace NoreSources\Language;

class Constants
{

	/**
	 * Subtag type.
	 *
	 * Extended language subtags.
	 *
	 * @var string
	 */
	const SUBTAG_TYPE_EXTLANG = 'extlang';

	/**
	 * Subtag type.
	 *
	 * Grandfathered subtags.
	 *
	 * @var string
	 */
	const SUBTAG_TYPE_GRANDFATHER = 'grandfathered';

	/**
	 * Subtag type.
	 *
	 * Main language subtags.
	 *
	 * @var string
	 */
	const SUBTAG_TYPE_LANGUAGE = 'language';

	/**
	 * Subtag type.
	 *
	 * Redundant subtags.
	 *
	 * @var string
	 */
	const SUBTAG_TYPE_REDUNDANT = 'redundant';

	/**
	 * Subtag type.
	 *
	 * Region subtags.
	 *
	 * @var string
	 */
	const SUBTAG_TYPE_REGION = 'region';

	/**
	 * Subtag type.
	 *
	 * Script subtags.
	 *
	 * @var string
	 */
	const SUBTAG_TYPE_SCRIPT = 'script';

	/**
	 * Subtag type.
	 *
	 * Language variant subtags.
	 *
	 * @var string
	 */
	const SUBTAG_TYPE_VARIANT = 'variant';

	/**
	 * Subtag type.
	 *
	 * Regular subtags.
	 *
	 * @var string
	 */
	const SUBTAG_TYPE_REGULAR = 'regular';

	/**
	 * Subtag type.
	 *
	 * Irregular subtags.
	 *
	 * @var string
	 */
	const SUBTAG_TYPE_IRREGULAR = 'irregular';

	/**
	 * xtlang = 3ALPHA ; selected ISO 639 codes
	 * *2("-" 3ALPHA) ; permanently reserved
	 */
	const SUBTAG_PATTERN_EXTLANG = '[A-Za-z]{3}(?:-[A-Za-z]{3}){0,2}';

	/**
	 * script = 4ALPHA ; ISO 15924 code
	 *
	 * @var string
	 */
	const SUBTAG_PATTERN_SCRIPT = '[A-Za-z]{4}';

	/**
	 * Region subtag pattern
	 *
	 * region = 2ALPHA ; ISO 3166-1 code
	 * / 3DIGIT ; UN M.49 code
	 *
	 * @var string
	 */
	const SUBTAG_PATTERN_REGION = '(?:[A-Za-z]{2})|(?:[0-9]{3})';

	/**
	 * variant = 5*8alphanum ; registered variants
	 * / (DIGIT 3alphanum)
	 *
	 * @var string
	 */
	const SUBTAG_PATTERN_VARIANT = '(?:[A-Za-z0-9]{5,8})|(?:[0-9][A-Za-z0-9]{3})';

	const SUBTAG_PATTERN_SINGLETON = '[0-9A-WY-Za-wy-z]';

	/**
	 * extension = singleton 1*("-" (2*8alphanum))
	 *
	 * @var string
	 */
	const SUBTAG_PATTERN_EXTENSION = self::SUBTAG_PATTERN_SINGLETON .
		'-[A-Za-z0-9]{2,8}';

	/**
	 * privateuse = "x" 1*("-" (1*8alphanum))
	 *
	 * @var string
	 */
	const SUBTAG_PATTERN_PRIVATE_USE = 'x(?:-[A-Za-z0-9]{1,8})?';

	/**
	 * language = 2*3ALPHA ; shortest ISO 639 code
	 * ["-" extlang] ; sometimes followed by extended language subtags
	 * / 4ALPHA ; or reserved for future use
	 * / 5*8ALPHA ; or registered language subtag
	 *
	 * @var string
	 */
	const SUBTAG_PATTERN_LANGUAGE = '(?:([A-Za-z]{2,3})(-' .
		self::SUBTAG_PATTERN_EXTLANG .
		')?)|(?:[A-Za-z]{4})|(?:[A-Za-z]{5,8})';

	/**
	 * Basic language range
	 *
	 * @see https://datatracker.ietf.org/doc/html/rfc4647#section-2.1
	 */
	const RANGE_BASIC_PATTERN = '(?:[A-Za-z]{1,8}(?:-[A-Za-z0-9]{1,8})*)|\*';

	/**
	 * Extended language range
	 *
	 * @see https://datatracker.ietf.org/doc/html/rfc4647#section-2.2
	 */
	const RANGE_EXTENDED_PATTERN = '(?:(?:[A-Za-z]{1,8})|\*)(?:-(?:(?:[A-Za-z0-9]{1,8})|\*))*';
}