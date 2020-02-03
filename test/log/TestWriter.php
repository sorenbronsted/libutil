<?php
namespace sbronsted;

class TestWriter implements LogWriter {
	public static $buffer;
	
	public function write(string $level, string $class, string $text) : void {
		self::$buffer = sprintf("$level:$class: $text");
	}
}
