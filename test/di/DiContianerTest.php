<?php

use ufds\DiContainer;

require_once 'test/settings.php';

class DiContainerTest extends PHPUnit_Framework_TestCase {
  
  protected function setUp() {
    DiContainer::instance()->value = 1;
  }
  
  public function testContainer() {
    $di = DiContainer::instance();
    $this->assertEquals(1, $di->value);
    $this->assertNull($di->notFound);
  }
}

?>