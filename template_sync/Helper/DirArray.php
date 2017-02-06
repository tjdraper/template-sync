<?php

namespace BuzzingPixel\TemplateSync\Helper;

/**
 * Class DirArray
 *
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/software/template-sync
 * @copyright Copyright (c) 2017, BuzzingPixel, LLC
 */
class DirArray
{
	/**
	 * Get directory array of all items
	 *
	 * @param string $path
	 * @return array
	 */
	public static function all($path)
	{
		// Normalize the path
		$path = rtrim($path, '/') . '/';

		// Return the contents of the directory as an array
		return DirArray::get($path);
	}

	/**
	 * Get files in a directory
	 *
	 * @param string $path
	 * @return array
	 */
	public static function files($path)
	{
		// Normalize the path
		$path = rtrim($path, '/') . '/';

		// Get the contents of the directory as an array
		$content = DirArray::get($path);

		$returnContent = array();

		// Go through each item and make sure it's a file before adding it
		// to the return items
		foreach ($content as $item) {
			if (file_exists($path . $item) and ! is_dir($path . $item)) {
				$returnContent[] = $item;
			}
		}

		return $returnContent;
	}

	/**
	 * Get directories from a directory
	 *
	 * @param string $path
	 * @return array
	 */
	public static function directories($path)
	{
		// Normalize the path
		$path = rtrim($path, '/') . '/';

		// Get the contents of the directory as an array
		$content = DirArray::get($path);

		$returnContent = array();

		// Go through each item and make sure it's a directory before adding it
		// to the return items
		foreach ($content as $item) {
			if (is_dir($path . $item)) {
				$returnContent[] = $item;
			}
		}

		return $returnContent;
	}

	/**
	 * Get
	 *
	 * @param string $path
	 * @return array
	 */
	private static function get($path)
	{
		// Get array of directory contents
		if (! is_dir($path)) {
			return array();
		}

		// Get files in directory
		$contents = array();
		$handle = opendir($path);
		if ($handle) {
			while (false !== ($entry = readdir($handle))) {
				$contents[] = $entry;
			}
		}

		// Remove . and ..
		$contents = array_diff($contents, array('.', '..'));

		// Make sure the file has a file extension
		$returnContent = array();
		foreach ($contents as $content) {
			$pathinfo = pathinfo($content);
			if ($pathinfo['filename']) {
				$returnContent[] = $content;
			}
		}

		return array_values($returnContent);
	}
}
