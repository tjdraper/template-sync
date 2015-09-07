<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Template Sync model
 *
 * @package template_sync
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright Copyright (c) 2015, BuzzingPixel
 */

class Template_sync_model extends CI_Model
{
	/**
	 * Get database templates
	 *
	 * @return array
	 */
	public function getDbTemplates()
	{
		$templateGroupsQuery = ee()->db->select('group_id, group_name')
			->from('template_groups')
			->order_by('group_order', 'asc')
			->get()
			->result_array();

		$templateGroups = array();

		foreach ($templateGroupsQuery as $key => $templateGroup) {
			$templateGroups[$templateGroup['group_id']] = $templateGroup;

			$templateGroups[$templateGroup['group_id']]['templates'] = array();
		}

		$templates = ee()->db->select(array(
				'template_id',
				'group_id',
				'template_name',
				'template_type'
			))
			->from('templates')
			->get()
			->result_array();

		foreach ($templates as $template) {
			$templateGroups[$template['group_id']]['templates'][$template['template_id']] = $template;
		}

		return $templateGroups;
	}

	/**
	 * Delete template groups
	 *
	 * @param array $groupIds
	 */
	public function deleteTemplateGroups($groupDelete = false)
	{
		if (! $groupDelete) {
			return;
		}

		ee()->db->where_in('group_id', $groupDelete);

		ee()->db->delete(array(
			'templates',
			'template_groups'
		));
	}

	/**
	 * Delete templates
	 *
	 * @param array $templateIds
	 */
	public function deleteTemplates($templateIds = false)
	{
		if (! $templateIds) {
			return;
		}

		ee()->db->where_in('template_id', $templateIds);

		ee()->db->delete('templates');
	}

	/**
	 * Update templates
	 *
	 * @param array $updateData
	 */
	public function updateTemplates($updateData = array())
	{
		if (! $updateData) {
			return;
		}

		ee()->db->update_batch('templates', $updateData, 'template_id');
	}

	/**
	 * Update template groups
	 *
	 * @param array $updateData
	 */
	public function updateTemplateGroups($updateData)
	{
		if (! $updateData) {
			return;
		}

		ee()->db->update_batch('template_groups', $updateData, 'group_id');
	}
}