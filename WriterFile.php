<?php
class WriterFile implements WriterInterface
{
    public function printResult(string $text)
    {
        file_put_contents('tree.txt', $text, FILE_APPEND);
    }
}
