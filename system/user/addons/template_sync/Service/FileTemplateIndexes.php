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

			$file = $path . $key . '.group/index.html';

			if (! file_exists($file)) {
				$oldUmask = umask(0000);
				file_put_contents($file, '{redirect="404"}');
				chmod($file, 0777);
				umask($oldUmask);
			}
		}
	}
}
