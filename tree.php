<?php
require_once('Tree.php');

$path = $argv[1] ? $argv[1] : null;
$isShowFiles = (count($argv) > 2 && $argv[2] === '-f') ? true : false;
$tree = new Tree($path, $isShowFiles);
$tree->show();