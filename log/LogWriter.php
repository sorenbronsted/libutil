<?php
namespace sbronsted;

/**
 * Interface LogWriter is the required interface for a writer implementation
 */
interface LogWriter {
	/**
	 * Write the text somewhere
	 * @param string $level
	 * 	The log level from the Log
	 * @param string $class
	 * 	The name of the class which want to output
	 * @param string $text
	 * 	The text to output
s	 */
	public function write(string $level, string $class, string $text) : void ;
}
