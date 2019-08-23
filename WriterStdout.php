<?php
class WriterStdout implements WriterInterface
{
    public function print(string $text)
    {
        echo $text;
    }
}
