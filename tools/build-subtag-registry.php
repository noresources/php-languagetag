<?php
namespace NoreSources\Language;

$input = __DIR__ . '/../resources/data/language-subtag-registry';

$data = \file_get_contents($input);
$parts = \explode('%%', $data);

$outputDirectory = __DIR__ . '/../src/SubtagRegistry';
$subtags = [];

$subtagTypes = [];

foreach ($parts as $content)
{
	$lines = \explode("\n", $content);
	$subtag = [];
	foreach ($lines as $line)
	{
		if (!preg_match('/(.*?):(.*)/', $line, $matches))
		{
			continue;
		}

		$key = \strtolower(\trim($matches[1]));
		$value = \trim($matches[2]);
		$subtag[$key] = $value;
	}

	if (!\array_key_exists('type', $subtag))
		continue;

	if (!(\array_key_exists('tag', $subtag) ||
		\array_key_exists('subtag', $subtag)))
		continue;

	$key = \array_key_exists('tag', $subtag) ? $subtag['tag'] : $subtag['subtag'];
	unset($subtag['subtag']);

	$type = $subtag['type'];
	unset($subtag['type']);

	if (!\array_key_exists($type, $subtags))
		$subtags[$type] = [];
	elseif (\array_key_exists($key, $subtags[$type]))
	{
		throw new \Exception(
			'Conflict ' .
			var_export([
				$subtags[$type][$key],
				$subtag
			], true));
	}

	$subtags[$type][$key] = $subtag;
	if (!\array_key_exists($key, $subtagTypes))
		$subtagTypes[$key] = [];

	$subtagTypes[$key][] = $type;
}

foreach ($subtags as $type => $table)
{
	$base = $outputDirectory . '/' . $type;
	\file_put_contents($base . '.details.json', \json_encode($table));
	\file_put_contents($base . '.json',
		\json_encode(\array_keys($table)));
}

\file_put_contents($outputDirectory . '/subtagtypes.json',
	\json_encode($subtagTypes));

