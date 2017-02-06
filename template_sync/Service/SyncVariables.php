<?php

/**
 * SyncVariables service
 *
 * @package template_sync
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/template-sync
 * @copyright Copyright (c) 2016, BuzzingPixel
 */

namespace BuzzingPixel\TemplateSync\Service;

class SyncVariables
{
	private $fileVariables;
	private $dbVariables;

	/**
	 * SyncVariables constructor
	 *
	 * @param array $fileVariables
	 * @param array $dbVariables
	 */
	public function __construct($fileVariables, $dbVariables)
	{
		$this->fileVariables = $fileVariables;
		$this->dbVariables = $dbVariables;
	}

	/**
	 * Sync the variables database with the filesystem
	 */
	public function sync()
	{
		// Loop through the template partials
		foreach ($this->dbVariables as $dbKey => $dbVal) {
			// If the file does not exist, or does not have html extension
			// Delete the DB partial
			if (
				! isset($this->fileVariables[$dbKey]) ||
				$this->fileVariables[$dbKey]->extension !== 'html'
			) {
				$dbVal->delete();
			}
		}
	}
}
