<?php
require_once 'PHPUnit/Autoload.php';
require_once 'test/settings.php';

class ConfigTest extends PHPUnit_Framework_TestCase {
  
  public function testGet() {
		$c = new Config("test/config/test.ini");
		$this->assertEquals("section1_var1", $c->section1_var1);
		$this->assertEquals("section2_var2", $c->section2_var2);
		$this->assertEquals(null, $c->nosection_novar);
  }
	
  public function testGetFails() {
		$c = new Config("test/config/test.ini");
		try {
			$c->var1;
			$this->fails("Expected an exception");
		}
		catch(RuntimeException $e) {
			$this->assertContains("format", $e->getMessage());
		}
  }
	
	public function testLoad() {
		try {
			new Config("xx.ini");
			$this->fail("Exception expected");
		}
		catch (RuntimeException $e) {
			$this->assertContains("not found", $e->getMessage());
		}
	}
}

?>