<?php
/*require_once 'PHPUnit/Autoload.php';
require_once 'test/settings.php';

class lprTest extends PHPUnit_Framework_TestCase {
	public function testPrinterConfiguration() {
		$error_file = tempnam(sys_get_temp_dir(), 'lpr');

		exec('lpr  2> '.$error_file);

		$content = trim(file_get_contents($error_file));
		@unlink($error_file);

		$this->assertTrue($content != 'lpr: Error - no default destination available.');
	}
}*/