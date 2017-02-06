<?php

namespace BuzzingPixel\TemplateSync\Service\Data;

use EllisLab\ExpressionEngine\Library\Data\Collection;

/**
 * Class Base
 *
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/software/template-sync
 * @copyright Copyright (c) 2017, BuzzingPixel, LLC
 */
class Base
{
	protected $propertyTypes = array();

	/**
	 * Set magic method
	 *
	 * @param string $name
	 * @param mixed $val
	 */
	public function __set($name, $val)
	{
		if (isset($this->propertyTypes[$name])) {
			$this->{$name} = $val;
		}
	}

	/**
	 * Get magic method
	 *
	 * @param string $name Class variable name
	 * @return mixed
	 */
	public function __get($name)
	{
		if (! isset($this->propertyTypes[$name]) ||
			! isset($this->{$name})
		) {
			return null;
		}

		return $this->{$name};
	}

	/**
	 * Setup properties
	 *
	 * @param array $items
	 */
	protected function setup($items)
	{
		foreach ($items as $key => $val) {
			$this->propertyTypes[$key] = gettype($val);
			$this->{$key} = $val;
		}
	}

	/**
	 * Get variable type
	 *
	 * @param string $name Variable name
	 * @return null|string
	 */
	public function getType($name)
	{
		if (! isset($this->propertyTypes[$name])) {
			return null;
		}

		return $this->propertyTypes[$name];
	}

	/**
	 * Get all properties as an array
	 *
	 * @return array
	 */
	public function asArray()
	{
		$array = array();

		foreach ($this->properties as $key => $val) {
			$array[$key] = $this->{$key};
		}

		return $array;
	}
}
