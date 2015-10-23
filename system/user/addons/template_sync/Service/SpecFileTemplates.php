<?php

namespace BuzzingPixel\Addons\TemplateSync\Service;

class SpecFileTemplates
{
	/**
	 * Get spec templates from the file system
	 *
	 * @return array
	 */
	public function get()
	{
		// Load helpers
		$dirArray = ee('template_sync:DirectoryArrayHelper');

		// Set variables
		$templateBasePath = SYSPATH . 'user/templates/';
		$path = $templateBasePath . ee()->config->item('site_short_name') .
			'/spec/';
		$returnFileTemplates = array();

		// Make sure directory exists
		if (! is_dir($path)) {
			mkdir($path);
		}

		// Get templates from file system
		$templates = $dirArray->process($path);

		// Get template names and contents
		foreach ($templates as $template) {
			$pathInfo = pathinfo($template);
			$content = file_get_contents($path . $template);

			$returnFileTemplates[$pathInfo['filename']] = $content;
		}

		return $returnFileTemplates;
	}

	/**
	 * Write spec templates
	 *
	 * @param array $data
	 */
	public function write($data)
	{
		// Set variables
		$templateBasePath = SYSPATH . 'user/templates/';
		$path = $templateBasePath . ee()->config->item('site_short_name') .
			'/spec/';

		foreach ($data as $key => $val) {
			file_put_contents($path . $key . '.html', $val);
		}
	}
}