<?php

namespace BuzzingPixel\Addons\TemplateSync\Controller;

class SyncTemplates
{
	/**
	 * Sync templates
	 */
	public function run()
	{
		// Load Services
		$fileTmplService = ee('template_sync:FileTemplatesService');
		$dbTmplService = ee('template_sync:DbTemplatesService');
		$compareService = ee('template_sync:TemplateCompareService');

		// Set variables
		$fileTemplates = $fileTmplService->get();
		$dbTemplates = $dbTmplService->get();
		$prepedTemplates = $compareService->run($dbTemplates, $fileTemplates);
		$order = $compareService->order($dbTemplates, $fileTemplates);

		// Delete template groups
		if ($prepedTemplates['groupDelete']) {
			$dbTmplService->deleteGroups($prepedTemplates['groupDelete']);
		}

		// Delete templates
		if ($prepedTemplates['templateDelete']) {
			$dbTmplService->deleteTemplates($prepedTemplates['templateDelete']);
		}

		// Update templates
		if ($prepedTemplates['update']) {
			$dbTmplService->updateTemplates($prepedTemplates['update']);
		}

		// Update template group order if applicable
		if ($order) {
			$dbTmplService->updateTemplateGroups($order);
		}
	}
}