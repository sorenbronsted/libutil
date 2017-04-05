<?php
require_once 'test/settings.php';

use ufds\Config2;

class ConfigTest extends PHPUnit_Framework_TestCase {
  
  public function testGet() {
		$c = new Config2("test/config/test.ini");
		$this->assertEquals("section1_var1", $c->section1_var1);
		$this->assertEquals("section2_var2", $c->section2_var2);
		$this->assertEquals(null, $c->nosection_novar);
  }
	
  public function testGetBoolean() {
		$c = new Config2("test/config/test.ini");
		$this->assertEquals(true, $c->boolean_var1);
		$this->assertEquals(false, $c->boolean_var2);
  }
	
  public function testGetFails() {
		$c = new Config2("test/config/test.ini");
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
			new Config2("xx.ini");
			$this->fail("Exception expected");
		}
		catch (RuntimeException $e) {
			$this->assertContains("not found", $e->getMessage());
		}
	}

	public function testSet() {
		$c = new Config2("test/config/test.ini");
		$c->new_value = 'hej';
		$this->assertEquals('hej', $c->new_value);
	}

	public function testSetFails() {
		$c = new Config2("test/config/test.ini");
		try {
			$c->var1 = 'x';
			$this->fails("Expected an exception");
		}
		catch(RuntimeException $e) {
			$this->assertContains("format", $e->getMessage());
		}
	}

}
