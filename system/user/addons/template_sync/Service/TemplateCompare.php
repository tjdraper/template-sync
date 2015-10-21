<?php

namespace BuzzingPixel\Addons\TemplateSync\Service;

class TemplateCompare
{
	/*
	 * Compare database and file templates
	 *
	 * @param array $dbTemplates
	 * @param $fileTemplates
	 * @return array
	 */
	public function run($dbTemplates, $fileTemplates)
	{
		// Load Libraries
		$extensionsLib = ee('template_sync:FileTemplateExtensionsLib');

		// Set variables
		$typeMap = $extensionsLib->getTypeMap();
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

		return array(
			'groupDelete' => $groupDelete,
			'templateDelete' => $templateDelete,
			'update' => $update
		);
	}

	/**
	 * Set template group order
	 *
	 * @param array $dbTemplates
	 * @param $fileTemplates
	 * @return array
	 */
	public function order($dbTemplates, $fileTemplates)
	{
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

		$order = array();

		// Check the order
		foreach ($fileGroupOrder as $key => $fileOrder) {
			// If the template names do not match, the DB templates are out
			// of order
			if (isset($dbGroupOrder[$key]) && $dbGroupOrder[$key] !== $fileOrder) {
				$i = 0;

				// Set the correct order
				foreach ($fileTemplates as $fileGroup => $temp) {
					foreach ($dbTemplates as $dbGroup) {
						if ($dbGroup['group_name'] === $fileGroup) {
							$groupId = $dbGroup['group_id'];

							$order[$groupId]['group_id'] = $groupId;

							$order[$groupId]['group_order'] = $i + 1;

							$i++;

							break;
						}
					}
				}

				break;
			}
		}

		return $order;
	}
}