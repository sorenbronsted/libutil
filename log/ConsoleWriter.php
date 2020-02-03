<?php
namespace sbronsted;

/**
 * Class ConsoleWriter writes it's output on stdout
 */
class ConsoleWriter implements LogWriter {
	public function write(string $level, string $class, string $text) : void {
		print("$level:$class: $text\n");
	}
}
