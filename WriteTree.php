<?php
class WriteTree
{
    public function writeToFile(string $result)
    {
        file_put_contents('tree.txt', $result, FILE_APPEND);
    }
    public function writeToWindow(string $result)
    {
        echo $result;
    }
}