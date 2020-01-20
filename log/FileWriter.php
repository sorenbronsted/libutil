<?php

namespace sbronsted;

class FileWriter implements LogWriter {
	private $fh;
	
	public function __construct() {
		$config = DiContainer::instance()->config;
		$this->fh = fopen($config->log_file, "a");
	}
	
	public function __destruct() {
		if ($this->fh) {
			fclose($this->fh);
		}
	}
	
	public function write($level, $class, $text) {
		fwrite($this->fh, "$level:$class: $text\n");
	}
}
