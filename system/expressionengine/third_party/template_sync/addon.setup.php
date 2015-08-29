<?php

if (! defined('TEMPLATE_SYNC_NAME')) {
	define('TEMPLATE_SYNC_NAME', 'Template Sync');
	define('TEMPLATE_SYNC_VER', '1.0.0-b.1');
	define('TEMPLATE_SYNC_AUTHOR', 'TJ Draper');
	define('TEMPLATE_SYNC_AUTHOR_URL', 'https://buzzingpixel.com');
	define('TEMPLATE_SYNC_DESC', 'Delete templates not in the file system');
	define('TEMPLATE_SYNC_PATH', PATH_THIRD . 'template_sync/');
}

$config['name'] = TEMPLATE_SYNC_NAME;
$config['version'] = TEMPLATE_SYNC_VER;

return array(
	'name' => TEMPLATE_SYNC_NAME,
	'version' => TEMPLATE_SYNC_VER,
	'author' => TEMPLATE_SYNC_AUTHOR,
	'author_url' => TEMPLATE_SYNC_AUTHOR_URL,
	'description' => TEMPLATE_SYNC_DESC,
	'namespace' => 'BuzzingPixel\TemplateSync'
);