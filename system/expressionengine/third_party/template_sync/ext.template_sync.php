<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

// Include configuration
include_once(PATH_THIRD . 'template_sync/addon.setup.php');

/**
 * Template Sync extension
 *
 * @package template_sync
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright Copyright (c) 2015, BuzzingPixel
 */

class Template_sync_ext
{
	public $name = TEMPLATE_SYNC_NAME;
	public $version = TEMPLATE_SYNC_VER;
	public $description = TEMPLATE_SYNC_DESC;
	public $docs_url = '';
	public $settings_exist = 'n';

	/**
	 * Activate extension
	 */
	public function activate_extension()
	{
		ee()->db->insert('extensions', array(
			'class' => __CLASS__,
			'method' => 'sync_templates',
			'hook' => 'sessions_start',
			'settings' => '',
			'priority' => 10,
			'version' => $this->version,
			'enabled' => 'y'
		));
	}

	/**
	 * Update extension
	 */
	public function update_extension($current = '')
	{
		if ($current !== $this->version) {
			ee()->db->where('class', __CLASS__);
			ee()->db->update('extensions', array(
				'version' => $this->version
			));

			return true;
		}

		return false;
	}

	/**
	 * Remove extension
	 */
	public function disable_extension()
	{
		ee()->db->where('class', __CLASS__);
		ee()->db->delete('extensions');
	}

	/**
	 * Sync templates (sessions_start)
	 */
	public function sync_templates()
	{
		// Get the URI segments
		$segments = ee()->uri->segment_array();

		// Check to see if we should be syncing templates
		if (
			(   // If ENV is defined and not production
				(defined('ENV') && ENV !== 'prod') ||
				// Or this is the control panel
				(isset($segments[1]) && $segments[1] === 'cp')
			) &&
			// Make sure template syncing is turned on
			ee()->config->item('save_tmpl_files') === 'y'
		) {
			$this->proceedWithSync();
		}
	}

	/**
	 * Proceed with sync
	 */
	private function proceedWithSync()
	{
		ee()->load->model('template_sync_model');
		ee()->load->library('template_sync_lib');

		$model = ee()->template_sync_model;
		$lib = ee()->template_sync_lib;

		// Get the file templates from the file system
		$fileTemplates = $lib->getFileTemplates();

		// Get the templates from the database
		$dbTemplates = $model->getDbTemplates();

		// Get the typeMap array to map file extensions to template type
		$typeMap = $lib->typeMap;

		// Start arrays
		$groupDelete = array();

		$templateDelete = array();

		$update = array();

		$i = 0;

		// Loop through the database template groups
		foreach ($dbTemplates as $dbTemplate) {
			// If the template group is not in the file system, add group to
			// delete array and go to next loop iteration
			if (! isset($fileTemplates[$dbTemplate['group_name']])) {
				$groupDelete[] = $dbTemplate['group_id'];

				continue;
			}

			// Set the file templates for this group to variable
			$fileGroupTemplates = $fileTemplates[$dbTemplate['group_name']];

			// Loop through the templates in this group
			foreach ($dbTemplate['templates'] as $template) {
				// If the template is not in file system, add template to delete
				// array and go to next loop iteration
				if (! isset($fileGroupTemplates[$template['template_name']])) {
					$templateDelete[] = $template['template_id'];

					continue;
				}

				// Set the file system template type
				$ext = $fileGroupTemplates[$template['template_name']]['extension'];

				$fileType = $typeMap[$ext];

				// Add the template type to the update array if not a match
				if ($template['template_type'] !== $fileType) {
					$update[$i]['template_id'] = $template['template_id'];

					$update[$i]['template_type'] = $typeMap[$ext];

					$i++;
				}
			}
		}

		// Send template groups to the model for deletion
		if ($groupDelete) {
			$model->deleteTemplateGroups($groupDelete);
		}

		// Send templates to the model for deletion
		if ($templateDelete) {
			$model->deleteTemplates($templateDelete);
		}

		// Send updates to the model
		if ($update) {
			$model->updateTemplates($update);
		}

		/**
		 * Check if DB template group order is alphabetical and update
		 * if necessary
		 */

		// Set the order of the file template groups
		$fileGroupOrder = array();
		foreach ($fileTemplates as $key => $val) {
			$fileGroupOrder[] = $key;
		}

		// Set the current order of the DB templates
		$dbGroupOrder = array();
		foreach ($dbTemplates as $dbGroup) {
			$dbGroupOrder[] = $dbGroup['group_name'];
		}

		// Check the order
		foreach ($fileGroupOrder as $key => $fileOrder) {
			// If the template names do not match, the DB templates are out
			// of order
			if ((isset($dbGroupOrder[$key]) && $dbGroupOrder[$key]) !== $fileOrder) {
				$i = 0;

				$order = array();

				// Set the correct order
				foreach ($fileTemplates as $fileGroup => $temp) {
					foreach ($dbTemplates as $dbGroup) {
						if ($dbGroup['group_name'] === $fileGroup) {
							$order[$i]['group_id'] = $dbGroup['group_id'];

							$order[$i]['group_order'] = $i + 1;

							$i++;

							break;
						}
					}
				}

				$model->updateTemplateGroups($order);

				break;
			}
		}
	}
}