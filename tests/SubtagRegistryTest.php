<?php
/**
 * Copyright © 2021 by Renaud Guillard (dev@nore.fr)
 * Distributed under the terms of the MIT License, see LICENSE
 *
 * @package HTTP
 */
namespace NoreSources\Language\Test;

use NoreSources\Language\SubtagRegistry;

class SubtagRegistryTest extends \PHPUnit\Framework\TestCase
{

	public function testSubtagTypes()
	{
		$registry = SubtagRegistry::getInstance();
		$this->assertInstanceOf(SubtagRegistry::class, $registry);

		$en = $registry->getSubtagTypes('en');

		$this->assertCount(1, $en);
		$this->assertContains('language', $en,
			'en is a main language subtag');
	}
}
