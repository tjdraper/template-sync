<?php

/**
 * SyncSpecTemplates service
 *
 * @package template_sync
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/template-sync
 * @copyright Copyright (c) 2016, BuzzingPixel
 */

namespace BuzzingPixel\TemplateSync\Service;

use BuzzingPixel\TemplateSync\Service\Data\FileSpecTemplates;
use BuzzingPixel\TemplateSync\Service\Data\DbSpecTemplates;

class SyncSpecTemplates
{
	private $fileSpecTemplates;
	private $dbSpecTemplates;

	/**
	 * SyncSpecTemplates constructor
	 *
	 * @param \BuzzingPixel\TemplateSync\Service\Data\FileSpecTemplates $fileSpecTemplates
	 * @param \BuzzingPixel\TemplateSync\Service\Data\DbSpecTemplates $dbSpecTemplates
	 */
	public function __construct(
		FileSpecTemplates $fileSpecTemplates,
		DbSpecTemplates $dbSpecTemplates
	)
	{
		$this->fileSpecTemplates = $fileSpecTemplates;
		$this->dbSpecTemplates = $dbSpecTemplates;
	}

	/**
	 * Sync the spec templates database with the filesystem
	 */
	public function sync()
	{
		// Set the template path
		$templateBasePath = SYSPATH . 'user/templates/';
		$path = $templateBasePath . ee()->config->item('site_short_name') . '/';
		$path .= '_spec/';

		// Loop through the spec templates
		foreach ($this->dbSpecTemplates as $dbKey => $dbVal) {
			// If the file does not exist, right it and continue
			if (! $this->fileSpecTemplates->{$dbKey}) {
				// Start template content variable
				$content = '';

				// If there is a title, set it
				if ($dbVal->data_title) {
					$content .= "{data_title}{$dbVal->data_title}{/data_title}\n\n";
				}

				// Add the template content
				$content .= "{$dbVal->template_data}\n";

				$oldUmask = umask(0000);

				// Write template contents to file
				file_put_contents(
					$path . $dbKey . '.html',
					$content
				);

				chmod($path . $dbKey . '.html', FILE_WRITE_MODE);
				umask($oldUmask);

				// Move on to the next template
				continue;
			}

			$save = false;

			// Make sure DB spec template matches file content
			$fileTemplate = $this->fileSpecTemplates->{$dbKey};

			if ($fileTemplate->content !== $dbVal->template_data) {
				$save = true;
				$dbVal->template_data = $fileTemplate->content;
			}

			if ($fileTemplate->data_title !== $dbVal->data_title) {
				$save = true;
				$dbVal->data_title = $fileTemplate->data_title;
			}

			if ($save) {
				$dbVal->edit_date = time();
				$dbVal->save();
			}
		}
	}
}
