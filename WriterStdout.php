<?php
class WriterStdout implements WriterInterface
{
    public function printResult(string $text)
    {
        echo $text;
    }
}
