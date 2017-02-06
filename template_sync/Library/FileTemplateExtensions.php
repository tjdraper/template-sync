<?php

namespace BuzzingPixel\TemplateSync\Library;

/**
 * Class FileTemplateExtensions
 *
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/software/template-sync
 * @copyright Copyright (c) 2017, BuzzingPixel, LLC
 */
class FileTemplateExtensions
{
	// Type mapping
	private static $typeMap = array(
		'css' => 'css',
		'html' => 'webpage',
		'js' => 'js',
		'rss' => 'feed',
		'feed' => 'feed',
		'xml' => 'xml'
	);

	/**
	 * Get file type extensions
	 *
	 * @return array
	 */
	public static function getExtensions()
	{
		return array_keys(self::$typeMap);
	}

	/**
	 * Get the type map
	 *
	 * @return array
	 */
	public static function getTypeMap()
	{
		return self::$typeMap;
	}
}
