<?php

defined('TEMPLATE_SYNC_VER') || define('TEMPLATE_SYNC_VER', '1.0.0-b.6');

return array(
	'name' => 'Template Sync',
	'version' => TEMPLATE_SYNC_VER,
	'author' => 'TJ Draper',
	'author_url' => 'https://buzzingpixel.com',
	'description' => 'Provide full, two way sync for templates in the database and filesystem',
	'namespace' => 'BuzzingPixel\Addons\TemplateSync',
	'services' => array(
		// Controllers
		'SyncTemplatesController' => 'Controller\SyncTemplates',
		'SyncSpecTemplatesController' => 'Controller\SyncSpecTemplates',
		'SyncPartialsController' => 'Controller\SyncPartials',

		// Services
		'FileTemplatesService' => 'Service\FileTemplates',
		'DbTemplatesService' => 'Service\DbTemplates',
		'TemplateCompareService' => 'Service\TemplateCompare',
		'SpecFileTemplatesService' => 'Service\SpecFileTemplates',
		'DbSpecTemplatesService' => 'Service\DbSpecTemplates',
		'PartialFileTemplatesService' => 'Service\PartialFileTemplates',
		'SyncPartialsService' => 'Service\SyncPartials',

		// Libraries
		'FileTemplateExtensionsLib' => 'Library\FileTemplateExtensions',

		// Helpers
		'DirectoryArrayHelper' => 'Helper\DirectoryArray'
	)
);