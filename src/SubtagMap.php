<?php
namespace NoreSources\Language;

use NoreSources\Container\ArrayAccessContainerInterfaceTrait;
use NoreSources\Container\CaseInsensitiveKeyMapTrait;
use NoreSources\Type\ArrayRepresentation;
use Psr\Container\ContainerInterface;

/**
 * Map of language subtag informations
 */
class SubtagMap implements ContainerInterface, ArrayRepresentation,
	\Countable
{
	use CaseInsensitiveKeyMapTrait;
	use ArrayAccessContainerInterfaceTrait;
}
