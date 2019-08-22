<?php
require_once 'Tree.php';

function writeTree(string $path, bool $isShowFiles)
{
    $tree = new Tree($path, $isShowFiles);
    $tree->show();
}
$path = $argv[1] ? $argv[1] : null;
$isShowFiles = (count($argv) > 2 && $argv[2] === '-f') ? true : false;
if (is_callable('writeTree', false, $callable_name)) {
    call_user_func_array('writeTree', [$path,$isShowFiles]);
}
