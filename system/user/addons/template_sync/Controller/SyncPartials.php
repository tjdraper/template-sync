<?php

namespace BuzzingPixel\Addons\TemplateSync\Controller;

class SyncPartials
{
	/**
	 * Sync partials
	 */
	public function run()
	{
		$partialFileService = ee('template_sync:PartialFileTemplatesService');
		$partialSyncService = ee('template_sync:SyncPartialsService');

		// Get partials
		$partials = $partialFileService->get('partials');

		// Get variables
		$variables = $partialFileService->get('variables');

		// Sync partials
		$partialSyncService->run('Snippet', $partials);

		// Sync variables
		$partialSyncService->run('GlobalVariable', $variables);
	}
}