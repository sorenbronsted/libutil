<?php

namespace ufds;

use RuntimeException;

class Config2 {
	private $values;
	
	public function __construct($file) {
		$this->values = [];
		$this->load($file);	
	}
	
	public function __get($name) {
		if (empty($this->values)) {
			return null;
		}
		$names = $this->getNames($name);
		return isset($this->values[$names[0]][$names[1]]) ? $this->values[$names[0]][$names[1]] : null;
	}

	public function __set($name, $value) {
		$names = $this->getNames($name);
		$this->values[$names[0]][$names[1]] = $value;
	}

	private function load($file) {
		if (!file_exists($file)) {
			throw new \RuntimeException($file." not found");
		}
		
		$this->values = parse_ini_file($file, true);
		if ($this->values === false) {
			throw new RuntimeException("Could not read configuration");
		}
	}

	private function getNames($name): array {
		$names = explode('_', $name);
		if (count($names) < 2) {
			throw new RuntimeException("Wrong format for config names");
		}
		return $names;
	}
}
