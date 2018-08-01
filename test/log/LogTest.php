<?php

use PHPUnit\Framework\TestCase;
use ufds\Config2;
use ufds\DiContainer;
use ufds\Log;
use ufds\TestWriter;

require_once 'test/settings.php';

class LogTest extends TestCase {
	
	public function testLogError() {
		$log = new Log(Log::ERROR, "ufds\TestWriter");
		$log->error(__CLASS__, "test1");
		$this->assertEquals("error:LogTest: test1", TestWriter::$buffer);
		TestWriter::$buffer = "";
		$log->debug(__CLASS__, "test2");
		$this->assertEquals("", TestWriter::$buffer);
	}
	
	public function testLogDebug() {
		$log = new Log(Log::DEBUG, "ufds\TestWriter");
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
}
