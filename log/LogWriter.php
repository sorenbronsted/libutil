<?php

namespace sbronsted;

interface LogWriter {
	public function write($level, $class, $text);
}
