<?php

namespace ufds;

class Config2 {
	private $values = null;
	
	public function __construct($file) {
		$this->load($file);	
	}
	
	public function __get($name) {
		if (! is_array($this->values)) {
			return null;
		}

		$names = explode('_', $name);
		if (count($names) < 2) {
			throw new \RuntimeException("Wrong format for config names");
		}
		return isset($this->values[$names[0]][$names[1]]) ? $this->values[$names[0]][$names[1]] : null;
	}
	
	private function load($file) {
		if (!file_exists($file)) {
			throw new \RuntimeException($file." not found");
		}
		
		$this->values = parse_ini_file($file, true);
		if ($this->values === false) {
			throw new \RuntimeException("Could not read configuration");
		}
	}
}
