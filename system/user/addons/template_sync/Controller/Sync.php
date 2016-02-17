<?php

/**
 * Template Sync Sync Controller
 *
 * @package template_sync
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/template-sync
 * @copyright Copyright (c) 2016, BuzzingPixel
 */

namespace BuzzingPixel\TemplateSync\Controller;

use BuzzingPixel\TemplateSync\Service\Data\FileTemplates;
use BuzzingPixel\TemplateSync\Service\Data\DbTemplates;
use BuzzingPixel\TemplateSync\Service\SyncPartials;
use BuzzingPixel\TemplateSync\Service\SyncVariables;
use BuzzingPixel\TemplateSync\Service\SyncTemplates;

class Sync
{
	// EE App Info
	protected $appInfo;

	/**
	 * Installer constructor
	 *
	 * @param $appInfo The extension provider object
	 */
	public function __construct(\EllisLab\ExpressionEngine\Core\Provider $appInfo) {
		$this->appInfo = $appInfo;
	}

	/**
	 * Run syncing
	 */
	public function run()
	{
		// Get the file templates
		$fileTemplates = new FileTemplates();

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
				$syncVariables = new SyncVariables(
					$fileTemplates->{$dbTemplateKey},
					$dbTemplateGroup
				);
				$syncVariables->sync();

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
