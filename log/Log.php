<?php
namespace sbronsted;

use Exception;

/**
 * Class Log writes information to a LogWriter. The log level are numbered so that ERROR i lowest and DEBUG is highest,
 * and the output i written only if the given level <= the default level.
 * It depends on the Config object in the DiContainer.
 * Usage:
 * ```
 * $dic = DiContainer::instance();
 * $dic->log = Log::createFromConfig();
 * ```
 * Ini file configuration:
 * ```
 * [log]
 * defaultLevel = error # default level is error and must be error|warn|info|debug
 * writer = SomeWriter # default writer i ConsoleWriter
 * # optional setting different loglevel on for different classes
 * debug[] = TestClass1,TestClass10
 * info[] = TestClass2
 * error[] = TestClass3
 * ```
 */
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

	/**
	 * Log constructor.
	 * @param int $defaultLevel
	 * 	The log level
	 * @param string $writer
	 * 	The writer class
	 */
	public function __construct($defaultLevel = Log::ERROR, $writer = ConsoleWriter::class) {
		$this->defaultLevel = $defaultLevel;
		$this->writer = new $writer;
		$this->levels = [
			self::ERROR => [],
			self::WARN => [],
			self::INFO => [],
			self::DEBUG => [],
		];
	}

	/**
	 * This will read settings from Config2
	 */
	public static function createFromConfig() : Log {
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

	/**
	 * Write a debug message
	 * @param string $class
	 * 	The calling class
	 * @param string $text
	 *  The text to print
	 */
	public function debug(string $class, string $text) : void {
		$this->write(self::DEBUG, $class, $text);
	}

	/**
	 * Write a warn message
	 * @param string $class
	 * 	The calling class
	 * @param string $text
	 *  The text to print
	 */
	public function warn(string $class, string $text) : void {
		$this->write(self::WARN, $class, $text);
	}

	/**
	 * Write a error message
	 * @param string $class
	 * 	The calling class
	 * @param string $text
	 *  The text to print
	 */
	public function error(string $class, string $text) : void {
		$this->write(self::ERROR, $class, $text);
	}

	/**
	 * Write a info message
	 * @param string $class
	 * 	The calling class
	 * @param string $text
	 *  The text to print
	 */
	public function info(string $class, string $text) : void {
		$this->write(self::INFO, $class, $text);
	}
	
	/**
	 * Add a class to a level programmatically
	 * @param int $level
	 *  The log level
	 * @param string $class
	 *  The name of the class to log at the given level
	 * @throws IllegalArgumentException
	 */
	public function add(int $level, string $class) {
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
