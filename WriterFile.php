<?php
class WriterFile implements WriterInterface
{
    public function print(string $text, string $fileName)
    {
        file_put_contents('tree.txt', $text, FILE_APPEND);
    }
}
