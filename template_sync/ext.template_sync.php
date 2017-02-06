<?php

use EllisLab\ExpressionEngine\Core\Provider as EEProvider;
use BuzzingPixel\TemplateSync\Controller\Installer;
use BuzzingPixel\TemplateSync\Controller\Sync;

/**
 * Class Template_sync_ext
 *
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/software/template-sync
 * @copyright Copyright (c) 2017, BuzzingPixel, LLC
 *
 * @SuppressWarnings(PHPMD.CamelCaseClassName)
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
// @codingStandardsIgnoreStart
class Template_sync_ext
// @codingStandardsIgnoreEnd
{
	/**
	 * @var string $version
	 */
	public $version = TEMPLATE_SYNC_VER;

	/**
	 * @var EEProvider $appInfo
	 */
	protected $appInfo;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->appInfo = ee('App')->get('template_sync');
	}

	/**
	 * Install extension
	 */
	// @codingStandardsIgnoreStart
	public function activate_extension() // @codingStandardsIgnoreEnd
	{
		$installer = new Installer($this->appInfo);
		$installer->install();
	}

	/**
	 * Uninstall extension
	 */
	// @codingStandardsIgnoreStart
	public function disable_extension() // @codingStandardsIgnoreEnd
	{
		$installer = new Installer($this->appInfo);
		$installer->uninstall();
	}

	/**
	 * Update extension
	 *
	 * @param string $current
	 * @return bool
	 */
	// @codingStandardsIgnoreStart
	public function update_extension($current = '') // @codingStandardsIgnoreEnd
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
		if (((defined('ENV') && ENV !== 'prod') || REQ === 'CP') &&
			ee()->config->item('save_tmpl_files') === 'y'
		) {
			$sync = new Sync($this->appInfo);
			$sync->run();
		}
	}
}
