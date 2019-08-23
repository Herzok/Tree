<?php
Class WriterFileStdout extends WriterFile
{
    public function print(string $text)
    {
        parent::print($text);
        echo $text;
    }
}