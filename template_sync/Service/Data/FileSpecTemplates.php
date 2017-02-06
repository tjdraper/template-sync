<?php

/**
 * Template Sync FileSpecTemplates Service
 *
 * @package template_sync
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/template-sync
 * @copyright Copyright (c) 2016, BuzzingPixel
 */

namespace BuzzingPixel\TemplateSync\Service\Data;

use BuzzingPixel\TemplateSync\Helper\DirArray;

class FileSpecTemplates extends Base
{
	/**
	 * FileSpecTemplates constructor
	 */
	public function __construct()
	{
		// Set the template path
		$templateBasePath = SYSPATH . 'user/templates/';
		$path = $templateBasePath . ee()->config->item('site_short_name') . '/';
		$path .= '_spec/';

		// Check if the directory exists
		if (! is_dir($path)) {
			// Make sure PHP can write file permissions
			$oldmask = umask(0000);

			// Create the directory
			mkdir($path, DIR_WRITE_MODE);

			// Reset the umask
			umask($oldmask);
		}

		// Get the spec template files
		$templates = DirArray::files($path);

		// Start a final templates array
		$finalFileTemplates = array();

		foreach ($templates as $template) {
			$pathInf = pathinfo($template);
			$name = $pathInf['filename'];
			$ext = isset($pathInf['extension']) ? $pathInf['extension'] : false;

			// Make sure the extension is correct
			if ($ext !== 'html') {
				continue;
			}

			// Get the file contents
			$fileContent = file_get_contents($path . $template);

			// Look for the data_title variable
			preg_match(
				'/{data_title}(.*){\/data_title}/',
				$fileContent,
				$matches
			);

			if ($matches) {
				$fileContent = trim(str_replace($matches[0], '', $fileContent)) . "\n";
				$dataTitle = $matches[1];
			} else {
				$dataTitle = '';
			}

			$templateObj = new FileTemplate();

			$templateObj->setup(array(
				'name' => $name,
				'extension' => $ext,
				'content' => $fileContent,
				'data_title' => $dataTitle
			));

			$finalFileTemplates[$name] = $templateObj;
		}

		$this->setup($finalFileTemplates);
	}
}
