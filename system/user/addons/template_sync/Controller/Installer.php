<?php

/**
 * Template Sync Installer Controller
 *
 * @package template_sync
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/template-sync
 * @copyright Copyright (c) 2016, BuzzingPixel
 */

namespace BuzzingPixel\TemplateSync\Controller;

class Installer
{
	// EE App Info
	protected $appInfo;

	/**
	 * Installer constructor
	 *
	 * @param $appInfo The extension provider object
	 */
	public function __construct(\EllisLab\ExpressionEngine\Core\Provider $appInfo) {
		$this->appInfo = $appInfo;
	}

	/**
	 * Install Template Sync
	 */
	public function install()
	{
		$extension = ee('Model')->make('Extension');

		$extension->set(array(
			'class' => 'Template_sync_ext',
			'method' => 'sync',
			'hook' => 'sessions_start',
			'settings' => '',
			'version' => $this->appInfo->getVersion()
		));

		$extension->save();
	}

	/**
	 * Uninstall Template Sync
	 */
	public function uninstall()
	{
		$extension = ee('Model')->get('Extension')
			->filter('class', 'Template_sync_ext')
			->all();

		$extension->delete();
	}

	/**
	 * General update routines
	 */
	public function generalUpdate()
	{
		$extension = ee('Model')->get('Extension')
			->filter('class', 'Template_sync_ext')
			->all();

		$extension->version = $this->appInfo->getVersion();

		$extension->save();
	}
}
