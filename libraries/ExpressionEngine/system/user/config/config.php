<?php



// Custom configs

// Domain and protocol logic
$secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
$protocol = $secure ? 'https://' : 'http://';

// Dynamic path settings
$baseUrl = $protocol . $_SERVER['SERVER_NAME'];
$basePath = $_SERVER['DOCUMENT_ROOT'];
$imagesFolder = 'images';
$imagesPath = $basePath . DIRECTORY_SEPARATOR . $imagesFolder;
$imagesUrl = $baseUrl . '/' . $imagesFolder;
$uploadsPath = $basePath . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;

$config['site_index'] = '';
$config['site_url'] = $baseUrl;
$config['cp_url'] = $baseUrl . '/admin.php';
$config['theme_folder_path'] = $basePath . '/themes/';
$config['theme_folder_url'] = $baseUrl . '/themes/';
$config['captcha_path'] = $imagesPath . '/captchas/';
$config['captcha_url'] = $imagesUrl . '/captchas/';
$config['avatar_path'] = $imagesPath . '/avatars/';
$config['avatar_url'] = $imagesUrl . '/avatars/';

// Cookie & session settings
$config['cookie_domain'] = '';
$config['cookie_httponly'] = 'y';
$config['cookie_path'] = '';
$config['website_session_type'] = 'c';

// Template settings
$config['save_tmpl_files'] = 'y';
$config['enable_template_routes'] = 'n';

// Tracking & performance settings
$config['disable_all_tracking'] = 'y';
$config['enable_hit_tracking'] = 'n';
$config['log_referrers'] = 'n';
$config['autosave_interval_seconds'] = '0';

// Control Panel
$config['cp_session_type'] = 'c';
$config['rte_enabled'] = 'n';

// General settings
$config['is_system_on'] = 'y';
$config['allow_extensions'] = 'y';
$config['profile_trigger'] = '7289824634';
$config['use_category_name'] = 'n';
$config['reserved_category_word'] = '';
$config['enable_emoticons'] = 'n';
$config['site_404'] = 'site/_404';
$config['encryption_key'] = 'e302cd3ee6865497c6514985daaa49de40914602';

// END Custom config

$config['is_system_on'] = 'y';
$config['multiple_sites_enabled'] = 'n';
// ExpressionEngine Config Items
// Find more configs and overrides at
// https://docs.expressionengine.com/latest/general/system_configuration_overrides.html

$config['app_version'] = '3.5.2';
$config['database'] = array(
	'expressionengine' => array(
		'hostname' => 'localhost',
		'database' => 'site',
		'username' => 'root',
		'password' => 'root',
		'dbprefix' => 'exp_',
		'port'     => ''
	),
);

// EOF
