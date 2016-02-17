<?php

/**
 * SyncPartials service
 *
 * @package template_sync
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/template-sync
 * @copyright Copyright (c) 2016, BuzzingPixel
 */

namespace BuzzingPixel\TemplateSync\Service;

class SyncPartials
{
	private $filePartials;
	private $dbPartials;

	/**
	 * SyncPartials constructor
	 *
	 * @param array $filePartials
	 * @param array $dbPartials
	 */
	public function __construct($filePartials, $dbPartials)
	{
		$this->filePartials = $filePartials;
		$this->dbPartials = $dbPartials;
	}

	/**
	 * Sync the partials database with the filesystem
	 */
	public function sync()
	{
		// Loop through the template partials
		foreach ($this->dbPartials as $dbKey => $dbVal) {
			// If the file does not exist, or does not have html extension
			// Delete the DB partial
			if (
				! isset($this->filePartials[$dbKey]) ||
				$this->filePartials[$dbKey]->extension !== 'html'
			) {
				$dbVal->delete();
			}
		}
	}
}
