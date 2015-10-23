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
			->with('Templates')
			->order('group_order', 'ASC')
			->all();

		$templateGroups = array();

		// Loop through the template groups
		foreach ($templateGroupsDB as $templateGroup) {
			$id = $templateGroup->group_id;

			// Set the array of properties to the templateGroups array
			$templateGroups[$id] = $templateGroup->toArray();

			// Attach templates
			foreach ($templateGroup->Templates as $templateDB) {
				$template = array(
					'template_id' => $templateDB->template_id,
					'group_id' => $templateDB->group_id,
					'template_name' => $templateDB->template_name,
					'template_type' => $templateDB->template_type
				);

				$templateGroups[$id]['templates'][$templateDB->template_id] = $template;
			}
		}

		return $templateGroups;
	}

	/**
	 * Delete template groups
	 *
	 * @param int|array $ids
	 */
	public function deleteGroups($ids)
	{
		ee('Model')->get('TemplateGroup')
			->filter('group_id', 'IN', $ids)
			->all()
			->delete();
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
	 * Delete templates
	 *
	 * @param int|array $ids
	 */
	public function deleteTemplates($ids)
	{
		ee('Model')->get('Template')
			->filter('template_id', 'IN', $ids)
			->all()
			->delete();
	}

	/**
	 * Update templates
	 *
	 * @param array $updateData
	 */
	public function updateTemplates($updateData)
	{
		$templates = ee('Model')->get('Template')->all();

		$idMap = array();

		foreach ($templates as $key => $val) {
			$idMap[$val->template_id] = $key;
		}

		foreach ($updateData as $data) {
			$tId = $idMap[$data['template_id']];

			unset($data['template_id']);

			foreach ($data as $dKey => $dVal) {
				$templates[$tId]->{$dKey} = $dVal;
			}
		}

		$templates->save();
	}
}