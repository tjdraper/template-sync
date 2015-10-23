<?php

namespace BuzzingPixel\Addons\TemplateSync\Service;

class DbSpecTemplates
{
	/**
	 * Get templates from the database
	 *
	 * @return array
	 */
	public function sync($specFileTemplates)
	{
		// Set variables
		$templates = ee('Model')->get('SpecialtyTemplate')->all();
		$returnTemplates = array();

		// Check if sync is needed and set template data
		$sync = false;

		foreach ($templates as $key => $template) {
			// Check if sync to DB is needed
			if (isset($specFileTemplates[$template->template_name])) {
				if ($template->template_data !== $specFileTemplates[$template->template_name]) {
					$sync = true;

					$templates[$key]->template_data = $specFileTemplates[$template->template_name];
				}
			} else {
				$returnTemplates[$template->template_name] = $template->template_data;
			}
		}

		if ($sync) {
			$templates->save();
		}

		return $returnTemplates;
	}
}