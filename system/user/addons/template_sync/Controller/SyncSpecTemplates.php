<?php

namespace BuzzingPixel\Addons\TemplateSync\Controller;

class SyncSpecTemplates
{
	/**
	 * Sync templates
	 */
	public function run()
	{
		// Set variables
		$specFileService = ee('template_sync:SpecFileTemplatesService');

		// Get file templates
		$specFileTemplates = $specFileService->get();

		// Sync the file templates to the database
		$writeSpecTemplates = ee('template_sync:DbSpecTemplatesService')->sync(
			$specFileTemplates
		);

		// Write templates not in file system
		if ($writeSpecTemplates) {
			$specFileService->write($writeSpecTemplates);
		}
	}
}