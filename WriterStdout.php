<?php
class WriterStdout implements WriterInterface
{
    public function print(string $text, string $fileName)
    {
        echo $text;
    }
}
