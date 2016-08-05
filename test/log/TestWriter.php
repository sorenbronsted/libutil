<?php
namespace ufds;

class TestWriter implements LogWriter {
	public static $buffer;
	
	public function write($level, $class, $text) {
		self::$buffer = sprintf("$level:$class: $text");
	}
}
