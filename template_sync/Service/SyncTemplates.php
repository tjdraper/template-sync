<?php

namespace BuzzingPixel\TemplateSync\Service;

/**
 * Class SyncTemplates
 *
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/software/template-sync
 * @copyright Copyright (c) 2017, BuzzingPixel, LLC
 */
class SyncTemplates
{
	private $groupName;
	private $groupModel;
	private $fileTemplates;
	private $dbTemplates;

	/**
	 * SyncTemplateGroupsTemplates constructor
	 *
	 * @param string $groupName
	 * @param array $fileTemplates
	 * @param array $dbTemplates
	 */
	public function __construct($groupName, $fileTemplates, $dbTemplates)
	{
		$this->groupName = $groupName;
		$this->fileTemplates = $fileTemplates;
		$this->groupModel = $dbTemplates['model'];
		unset($dbTemplates['model']);
		$this->dbTemplates = $dbTemplates;
	}

	/**
	 * Sync the template groups and templates database with the filesystem
	 */
	public function sync()
	{
		// If template group is not in file system, delete from db
		if (! $this->fileTemplates) {
			$this->groupModel->delete();

			return;
		}

		// Loop through the template groups
		foreach ($this->dbTemplates as $dbKey => $dbVal) {
			$fileTemplate = isset($this->fileTemplates[$dbKey]) ?
				$this->fileTemplates[$dbKey] : false;

			// If template is not in file system, delete from db
			if (! $fileTemplate) {
				$dbVal->delete();

				continue;
			}

			// If template types do not match, update DB
			if ($fileTemplate->type !== $dbVal->template_type) {
				$dbVal->template_type = $fileTemplate->type;
				$dbVal->save();
			}
		}
	}
}
