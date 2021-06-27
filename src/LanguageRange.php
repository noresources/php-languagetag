<?php

/**
 * Copyright © 2021 by Renaud Guillard (dev@nore.fr)
 * Distributed under the terms of the MIT License, see LICENSE
 *
 * @package HTTP
 */
namespace NoreSources\Language;

use NoreSources\Container\Container;
use NoreSources\Type\ArrayRepresentation;
use NoreSources\Type\StringRepresentation;
use NoreSources\Type\TypeConversion;

/**
 * Language range pattern
 */
class LanguageRange implements StringRepresentation, ArrayRepresentation
{

	/**
	 * Wildcard special subtag
	 *
	 * @var string
	 */
	const ANY = '*';

	/**
	 * Language range type
	 *
	 * @var integer Undefined range type
	 */
	const TYPE_UNDEFINED = 0;

	/**
	 * Language range type
	 *
	 * @var integer Basic range type
	 * @see https://datatracker.ietf.org/doc/html/rfc4647#section-3.3.1
	 */
	const TYPE_BASIC = 1;

	/**
	 * Language range type
	 *
	 * @var integer Extended range type
	 * @see https://datatracker.ietf.org/doc/html/rfc4647#section-3.3.2
	 */
	const TYPE_EXTENDED = 2;

	/**
	 *
	 * @param string $text
	 *        	Text to parse
	 * @param integer $type
	 *        	Range format
	 * @throws \InvalidArgumentException
	 * @return LanguageRange
	 */
	public static function fromString($text,
		$type = self::TYPE_UNDEFINED)
	{
		$patterns = [];
		$names = [];
		if ($type != self::TYPE_EXTENDED)
		{
			$patterns[self::TYPE_BASIC] = Constants::RANGE_BASIC_PATTERN;
			$names[self::TYPE_BASIC] = 'basic';
		}
		if ($type != self::TYPE_BASIC)
		{
			$patterns[self::TYPE_EXTENDED] = Constants::RANGE_EXTENDED_PATTERN;
			$names[self::TYPE_EXTENDED] = 'extended';
		}

		$range = null;
		$type = null;

		foreach ($patterns as $t => $p)
		{
			$m = [];
			$p = '^(' . $p . ')(?=(?:\s|$))';

			if (\preg_match(chr(1) . $p . chr(1), $text, $m))
			{
				$type = $t;
				$range = $m[0];
				break;
			}
		}

		if ($range === null)
			throw new \InvalidArgumentException(
				$text . ' is not a valid ' .
				Container::implodeValues($names,
					[
						Container::IMPLODE_BETWEEN => ', ',
						Container::IMPLODE_BETWEEN_LAST => ' nor '
					]) . ' language range');

		return new LanguageRange($range, $type);
	}

	/**
	 *
	 * @param string|LanguageRange $range
	 *        	Language range text
	 * @param integer $type
	 *        	Language range type. if TYPE_UNDEFINED, the type is auto-detected.
	 */
	public function __construct($range, $type = self::TYPE_UNDEFINED)
	{
		$this->rangeText = TypeConversion::toString($range);
		if ($type == self::TYPE_UNDEFINED)
		{
			if ($range instanceof LanguageRange)
			{
				$type = $range->getRangeType();
			}
			else
			{
				foreach ([
					self::TYPE_BASIC => Constants::RANGE_BASIC_PATTERN,
					self::TYPE_EXTENDED => Constants::RANGE_EXTENDED_PATTERN
				] as $t => $p)
				{
					$p = '^(' . $p . ')$';
					if (\preg_match(chr(1) . $p . chr(1),
						$$this->rangeText))
					{
						$type = $t;
						break;
					}
				}
			}
		}

		$this->rangeType = $type;
	}

	/**
	 *
	 * @return integer Range type
	 */
	public function getRangeType()
	{
		return $this->rangeType;
	}

	public function __toString()
	{
		return $this->rangeText;
	}

	/**
	 *
	 * @return array Broken-out range parts
	 */
	public function getArrayCopy()
	{
		return \explode('-', $this->rangeText);
	}

	/**
	 *
	 * @var string Language range text
	 */
	private $rangeText;

	/**
	 *
	 * @var integer Range type
	 */
	private $rangeType;
}
