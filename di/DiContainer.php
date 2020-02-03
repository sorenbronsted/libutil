<?php

namespace sbronsted;

/**
 * Class DiContainer is a container for holdings value. It is implemented as a singleton, and it uses magic get and set
 * methods.
 * Usage:
 *```
 * $dic = DiContainer::instance();
 * $dic->property = $someValue; // Set a value
 * $someValue = $dic->property; // Returns som value
 *```
 */
class DiContainer {
  private $objects;
  private static $instance = null;

	/**
	 * Get the singleton
	 * @return DiContainer
	 * 	The only existing container object
	 */
  public static function instance() : DiContainer {
    if (self::$instance == null) {
      self::$instance = new DiContainer();
    }
    return self::$instance;
  }

	/**
	 * Set a value for $name
	 * @param string $name
	 * 	The name of the property to assign to
	 * @param mixed $value
	 * 	The value
	 */
  public function __set(string $name, $value) {
    $this->objects[$name] = $value;
  }

	/**
	 * Get the value for $name
	 * @param string $name
	 * 	The name of the property to get
	 * @return mixed|null
	 * 	If found the value otherwise null
	 */
  public function __get(string $name) {
    return isset($this->objects[$name]) ? $this->objects[$name] : null;
  }

  private function __construct() {
    $this->objects = [];
  }
}
