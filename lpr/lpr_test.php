<?php
require('lpr.php');

$file = __DIR__.'/test.pdf';
$job = new lpr($file);
unset($job);

$job = new lpr(array($file));
$job->username = 'Christoffer Nielsen';
$job->exec();