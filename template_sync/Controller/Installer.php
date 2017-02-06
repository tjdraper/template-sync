<?php

namespace BuzzingPixel\TemplateSync\Controller;

use EllisLab\ExpressionEngine\Core\Provider as EEProvider;
use EllisLab\ExpressionEngine\Model\Addon\Extension as ExtensionRecord;

/**
 * Class Installer
 *
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/software/template-sync
 * @copyright Copyright (c) 2017, BuzzingPixel, LLC
 */
class Installer
{
	// EE App Info
	protected $appInfo;

	/**
	 * Installer constructor
	 *
	 * @param EEProvider $appInfo The extension provider object
	 */
	public function __construct(EEProvider $appInfo)
	{
		$this->appInfo = $appInfo;
	}

	/**
	 * Install Template Sync
	 */
	public function install()
	{
		/** @var ExtensionRecord $extensionRecord */
		$extensionRecord = ee('Model')->make('Extension');

		$extensionRecord->set(array(
			'class' => 'Template_sync_ext',
			'method' => 'sync',
			'hook' => 'core_boot',
			'settings' => '',
			'version' => $this->appInfo->getVersion()
		));

		$extensionRecord->save();
	}

	/**
	 * Uninstall Template Sync
	 */
	public function uninstall()
	{
		/** @var ExtensionRecord $extensionRecord */
		$extensionRecord = ee('Model')->get('Extension')
			->filter('class', 'Template_sync_ext')
			->all();

		$extensionRecord->delete();
	}

	/**
	 * General update routines
	 */
	public function generalUpdate()
	{
		/** @var ExtensionRecord $extensionRecord */
		$extensionRecord = ee('Model')->get('Extension')
			->filter('class', 'Template_sync_ext')
			->all();

		$extensionRecord->setProperty('version', $this->appInfo->getVersion());

		$extensionRecord->save();
	}

	/**
	 * Switch to core_boot hook
	 */
	public function switchToCoreBoot()
	{
		/** @var ExtensionRecord $extensionRecord */
		$extensionRecord = ee('Model')->get('Extension')
			->filter('class', 'Template_sync_ext')
			->first();

		$extensionRecord->setProperty('hook', 'core_boot');

		$extensionRecord->save();
	}
}
