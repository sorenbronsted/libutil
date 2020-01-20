<?php
namespace sbronsted;

use PHPUnit\Framework\TestCase;

require_once 'test/settings.php';

class DiContainerTest extends TestCase {
  
  protected function setUp() : void {
    DiContainer::instance()->value = 1;
  }
  
  public function testContainer() {
    $di = DiContainer::instance();
    $this->assertEquals(1, $di->value);
    $this->assertNull($di->notFound);
  }
}

?>