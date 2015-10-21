<?php

namespace BuzzingPixel\Addons\TemplateSync\Service;

class FileTemplates
{
	/**
	 * Get templates from the file system
	 *
	 * @return array
	 */
	public function get()
	{
		// Load Libraries
		$extensionsLib = ee('template_sync:FileTemplateExtensionsLib');

		// Load helpers
		$dirArray = ee('template_sync:DirectoryArrayHelper');

		// Set variables
		$templateBasePath = SYSPATH . 'user/templates/';
		$path = $templateBasePath . ee()->config->item('site_short_name') . '/';
		$fileExtensions = $extensionsLib->getExtensions();
		$returnFileTemplates = array();

		// Get template groups from file system
		$templateGroups = $dirArray->process($path, true);

		// Make sure items are alphabetical
		sort($templateGroups);

		// Loop through template groups
		foreach ($templateGroups as $templateGroup) {
			$groupPathInfo = pathinfo($templateGroup);

			// Make sure .group is present
			if (! isset($groupPathInfo['extension']) ||
				$groupPathInfo['extension'] !== 'group'
			) {
				continue;
			}

			// Get the name of the template group
			$templateGroupName = $groupPathInfo['filename'];

			// Write an index template if it does not exist
			if (! file_exists($path . $templateGroup . '/index.html')) {
				file_put_contents(
					$path . $templateGroup . '/' . 'index.html',
					'{redirect="404"}'
				);
			}

			// Set the templates for the group to an array
			$finalTemplates = array();

			// Get the templates in this group
			$templates = $dirArray->process($path . $templateGroup . '/');

			// Process the templates
			foreach ($templates as $template) {
				$templatePathInfo = pathinfo($template);

				// Make sure the extension maps up
				if (
					! isset($templatePathInfo['extension']) ||
					! in_array($templatePathInfo['extension'], $fileExtensions)
				) {
					continue;
				}

				// Set the final template variables
				$finalTemplates[$templatePathInfo['filename']]['name'] = $templatePathInfo['filename'];

				$finalTemplates[$templatePathInfo['filename']]['extension'] =
					$templatePathInfo['extension'];
			}

			// Set this template to the return variable
			$returnFileTemplates[$templateGroupName] = $finalTemplates;
		}

		return $returnFileTemplates;
	}
}