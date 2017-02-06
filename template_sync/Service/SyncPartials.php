<?php

namespace BuzzingPixel\TemplateSync\Service;

/**
 * Class SyncPartials
 *
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/software/template-sync
 * @copyright Copyright (c) 2017, BuzzingPixel, LLC
 */
class SyncPartials
{
	/**
	 * @var array $filePartials
	 */
	private $filePartials;

	/**
	 * @var array $dbPartials
	 */
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
			if (! isset($this->filePartials[$dbKey]) ||
				$this->filePartials[$dbKey]->extension !== 'html'
			) {
				$dbVal->delete();
			}
		}
	}
}
