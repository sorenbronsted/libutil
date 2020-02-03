<?php
namespace sbronsted;

use RuntimeException;

/**
 * Class Config2 is a wrapper for php ini file functions. It uses magic get and set method to access the values
 * from the ini file.
 *Usage:
 *```
 * $config = new Config2('/path/to/config/file.ini');
 * $value = $config->section_name; // read a value
 * $config->section_name = 10; // set a value
 *```
 */
class Config2 {
	private $values;

	/**
	 * Config2 constructor.
	 * @param string $file
	 * 	The name of the ini file.
	 */
	public function __construct(string $file) {
		$this->values = [];
		$this->load($file);	
	}

	/**
	 * Get a value
	 * @param string $name
	 * 	The name of config value on the form section_name
	 * @return mixed|null
	 * 	If found the value otherwise null
	 */
	public function __get(string $name) {
		if (empty($this->values)) {
			return null;
		}
		$names = $this->getNames($name);
		return isset($this->values[$names[0]][$names[1]]) ? $this->values[$names[0]][$names[1]] : null;
	}

	/**
	 * Set a value
	 * @param string $name
	 * 	The name of config value on the form section_name
	 * @param mixed $value
	 * 	The value
	 */
	public function __set(string $name, $value) {
		$names = $this->getNames($name);
		$this->values[$names[0]][$names[1]] = $value;
	}

	private function load($file) {
		if (!file_exists($file)) {
			throw new RuntimeException($file." not found");
		}
		
		$this->values = parse_ini_file($file, true);
		if ($this->values === false) {
			throw new RuntimeException("Could not read configuration");
		}
	}

	private function getNames($name) {
		$names = explode('_', $name);
		if (count($names) < 2) {
			throw new RuntimeException("Wrong format for config names");
		}
		return $names;
	}
}
