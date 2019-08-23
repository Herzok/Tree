<?php
class WriterNull implements WriterInterface
{
    public function print(string $text, string $fileName)
    {
    }
}
