<?php
namespace sbronsted;

/**
 * Class SyslogWriter writes it's output to syslogs LOG_INFO level
 */
class SyslogWriter implements LogWriter {
	public function write(string $level, string $class, string $text) : void {
		syslog(LOG_INFO,"$level:$class: $text");
	}
}

