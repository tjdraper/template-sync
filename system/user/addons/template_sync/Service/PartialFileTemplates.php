<?php

namespace BuzzingPixel\Addons\TemplateSync\Service;

class PartialFileTemplates
{
	/**
	 * Get partial file templates from file system
	 *
	 * @return array
	 */
	public function get($path)
	{
		// Load helpers
		$dirArray = ee('template_sync:DirectoryArrayHelper');

		// Set variables
		$templateBasePath = SYSPATH . 'user/templates/';
		$path = $templateBasePath . ee()->config->item('site_short_name') .
			'/' . $path . '/';
		$returnFileTemplates = array();

		// Make sure directory exists
		if (! is_dir($path)) {
			mkdir($path);
		}

		// Get templates from file system
		$templates = $dirArray->process($path);

		// Get template names and contents
		foreach ($templates as $template) {
			// Make sure we only include files
			if (is_dir($path . $template)) {
				continue;
			}

			$pathInfo = pathinfo($template);
			$content = file_get_contents($path . $template);

			// Make sure hidden files (starting with .) are not included
			if ($pathInfo['filename']) {
				$returnFileTemplates[$pathInfo['filename']] = $content;
			}
		}

		return $returnFileTemplates;
	}
}