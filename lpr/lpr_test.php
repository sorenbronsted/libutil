<?php
require('Lpr.php');

$file = __DIR__.'/test.pdf';
$job = new Lpr($file);
unset($job);

$job = new Lpr(array($file));
$job->username = 'Christoffer Nielsen';
$job->exec();