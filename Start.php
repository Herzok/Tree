<?php
require_once('bootstrap.php');

$path = $argv[1] ? $argv[1] : null;
$isShowFiles = (count($argv) > 2 && $argv[2] === '-f')
    ? true
    : false;
//$writer = new WriterFile();
//$writer = new WriterStdout();
$writer = new WriterFileStdout();
$tree = new Tree($writer);
$tree->show($path, $isShowFiles);
