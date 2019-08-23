<?php
class WriterFile implements WriterInterface
{
    private $fileName;

    public function __construct(string $fileName = 'tree.txt')
    {
        $this->fileName = $fileName;
    }

    public function print(string $text)
    {
        file_put_contents($this->fileName, $text, FILE_APPEND);
    }
}
