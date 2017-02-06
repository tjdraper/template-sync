<?php

/**
 * Template Sync FileTemplates Service
 *
 * @package template_sync
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/template-sync
 * @copyright Copyright (c) 2016, BuzzingPixel
 */

namespace BuzzingPixel\TemplateSync\Service\Data;

use BuzzingPixel\TemplateSync\Library\FileTemplateExtensions;
use BuzzingPixel\TemplateSync\Helper\DirArray;

class FileTemplates extends Base
{
	/**
	 * FileTemplates constructor
	 */
	public function __construct()
	{
		// Start an array for the templates
		$finalFileTemplates = array();

		// Set the template path
		$templateBasePath = SYSPATH . 'user/templates/';
		$path = $templateBasePath . ee()->config->item('site_short_name') . '/';

		// Get the file extensions
		$fileExtensions = FileTemplateExtensions::getExtensions();
		$templateTypes = FileTemplateExtensions::getTypeMap();

		// Get template groups, partials, and variables from file system
		$templateGroups = DirArray::directories($path);

		// Loop through template groups
		foreach ($templateGroups as $group) {
			$pathInf = pathinfo($group);
			$name = $pathInf['filename'];
			$ext = isset($pathInf['extension']) ? $pathInf['extension'] : false;

			// Make sure .group is present, or _partials or _variables
			if (
				$ext !== 'group' &&
				$group !== '_partials' &&
				$group !== '_variables'
			) {
				continue;
			}

			// Get the templates in this group
			$templates = DirArray::files($path . $group);

			// Make sure there is an index template
			if ($name !== '_partials' && $name !== '_variables') {
				// Start by assuming index template does not exist
				$indexTemplateExists = false;

				// Now find out if it does
				foreach ($fileExtensions as $ext) {
					if (in_array("index.{$ext}", $templates)) {
						$indexTemplateExists = true;
						break;
					}
				}

				// If index template does not exist
				if (! $indexTemplateExists) {
					$templates[] = 'index.html';
				}
			}

			// Process the templates
			foreach ($templates as $template) {
				$templatePathInf = pathinfo($template);
				$templateName = $templatePathInf['filename'];
				$templateExt = isset($templatePathInf['extension']) ?
					$templatePathInf['extension'] : false;

				// Make sure the extension maps up
				if (! in_array($templateExt, $fileExtensions)) {
					continue;
				}

				$template = new FileTemplate();

				$template->setup(array(
					'group' => $group,
					'name' => $templateName,
					'extension' => $templateExt,
					'type' => $templateTypes[$templateExt]
				));

				$finalFileTemplates[$name][$templateName] = $template;
			}
		}

		$this->setup($finalFileTemplates);
	}
}
