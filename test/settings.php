<?php

$paths = array(
  "di",
  "config",
  "log",
  "test/log",
);

set_include_path(get_include_path().":".implode(':', $paths));

spl_autoload_register(function($class) {
  require("$class.php");
});

?>