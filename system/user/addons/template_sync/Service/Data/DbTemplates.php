<?php

/**
 * Template Sync DbTemplates Service
 *
 * @package template_sync
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/template-sync
 * @copyright Copyright (c) 2016, BuzzingPixel
 */

namespace BuzzingPixel\TemplateSync\Service\Data;

class DbTemplates extends Base
{
	/**
	 * DbTemplates constructor
	 */
	public function __construct()
	{
		// Get template partials
		$templatePartialsDb = ee('Model')->get('Snippet')->all();

		// Get template variables
		$templateVariablesDb = ee('Model')->get('GlobalVariable')->all();

		// Get templates and groups
		$templateGroupsDb = ee('Model')->get('TemplateGroup')
			->with('Templates')
			->order('group_order', 'ASC')
			->all();

		// Start an array
		$templateGroups = array();

		// Loop through the template partials
		foreach ($templatePartialsDb as $partial) {
			$templateGroups['_partials'][$partial->snippet_name] = $partial;
		}

		// Loop through the template variables
		foreach ($templateVariablesDb as $var) {
			$templateGroups['_variables'][$var->variable_name] = $var;
		}

		// Loop through the template groups
		foreach ($templateGroupsDb as $templateGroup) {
			$templateGroups[$templateGroup->group_name]['model'] = $templateGroup;

			foreach ($templateGroup->Templates as $template) {
				$templateGroups[$templateGroup->group_name][$template->template_name] = $template;
			}
		}

		$this->setup($templateGroups);
	}
}
