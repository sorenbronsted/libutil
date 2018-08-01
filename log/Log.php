<?php

namespace ufds;

/*
 * This Log class writes information to a LogWriter.
 * What is written is dependent on which level is configurered with.
 * The log level is numbered so that level ERROR will only writes error
 * messages, level WARN will right  error and warning messages and so fourth.
 */
use Exception;

class Log {
	const ERROR = 0;
	const WARN  = 1;
	const INFO  = 2;
	const DEBUG = 3;

	protected static $levelTexts = array(
		self::ERROR => 'error',
		self::WARN  => 'warn',
		self::INFO  => 'info',
		self::DEBUG => 'debug'
	);
	protected $levels = null;
	protected $defaultLevel = null;
	protected $writer = null;
	
	/*
	 * This will create a log object where default level is error and output to console
	 * $defaultLevel: a 4 four level ranging from Error to Debug
	 * $writer: is the of the writer class to use, which must implement LogWriter
	 */
	public function __construct($defaultLevel = Log::ERROR, $writer = "ufds\ConsoleWriter") {
		$this->defaultLevel = $defaultLevel;
		$this->writer = new $writer;
		$this->levels = array();
		$this->levels[self::ERROR] = array();
		$this->levels[self::WARN]  = array();
		$this->levels[self::INFO]  = array();
		$this->levels[self::DEBUG] = array();
	}
	
	/*
	 * This will create a log object and configure it from config object
	 * which is loaded from DiContainer.
	 * It is expected that the configuration has a log section in the ini file,
	 * and all entries are optional.
	 *   [log]
	 *     defaultlevel = 0 #this error level
	 *     writer = SomeWriter
	 *     debug[] = TestClass1,TestClass10
	 *     info[] = TestClass2
	 *     error[] = TestClass3
	 */
	public static function createFromConfig() {
		$class = get_called_class();
		$result = new $class();
		
		$config = DiContainer::instance()->config;
		if ($config == null) {
			throw new Exception("Config in DiContainer is not set");
		}
		
		$tmp = $config->log_defaultLevel;
		if ($tmp != null) {
			$tmp = strtolower($tmp);
			$idx = array_search($tmp, self::$levelTexts);
			if ($idx !== false) {
				$result->defaultLevel = $idx;
			}
		}
		
		$tmp = $config->log_writer;
		if ($tmp != null) {
			$result->writer = new $tmp();
		}
		
		$tmp = $config->log_debug;
		if ($tmp != null) {
			$result->levels[self::DEBUG] = $tmp;
		}
		
		$tmp = $config->log_info;
		if ($tmp != null) {
			$result->levels[self::INFO] = $tmp;
		}
		
		$tmp = $config->log_warn;
		if ($tmp != null) {
			$result->levels[self::WARN] = $tmp;
		}
		
		$tmp = $config->log_error;
		if ($tmp != null) {
			$result->levels[self::ERROR] = $tmp;
		}
		
		return $result;
	}
	
	/*
	 * Write on debug level.
	 * $from: the calling object
	 * $text: the message.
	 */
	public function debug($class, $text) {
		$this->write(self::DEBUG, $class, $text);
	}

	/*
	 * Write on warning level.
	 * $from: the calling object
	 * $text: the message.
	 */
	public function warn($class, $text) {
		$this->write(self::WARN, $class, $text);
	}

	/*
	 * Write on error level.
	 * $from: the calling object
	 * $text: the message.
	 */
	public function error($class, $text) {
		$this->write(self::ERROR, $class, $text);
	}

	/*
	 * Write on info level.
	 * $from: the calling object
	 * $text: the message.
	 */
	public function info($class, $text) {
		$this->write(self::INFO, $class, $text);
	}
	
	/*
	 * Adds log level for a class name
	 * $level: can 4 four level ranging from Error to Debug
	 * $class: the class name for this level
	 */
	public function add($level, $class) {
		if ($level < self::ERROR || $level > self::DEBUG) {
			throw new IllegalArgumentException("level", __FILE__, __LINE__);
		}
		if (in_array($class, $this->levels[$level])) {
			return;
		}
		$this->levels[$level][] = $class;
	}
	
	protected function write($level, $class, $text) {
		if (in_array($class, $this->levels[$level]) || $this->defaultLevel >= $level) {
			$this->writer->write(self::$levelTexts[$level], $class, $text);
		}
	}
}
