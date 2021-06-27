<?php
/**
 * Copyright © 2012 - 2020 by Renaud Guillard (dev@nore.fr)
 * Distributed under the terms of the MIT License, see LICENSE
 */

/**
 *
 * @package Core
 */
namespace NoreSources\Language;

use NoreSources\Container\Container;
use NoreSources\Type\ArrayRepresentation;
use NoreSources\Type\StringRepresentation;

/**
 * Language tag
 */
class LanguageTag implements StringRepresentation, ArrayRepresentation
{

	/**
	 *
	 * @param LanguageRangeFilter $filter
	 *        	Matching scheme
	 * @return boolean TRUE if the language tag match the given filter
	 */
	public function match(LanguageRangeFilter $filter)
	{
		return $filter->match($this);
	}

	/**
	 *
	 * @param string|array $language
	 *        	[ISO 639] language and optional subtags
	 * @param string $script
	 *        	Language script
	 * @param string $region
	 *        	[ISO 3166-1] Language region or [UN_M.49] country number
	 * @param array $variants
	 *        	Language variants
	 * @param array $extensions
	 *        	Language extensions
	 * @param string $privateuse
	 *        	Private use tag
	 */
	public function __construct($language, $script = NULL,
		$region = NULL, $variants = array(), $extensions = array(),
		$privateuse = NULL)
	{
		if (\is_string($language))
			$language = \explode('-', $language);
		$this->language = $language;
		if (isset($script))
			$this->script = $script;
		if (isset($this->region))
			$s .= '-' . $this->region;
		if (isset($variants))
		{
			if (\is_string($variants))
				$variants = \explode('-', $variants);
			$this->variants = $variants;
		}

		if (isset($extensions))
		{
			if (\is_string($extensions))
				$extensions = \explode('-', $extensions);
			$this->extensions = $extensions;
		}
		if (isset($privateuse))
			$this->privateUse = $privateuse;
	}

	public function __toString()
	{
		$s = Container::implodeValues($this->language, '-');
		if (isset($this->script))
			$s .= '-' . $this->script;
		if (isset($this->region))
			$s .= '-' . $this->region;
		if (isset($this->variants))
			$s .= Container::implodeValues($this->variants,
				[
					Container::IMPLODE_BEFORE => '-'
				]);
		if (isset($this->extensions))
			$s .= Container::implodeValues($this->extensions,
				[
					Container::IMPLODE_BEFORE => '-'
				]);
		if (isset($this->privateUse))
			$s .= '-' . $this->privateUse;
		return $s;
	}

	public function getArrayCopy()
	{
		return \explode('-', \strval($this));
	}

	public function getMainLanguageTag()
	{
		return $this->language[0];
	}

	/**
	 *
	 * @return string[]
	 */
	public function getMainLanguageSubtags()
	{
		return $this->language;
	}

	/**
	 *
	 * @return string|NULL
	 */
	public function getScriptSubtag()
	{
		return $this->script;
	}

	/**
	 *
	 * @return string|NULL
	 */
	public function getRegionTag()
	{
		return $this->region;
	}

	/**
	 *
	 * @return string[]|NULL
	 */
	public function getVariantSubtags()
	{
		return $this->variants;
	}

	/**
	 *
	 * @return string[]|NULL
	 */
	public function getExtensionSubtags()
	{
		return $this->extensions;
	}

	/**
	 *
	 * @return string
	 */
	public function getPrivateUseTag()
	{
		return $this->privateUse;
	}

	/**
	 *
	 * @var string[] Language code & extended sub tags
	 */
	private $language;

	/**
	 *
	 * @var string Script
	 */
	private $script;

	/**
	 *
	 * @var string Region code
	 */
	private $region;

	/**
	 *
	 * @var string[] Variants
	 */
	private $variants;

	/**
	 *
	 * @var string[] Extensions
	 */
	private $extensions;

	/**
	 *
	 * @var string
	 */
	private $privateUse;
}