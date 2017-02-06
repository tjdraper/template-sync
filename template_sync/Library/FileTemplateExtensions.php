<?php

/**
 * Template Sync FileTemplates Service
 *
 * @package template_sync
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/template-sync
 * @copyright Copyright (c) 2016, BuzzingPixel
 */

namespace BuzzingPixel\TemplateSync\Library;

class FileTemplateExtensions
{
	// Type mapping
	private static $typeMap = array(
		'css' => 'css',
		'html' => 'webpage',
		'js' => 'js',
		'rss' => 'feed',
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
