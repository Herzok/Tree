<?php
Class WriterFileStdout implements WriterInterface
{
    public function print(string $text, string $fileName)
    {
        echo $text;
        file_put_contents($fileName, $text, FILE_APPEND);
    }
}