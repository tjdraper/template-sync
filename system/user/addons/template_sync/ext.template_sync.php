<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Template Sync extension
 *
 * @package template_sync
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/template-sync
 * @copyright Copyright (c) 2016, BuzzingPixel
 */

use BuzzingPixel\TemplateSync\Controller\Installer;
use BuzzingPixel\TemplateSync\Controller\Sync;

class Template_sync_ext
{
	// Set the version for ExpressionEngine
	public $version = TEMPLATE_SYNC_VER;

	protected $appInfo;

	public function __construct()
	{
		$this->appInfo = ee('App')->get('template_sync');
	}

	/**
	 * Install extension
	 */
	public function activate_extension()
	{
		$installer = new Installer($this->appInfo);
		$installer->install();
	}

	/**
	 * Uninstall extension
	 */
	public function disable_extension()
	{
		$installer = new Installer($this->appInfo);
		$installer->uninstall();
	}

	/**
	 * Update extension
	 */
	public function update_extension($current = '')
	{
		if ($current ===  $this->appInfo->getVersion()) {
			return false;
		}

		$installer = new Installer($this->appInfo);
		$installer->generalUpdate();

		return true;
	}

	/**
	 * Sync templates (sessions_start)
	 */
	public function sync()
	{
		// Check to see if we should be syncing templates
		if (
			((defined('ENV') && ENV !== 'prod') || REQ === 'CP') &&
			ee()->config->item('save_tmpl_files') === 'y'
		) {
			$sync = new Sync($this->appInfo);
			$sync->run();
		}
	}
}
