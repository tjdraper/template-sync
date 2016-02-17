<?php

/**
 * Template Sync DbSpecTemplates Service
 *
 * @package template_sync
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/template-sync
 * @copyright Copyright (c) 2016, BuzzingPixel
 */

namespace BuzzingPixel\TemplateSync\Service\Data;

class DbSpecTemplates extends Base
{
	public function __construct()
	{
		// Get specialty templates
		$specTemplatesDb = ee('Model')->get('SpecialtyTemplate')->all();

		// Start an array
		$specTemplates = array();

		foreach ($specTemplatesDb as $specTemplate) {
			$specTemplates[$specTemplate->template_name] = $specTemplate;
		}

		$this->setup($specTemplates);
	}
}
