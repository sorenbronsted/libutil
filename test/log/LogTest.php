<?php
namespace sbronsted;

use PHPUnit\Framework\TestCase;

require_once 'test/settings.php';

class LogTest extends TestCase {
	
	public function testLogError() {
		$log = new Log(Log::ERROR, "sbronsted\TestWriter");
		$log->error(__CLASS__, "test1");
		$this->assertEquals("error:sbronsted\LogTest: test1", TestWriter::$buffer);
		TestWriter::$buffer = "";
		$log->debug(__CLASS__, "test2");
		$this->assertEquals("", TestWriter::$buffer);
	}
	
	public function testLogDebug() {
		$log = new Log(Log::DEBUG, "sbronsted\TestWriter");
		$log->error(__CLASS__, "test1");
		$this->assertEquals("error:sbronsted\LogTest: test1", TestWriter::$buffer);
		TestWriter::$buffer = "";
		$log->debug(__CLASS__, "test2");
		$this->assertEquals("debug:sbronsted\LogTest: test2", TestWriter::$buffer);
	}
	
	public function testCreateFromConfig() {
		DiContainer::instance()->config = new Config2("test/log/test.ini");
		$log = Log::createFromConfig();
		$log->warn(__CLASS__, "test1");
		$this->assertEquals("warn:sbronsted\LogTest: test1", TestWriter::$buffer);
		TestWriter::$buffer = "";
		$log->debug(__CLASS__, "test1");
		$this->assertEquals("debug:sbronsted\LogTest: test1", TestWriter::$buffer);
		TestWriter::$buffer = "";
		$log->info(__CLASS__, "test1");
		$this->assertEquals("info:sbronsted\LogTest: test1", TestWriter::$buffer);
	}
}
