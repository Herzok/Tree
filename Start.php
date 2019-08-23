<?php
require_once('bootstrap.php');

function write(string $path, bool $isShowFiles, bool $isPrintToFile)
{
    //$writer = new TreeWrite();
    $tree = new Tree();
    $tree->show($path, $isShowFiles/*, $writer*/, $isPrintToFile);
}
$path = $argv[1] ? $argv[1] : null;
$isShowFiles = (count($argv) > 2 && $argv[2] === '-f')
    ? true
    : false;
$isPrintToFile = (count($argv) > 3 && $argv[3] === 'print')
    ? true
    : false;
if (is_callable('write', false, $callable_name)) {
    call_user_func_array('write', [$path, $isShowFiles, $isPrintToFile]);
}
