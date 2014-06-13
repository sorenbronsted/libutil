<?php
require_once 'test/settings.php';

class LogTest extends PHPUnit_Framework_TestCase {
	
	public function testLogError() {
		$log = new Log(Log::ERROR, "TestWriter");
		$log->error(__CLASS__, "test1");
		$this->assertEquals("error:LogTest: test1", TestWriter::$buffer);
		TestWriter::$buffer = "";
		$log->debug(__CLASS__, "test2");
		$this->assertEquals("", TestWriter::$buffer);
	}
	
	public function testLogDebug() {
		$log = new Log(Log::DEBUG, "TestWriter");
		$log->error(__CLASS__, "test1");
		$this->assertEquals("error:LogTest: test1", TestWriter::$buffer);
		TestWriter::$buffer = "";
		$log->debug(__CLASS__, "test2");
		$this->assertEquals("debug:LogTest: test2", TestWriter::$buffer);
	}
	
	public function testCreateFromConfig() {
		DiContainer::instance()->config = new Config2("test/log/test.ini");
		$log = Log::createFromConfig();
		$log->warn(__CLASS__, "test1");
		$this->assertEquals("warn:LogTest: test1", TestWriter::$buffer);
		TestWriter::$buffer = "";
		$log->debug(__CLASS__, "test1");
		$this->assertEquals("debug:LogTest: test1", TestWriter::$buffer);
		TestWriter::$buffer = "";
		$log->info(__CLASS__, "test1");
		$this->assertEquals("", TestWriter::$buffer);
	}
	
	public function testFileWriter() {
		
	}
}

?>