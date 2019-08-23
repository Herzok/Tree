<?php
require_once('bootstrap.php');

$path = $argv[1] ? $argv[1] : null;
$isShowFiles = (count($argv) > 2 && $argv[2] === '-f')
    ? true
    : false;
/*$fileName = (count($argv) > 3 && !is_null($argv[3]))
    ? $argv[3]
    : null;*/
//$writer = new WriterFile('1.txt');
//$writer = new WriterStdout();
$writer = new WriterFileStdout('1.txt');
$tree = new Tree($writer);
$tree->show($path, $isShowFiles);
