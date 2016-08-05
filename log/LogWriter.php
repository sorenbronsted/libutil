<?php

namespace ufds;

interface LogWriter {
	public function write($level, $class, $text);
}
