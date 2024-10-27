<?php
namespace NoreSources\Language;

use NoreSources\SingletonTrait;
use NoreSources\Container\Container;
use NoreSources\Container\KeyNotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Repository of registered language subtags
 */
class SubtagRegistry implements ContainerInterface
{
	use SingletonTrait;

	public function __construct()
	{}

	/**
	 *
	 * @param string $type
	 *        	Subtag type
	 * {@inheritdoc}
	 * @see \Psr\Container\ContainerInterface::has()
	 */
	public function has(string $type) : bool
	{
		return \in_array($type,
			[
				Constants::SUBTAG_TYPE_EXTLANG,
				Constants::SUBTAG_TYPE_GRANDFATHER,
				Constants::SUBTAG_TYPE_IRREGULAR,
				Constants::SUBTAG_TYPE_LANGUAGE,
				Constants::SUBTAG_TYPE_REDUNDANT,
				Constants::SUBTAG_TYPE_REGION,
				Constants::SUBTAG_TYPE_REGULAR,
				Constants::SUBTAG_TYPE_SCRIPT,
				Constants::SUBTAG_TYPE_VARIANT
			]);
	}

	/**
	 * Get the repository of language subtags of a given type
	 *
	 * @param string $type
	 *        	Subtag type
	 * @return SubtagMap Subtags of the given type
	 */
	public function get(string $type)
	{
		if (!$this->has($type))
			throw new KeyNotFoundException($type);
		return $this->getSubtagTypeRegistry($type);
	}

	/**
	 *
	 * @param string $subtag
	 *        	Subtag key
	 * @return array List of type where $subtag can be found
	 */
	public function getSubtagTypes($subtag)
	{
		if (!isset($this->subtagTypes))
		{
			$data = $this->load('subtagtypes');
			$this->subtagTypes = new SubtagMap($data);
		}

		return $this->subtagTypes->offsetGet(\strtolower($subtag));
	}

	/**
	 *
	 * @param string $type
	 *        	Subtag type
	 * @return string[] List of subtag keys of the given type
	 */
	public function getTypeSubtags($type)
	{
		return Container::keys($this->getSubtagTypeRegistry($type));
	}

	/**
	 * Get a language subtag information
	 *
	 * @param string $type
	 *        	Subtag type
	 * @param string $tag
	 *        	Subtag key
	 * @return array Subtag data
	 */
	public function getSubtag($type, $tag)
	{
		$this->getSubtagTypeRegistry($type)->get($tag);
	}

	/**
	 *
	 * @return SubtagMap
	 */
	private function getSubtagTypeRegistry($type)
	{
		if (!isset($this->registry))
			$this->registry = [];
		if (!Container::keyExists($this->registry, $type))
			$this->registry[$type] = new SubtagMap(
				$this->load($type . '.details'));

		return $this->registry[$type];
	}

	private function load($key)
	{
		$path = __DIR__ . '/SubtagRegistry/' . $key . '.json';
		return \json_decode(\file_get_contents($path), true);
	}

	/**
	 *
	 * @var array<string, SubtagMap>
	 */
	private $registry;

	/**
	 *
	 * @var \ArrayAccessible
	 */
	private $subtagTypes;
}
