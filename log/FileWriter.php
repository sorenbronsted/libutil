<?php
namespace sbronsted;

/**
 * Class FileWriter writes it's output to a given file name, which read from the config property of theDiContainer.
 * Usage:
 *```
 * $dic = DiContainer::instance();
 * $dic->log = new FileWriter();
 *```
 */
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
	
	public function write(string $level, string $class, string $text) : void {
		fwrite($this->fh, "$level:$class: $text\n");
	}
}
