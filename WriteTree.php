<?php
class WriteTree
{
    public function writeToFile(string $text)
    {
        file_put_contents('tree.txt', $text, FILE_APPEND);
    }
    public function writeToWindow(string $text)
    {
        echo $text;
    }
}