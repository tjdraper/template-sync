<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Template Sync extension
 *
 * @package template_sync
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright Copyright (c) 2015, BuzzingPixel
 */

class Template_sync_ext
{
	public $version = TEMPLATE_SYNC_VER;

	protected $info;

	public function __construct()
	{
		$this->info = ee('App')->get('template_sync');
	}

	/**
	 * Activate extension
	 */
	public function activate_extension()
	{
		$extension = ee('Model')->make('Extension');

		$extension->set(array(
			'class' => __CLASS__,
			'method' => 'sync',
			'hook' => 'sessions_start',
			'version' => $this->info->getVersion()
		));

		$extension->save();
	}

	/**
	 * Update extension
	 */
	public function update_extension($current = '')
	{
		if ($current !== $this->info->getVersion()) {
			$extension = ee('Model')->get('Extension')
				->filter('class', __CLASS__)
				->all();

			$extension->version = $this->info->getVersion();

			$extension->save();
		}

		return false;
	}

	/**
	 * Remove extension
	 */
	public function disable_extension()
	{
		$extension = ee('Model')->get('Extension')
			->filter('class', __CLASS__)
			->all();

		$extension->delete();
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
			if (ee()->config->item('template_sync_disable_tmpl_sync') !== 'y') {
				ee('template_sync:SyncTemplatesController')->run();
			}

			if (ee()->config->item('template_sync_disable_spec_sync') !== 'y') {
				ee('template_sync:SyncSpecTemplatesController')->run();
			}
		}
	}
}