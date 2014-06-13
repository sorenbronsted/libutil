<?php

spl_autoload_register(function($class) {
	$paths = array(
		"di",
		"config",
		"log",
		"test/log",
	);

	foreach($paths as $path) {
		$fullname = $path.'/'.$class.'.php';
		if (is_file($fullname)) {
			include($fullname);
			return true;
		}
	}
	return false;
});

?>