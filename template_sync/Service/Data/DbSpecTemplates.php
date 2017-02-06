<?php

namespace BuzzingPixel\TemplateSync\Service\Data;

/**
 * Class DbSpecTemplates
 *
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/software/template-sync
 * @copyright Copyright (c) 2017, BuzzingPixel, LLC
 */
class DbSpecTemplates extends Base
{
	/**
	 * Constructor
	 */
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
