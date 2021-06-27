<?php

/**
 * Copyright © 2021 by Renaud Guillard (dev@nore.fr)
 * Distributed under the terms of the MIT License, see LICENSE
 *
 * @package HTTP
 */
namespace NoreSources\Language\Test;

use NoreSources\Container\Container;
use NoreSources\Language\LanguageRange;
use NoreSources\Language\LanguageRangeFilter;

class LanguageRangeTest extends \PHPUnit\Framework\TestCase
{

	public function testParse()
	{
		$tests = [
			'Any language' => [
				'text' => '*',
				'type' => LanguageRange::TYPE_BASIC
			],
			'Basic language' => [
				'text' => 'fr',
				'type' => LanguageRange::TYPE_BASIC
			],
			'Basic multi-tag' => [
				'text' => 'fr-FR',
				'type' => LanguageRange::TYPE_BASIC
			],
			'Extended' => [
				'text' => 'fr-*',
				'type' => LanguageRange::TYPE_EXTENDED
			],
			'Invalid' => [
				'text' => 'farfaraway-andtoolong-US',
				'error' => \InvalidArgumentException::class
			],
			'not basic' => [
				'text' => 'en-*',
				'type' => LanguageRange::TYPE_BASIC,
				'error' => \InvalidArgumentException::class
			]
		];

		foreach ($tests as $label => $test)
		{
			$text = Container::keyValue($test, 'text');
			$type = Container::keyValue($test, 'type',
				LanguageRange::TYPE_UNDEFINED);
			$error = Container::keyValue($test, 'error');
			$expected = Container::keyValue($test, 'expected', $text);
			$expectedType = Container::keyValue($test, 'etype', $type);

			$result = null;
			try
			{
				$result = LanguageRange::fromString($text, $type);
			}
			catch (\InvalidArgumentException $e)
			{
				$result = $e;
			}

			if ($error)
			{
				$this->assertInstanceOf($error, $result);
				continue;
			}

			$this->assertInstanceOf(LanguageRange::class, $result,
				$label . ' class');
			$this->assertEquals($expected, \strval($result),
				$label . ' text');
			$this->assertEquals($expectedType, $result->getRangeType(),
				$label . ' type');
		}
	}

	public function testFiltering()
	{
		$tests = [
			'RFC example' => [
				'range' => 'de-*-DE',
				'tags' => [
					'de-DE' => true,
					'de-de' => true,
					'de-Latn-DE' => true,
					'de-Latf-DE' => true,
					'de-DE-x-goethe' => true,
					'de-Latn-DE-1996' => true,
					'de-Deva-DE' => true,
					'de' => false,
					'de-x-DE' => false,
					'de-Deva' => false
				]
			]
		];

		foreach ($tests as $label => $test)
		{
			$range = Container::keyValue($test, 'range');
			$tags = Container::keyValue($test, 'tags');

			$range = LanguageRange::fromString($range);
			$filter = new LanguageRangeFilter($range);

			foreach ($tags as $tag => $expected)
			{
				$actual = $filter->match($tag);
				$this->assertEquals($expected, $actual,
					$label . PHP_EOL . \strval($range) . ' vs ' . $tag);
			}
		}
	}
}
