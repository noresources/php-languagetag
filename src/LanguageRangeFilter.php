<?php

/**
 * Copyright © 2021 by Renaud Guillard (dev@nore.fr)
 * Distributed under the terms of the MIT License, see LICENSE
 *
 * @package HTTP
 */
namespace NoreSources\Language;

use NoreSources\Container\Container;
use NoreSources\Type\TypeConversion;

class LanguageRangeFilter
{

	/**
	 *
	 * @param string|LanguageRange|array $range
	 */
	public function __construct($range)
	{
		if (!($range instanceof LanguageRange))
		{
			if (\is_array($range))
				$range = \implode('-', $range);

			$range = LanguageRange::fromString(
				TypeConversion::toString($range),
				LanguageRange::TYPE_EXTENDED);
		}

		$this->languageRange = $range;
	}

	/**
	 *
	 * @return LanguageRange
	 */
	public function getLanguageRange()
	{
		return $this->languageRange;
	}

	/**
	 *
	 * @param LanguageTag|array|string $tag
	 *        	Language tag to test agains the filter
	 * @return boolean TRUE if $tag match the range
	 */
	public function match($tag)
	{
		if ($this->languageRange->getRangeType() ==
			LanguageRange::TYPE_BASIC)
			return $this->matchBasic($tag);
		return $this->matchExtended($tag);
	}

	/**
	 * Basic filtering
	 *
	 * @param LanguageTag|string $tag
	 * @return boolean
	 * @see https://datatracker.ietf.org/doc/html/rfc4647#section-3.3.1
	 */
	private function matchBasic($tag)
	{
		if (!isset($this->stringForm))
		{
			$this->stringForm = \strval($this->languageRange);
			$this->stringLength = \strlen($this->stringForm);
		}

		if ($this->stringForm == LanguageRange::ANY)
			return true;

		$ts = \strval($tag);
		$tl = \strlen($ts);
		if ($tl < $this->stringLength)
			return false;

		if (\strcasecmp($this->stringForm, $ts) != 0)
			return false;

		if ($tl > $this->stringLength &&
			\substr($ts, $this->stringLength, 1) != '-')
			return false;
		return true;
	}

	/**
	 *
	 * @param string|LanguageTag|array $tag
	 * @return boolean
	 * @see https://datatracker.ietf.org/doc/html/rfc4647#section-3.3.2
	 */
	private function matchExtended($tag)
	{
		if (!isset($this->arrayForm))
			$this->arrayForm = $this->languageRange->getArrayCopy();

		if ($tag instanceof LanguageTag)
			$tag = $tag->getArrayCopy();
		elseif (\is_string($tag))
			$tag = \explode('-', $tag);
		else
			$tag = Container::createArray($tag);

		$ri = 0;
		$rc = Container::count($this->arrayForm);
		$ti = 0;
		$tc = Container::count($tag);

		/*
		 * .  Begin with the first subtag in each list.  If the first subtag in
		 the range does not match the first subtag in the tag, the overall
		 match fails.  Otherwise, move to the next subtag in both the
		 range and the tag.
		 */
		if ($rc && $tc)
		{
			if (!($this->arrayForm[0] == LanguageRange::ANY ||
				\strcasecmp($this->arrayForm[0], $tag[0]) == 0))
				return false;
			$ri++;
			$ti++;
		}

		while ($ri < $rc)
		{
			$r = $this->arrayForm[$ri];
			if ($r == LanguageRange::ANY)
			{
				$ri++;
				continue;
			}

			if ($ti >= $tc)
				return false;

			$t = $tag[$ti];

			if (\strcasecmp($r, $t) == 0)
			{
				$ri++;
				$ti++;
			}

			if (\preg_match('/^[A-Za-z0-9]$/', $t))
				return false;

			$ti++;
		}

		return true;
	}

	/**
	 *
	 * @var LanguageRange
	 */
	private $languageRange;

	/**
	 *
	 * @var string
	 */
	private $stringForm;

	private $stringLength;

	/**
	 *
	 * @var array
	 */
	private $arrayForm;
}
