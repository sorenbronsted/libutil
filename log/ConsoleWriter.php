<?php

class ConsoleWriter implements LogWriter {
	public function write($level, $class, $text) {
		print("$level:$class: $text\n");
	}
}

?>