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

		// Get the installer
		$installer = new Installer($this->appInfo);

		// Check version
		if (version_compare($current, '1.1.0', '<')) {
			$installer->switchToCoreBoot();
		}

		// Run general update routines
		$installer->generalUpdate();

		// We're done here
		return true;
	}

	/**
	 * Sync templates (sessions_start)
	 *
	 * @param object $isSession
	 */
	public function sync($isSession = null)
	{
		// If we are being passed a session object, we're on the wrong hook
		// and we’ll throw errors so we shouldn’t do that
		if ($isSession) {
			return;
		}

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
