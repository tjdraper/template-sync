<?php

namespace BuzzingPixel\TemplateSync\Service;

use BuzzingPixel\TemplateSync\Library\FileTemplateExtensions;
use BuzzingPixel\TemplateSync\Service\Data\FileTemplates;

/**
 * Class FileTemplateIndexes
 *
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/software/template-sync
 * @copyright Copyright (c) 2017, BuzzingPixel, LLC
 */
class FileTemplateIndexes
{
	/**
	 * @var FileTemplates $fileTemplates
	 */
	private $fileTemplates;

	/**
	 * FileTemplateIndexes constructor
	 *
	 * @param FileTemplates $fileTemplates
	 */
	public function __construct(FileTemplates $fileTemplates)
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
				$file = "{$path}{$key}.group/index.html";
				$oldUmask = umask(0000);
				file_put_contents($file, '{redirect="404"}');
				chmod($file, FILE_WRITE_MODE);
				umask($oldUmask);
			}
		}
	}
}
