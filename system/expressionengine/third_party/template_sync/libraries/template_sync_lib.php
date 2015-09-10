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
		$templateGroups = $this->dirArray($path, true);

		// Make sure items are alphabetical
		sort($templateGroups);

		foreach ($templateGroups as $templateGroup) {
			// Get the name of the template group
			$templateGroupName = rtrim($templateGroup, '.group');

			// Write an index template if it does not exist
			if (! file_exists($path . $templateGroup . '/index.html')) {
				$this->writeIndexTemplate($path . $templateGroup . '/');
			}

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

	public function writeIndexTemplate($groupPath)
	{
		file_put_contents(
			$groupPath . 'index.html',
			'{redirect="404"}'
		);
	}

	/**
	 * Scandir array with directories only (unset first two items)
	 *
	 * @param string $path
	 * @return array
	 */
	public function dirArray($path, $dirOnly = false)
	{
		$dir = scandir($path);

		unset($dir[0]);

		unset($dir[1]);

		if ($dirOnly) {
			foreach ($dir as $key => $val) {
				if (! is_dir($path . $val)) {
					unset($dir[$key]);
				}
			}
		}

		return array_values($dir);
	}
}