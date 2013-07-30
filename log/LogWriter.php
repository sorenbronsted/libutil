<?php

interface LogWriter {
	public function write($level, $class, $text);
}

?>