<?php

namespace BuzzingPixel\TemplateSync\Controller;

use EllisLab\ExpressionEngine\Core\Provider as EEProvider;
use BuzzingPixel\TemplateSync\Service\Data\FileSpecTemplates;
use BuzzingPixel\TemplateSync\Service\Data\DbSpecTemplates;
use BuzzingPixel\TemplateSync\Service\SyncSpecTemplates;
use BuzzingPixel\TemplateSync\Service\Data\FileTemplates;
use BuzzingPixel\TemplateSync\Service\FileTemplateIndexes;
use BuzzingPixel\TemplateSync\Service\Data\DbTemplates;
use BuzzingPixel\TemplateSync\Service\SyncPartials;
use BuzzingPixel\TemplateSync\Service\SyncVariables;
use BuzzingPixel\TemplateSync\Service\SyncTemplates;

/**
 * Class Sync
 *
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/software/template-sync
 * @copyright Copyright (c) 2017, BuzzingPixel, LLC
 */
class Sync
{
	// EE App Info
	protected $appInfo;

	/**
	 * Installer constructor
	 *
	 * @param EEProvider $appInfo The extension provider object
	 */
	public function __construct(EEProvider $appInfo)
	{
		$this->appInfo = $appInfo;
	}

	/**
	 * Run syncing
	 */
	public function run()
	{
		// Sync specialty templates
		$syncSpecTemplates = new SyncSpecTemplates(
			new FileSpecTemplates(),
			new DbSpecTemplates()
		);
		$syncSpecTemplates->sync();

		// Get the file templates
		$fileTemplates = new FileTemplates();

		// Write index files if necessary
		$fileTemplateIndexes = new FileTemplateIndexes($fileTemplates);
		$fileTemplateIndexes->write();

		// Get the DB templates
		$dbTemplates = new DbTemplates();

		// Loop through the DB templates
		foreach ($dbTemplates as $dbTemplateKey => $dbTemplateGroup) {
			// Sync partials
			if ($dbTemplateKey === '_partials') {
				$syncPartials = new SyncPartials(
					$fileTemplates->{$dbTemplateKey},
					$dbTemplateGroup
				);
				$syncPartials->sync();

			// Sync variables
			} elseif ($dbTemplateKey === '_variables') {
				// Make sure this is not a Low Variables page, because we can
				// really screw up Low Variables
				if (! in_array('low_variables', ee()->uri->segments)) {
					$syncVariables = new SyncVariables(
						$fileTemplates->{$dbTemplateKey},
						$dbTemplateGroup
					);
					$syncVariables->sync();
				}

			// Sync template groups and templates
			} else {
				$syncTemplates = new SyncTemplates(
					$dbTemplateKey,
					$fileTemplates->{$dbTemplateKey},
					$dbTemplateGroup
				);
				$syncTemplates->sync();
			}
		}
	}
}
