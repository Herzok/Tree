<?php
require_once('bootstrap.php');

function write(string $path, bool $isShowFiles)
{
    $writer = new WriterStdout();
    $tree = new Tree($writer, $path, $isShowFiles);
    $tree->show();
}
$path = $argv[1] ? $argv[1] : null;
$isShowFiles = (count($argv) > 2 && $argv[2] === '-f')
    ? true
    : false;
if (is_callable('write', false, $callable_name)) {
    call_user_func_array('write', [$path, $isShowFiles]);
}
