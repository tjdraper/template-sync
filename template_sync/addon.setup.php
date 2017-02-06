<?php // @codingStandardsIgnoreStart

// @codingStandardsIgnoreEnd

// Get addon json path
$addonJsonPath = realpath(dirname(__FILE__));
$addonJsonPath .= '/addon.json';

// Get the addon json file
$addonJson = json_decode(file_get_contents($addonJsonPath));

defined('TEMPLATE_SYNC_VER') || define('TEMPLATE_SYNC_VER', $addonJson->version);

return array(
	'author' => $addonJson->author,
	'author_url' => $addonJson->authorUrl,
	'description' => $addonJson->description,
	'docs_url' => $addonJson->docsUrl,
	'name' => $addonJson->label,
	'namespace' => $addonJson->namespace,
	'settings_exist' => $addonJson->settingsExist,
	'version' => $addonJson->version,
);
