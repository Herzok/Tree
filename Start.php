<?php
require_once('bootstrap.php');

//class Write
//{
//    public function writeToFile(string $result, string $fileName)
//    {
//        file_put_contents('tree.txt', $result, FILE_APPEND);
//    }
//    public function writeToWindow(string $result)
//    {
//        echo $result;
//    }
//}

function write(string $path, bool $isShowFiles, bool $isPrintToFile)
{
    $writer = new WriteTree();
    $tree = new Tree();
    $tree->show($path, $isShowFiles, $writer, $isPrintToFile);
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
