<?php

/**
 * FileTemplateIndexes service
 *
 * @package template_sync
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/template-sync
 * @copyright Copyright (c) 2016, BuzzingPixel
 */

namespace BuzzingPixel\TemplateSync\Service;

use BuzzingPixel\TemplateSync\Library\FileTemplateExtensions;

class FileTemplateIndexes
{
	private $fileTemplates;

	/**
	 * FileTemplateIndexes constructor
	 *
	 * @param object $fileTemplates
	 */
	public function __construct(
		\BuzzingPixel\TemplateSync\Service\Data\FileTemplates $fileTemplates
	)
	{
		$this->fileTemplates = $fileTemplates;
	}

	/**
	 * Write template group indexes as needed
	 */
	public function write()
	{
		// Set the template path
		$templateBasePath = SYSPATH . 'user/templates/';
		$path = $templateBasePath . ee()->config->item('site_short_name') . '/';

		foreach ($this->fileTemplates as $key => $val) {
			if ($key === '_partials' || $key === '_variables') {
				continue;
			}

			// Get file extensions
			$fileExtensions = FileTemplateExtensions::getExtensions();

			// Start by assuming file does not exist
			$indexFileExists = false;

			// Iterate through file extensions
			foreach ($fileExtensions as $ext) {
				$file = "{$path}{$key}.group/index.{$ext}";

				if (file_exists($file)) {
					$indexFileExists = true;
					break;
				}
			}

			if (! $indexFileExists) {
				$oldUmask = umask(0000);
				file_put_contents($file, '{redirect="404"}');
				chmod($file, FILE_WRITE_MODE);
				umask($oldUmask);
			}
		}
	}
}
