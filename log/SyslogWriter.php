<?php
namespace ufds;

class SyslogWriter implements LogWriter {
	public function write($level, $class, $text) {
		syslog(LOG_INFO,"$level:$class: $text");
	}
}

