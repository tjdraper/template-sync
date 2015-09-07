<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Template Sync library
 *
 * @package template_sync
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright Copyright (c) 2015, BuzzingPixel
 */

class Template_sync_lib
{
	// Acceptable template file extensions
	public $templateFileExtensions = array(
		'css',
		'html',
		'js',
		'rss',
		'xml'
	);

	// Type mapping
	public $typeMap = array(
		'css' => 'css',
		'html' => 'webpage',
		'js' => 'js',
		'rss' => 'feed',
		'xml' => 'xml'
	);

	/**
	 * Get template groups and templates from file system
	 *
	 * @return array
	 */
	public function getFileTemplates()
	{
		// Start return array
		$returnFileTemplates = array();

		// Set path
		$path = rtrim(ee()->config->item('tmpl_file_basepath'), '/') . '/' .
			ee()->config->item('site_short_name') . '/';

		// Get template groups from file system
		$templateGroups = $this->dirArray($path);

		foreach ($templateGroups as $templateGroup) {
			// Get the name of the template group
			$templateGroupName = rtrim($templateGroup, '.group');

			// Set the templates for the group to an array
			$finalTemplates = array();

			// Get the templates in this group
			$templates = $this->dirArray($path . $templateGroup . '/');

			foreach ($templates as $template) {
				$templatePathInfo = pathinfo($template);

				if (
					! isset($templatePathInfo['extension']) ||
					! in_array(
						$templatePathInfo['extension'],

						$this->templateFileExtensions
					)
				) {
					continue;
				}

				$finalTemplates[$templatePathInfo['filename']]['name'] = $templatePathInfo['filename'];

				$finalTemplates[$templatePathInfo['filename']]['extension'] =
					$templatePathInfo['extension'];
			}

			$returnFileTemplates[$templateGroupName] = $finalTemplates;
		}

		return $returnFileTemplates;
	}

	/**
	 * Scandir array with directories only (unset first two items)
	 *
	 * @param string $path
	 * @return array
	 */
	public function dirArray($path)
	{
		$dir = scandir($path);

		unset($dir[0]);

		unset($dir[1]);

		return array_values($dir);
	}
}