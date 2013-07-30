<?php

/* Dependency Injection Container
 * In some commen place eg settings populate this DiContianer with values which
 * the application rely on.
 */ 
class DiContainer {
  private $objects;
  private static $instance = null;
  
  public static function instance() {
    if (self::$instance == null) {
      self::$instance = new DiContainer();
    }
    return self::$instance;
  }
  
  public function __set($name, $value) {
    $this->objects[$name] = $value;
  }
  
  public function __get($name) {
    return isset($this->objects[$name]) ? $this->objects[$name] : null;
  }

  private function __construct() {
    $this->objects = array();
  }
}

?>