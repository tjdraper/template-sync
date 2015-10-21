<?php

namespace BuzzingPixel\Addons\TemplateSync\Service;

class DbTemplates
{
	/**
	 * Get templates from the database
	 *
	 * @return array
	 */
	public function get()
	{
		// Set variables
		$templateGroupsDB = ee('Model')->get('TemplateGroup')
			->order('group_order', 'ASC')
			->all();
		$templateGroups = array();

		// Loop through the template groups
		foreach ($templateGroupsDB as $templateGroup) {
			$id = $templateGroup->group_id;

			// Set the array of properties to the templateGroups array
			$templateGroups[$id] = $templateGroup->toArray();

			// This Model is not currently working. Waiting on EllisLab to work
			// it out
			// var_dump($templateGroup->Templates);
		}

		// Temp, get templates the old way because the model is not working
		$templateGroups = $this->attachTemplates($templateGroups);

		return $templateGroups;
	}

	/**
	 * Delete template groups
	 *
	 * @param int|array $ids
	 */
	public function deleteGroups($ids)
	{
		// The model is currently broken
		//$templateGroups = ee('Model')->get('TemplateGroup')
		//	->filter('group_id', 'IN', $ids)
		//	->all();
		//
		//$templateGroups->delete();

		ee()->db->where_in('group_id', $ids);

		ee()->db->delete(array(
			'templates',
			'template_groups'
		));
	}

	/**
	 * Update template groups
	 *
	 * @param array $data
	 */
	public function updateTemplateGroups($data)
	{
		$templateGroups = ee('Model')->get('TemplateGroup')->all();

		foreach ($templateGroups as $key => $templateGroup) {
			$templateGroups[$key]->group_order =
				$data[$templateGroup->group_id]['group_order'];
		}

		$templateGroups->save();
	}

	/**
	 * Delete templates (update to use EE Model when EllisLab fixes the model)
	 *
	 * @param int|array $ids
	 */
	public function deleteTemplates($ids)
	{
		ee()->db->where_in('template_id', $ids);
		ee()->db->delete('templates');
	}

	/**
	 * Update templates (update to use EE Model when EllisLab fixes the model)
	 *
	 * @param array $updateData
	 */
	public function updateTemplates($updateData)
	{
		ee()->db->update_batch('templates', $updateData, 'template_id');
	}

	/**
	 * Temporary function to get templates for template groups
	 *
	 * @param $templateGroups
	 * @return array
	 */
	private function attachTemplates($templateGroups)
	{
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
}