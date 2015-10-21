<?php

namespace BuzzingPixel\Addons\TemplateSync\Helper;

class DirectoryArray
{
	/**
	 * Scandir array with directories only (unset first two items)
	 *
	 * @param string $path
	 * @param bool $dirOnly
	 * @return array
	 */
	public function process($path, $dirOnly = false)
	{
		$dir = scandir($path);

		unset($dir[0]);

		unset($dir[1]);

		if ($dirOnly) {
			foreach ($dir as $key => $val) {
				if (! is_dir($path . $val)) {
					unset($dir[$key]);
				}
			}
		}

		return array_values($dir);
	}
}