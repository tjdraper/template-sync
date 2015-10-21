<?php

namespace BuzzingPixel\Addons\TemplateSync\Library;

class FileTemplateExtensions
{
	// Type mapping
	private $typeMap = array(
		'css' => 'css',
		'html' => 'webpage',
		'js' => 'js',
		'rss' => 'feed',
		'xml' => 'xml'
	);

	/**
	 * Get file type extensions
	 *
	 * @return array
	 */
	public function getExtensions()
	{
		return array_keys($this->typeMap);
	}

	/**
	 * Get the type map
	 *
	 * @return array
	 */
	public function getTypeMap()
	{
		return $this->typeMap;
	}
}