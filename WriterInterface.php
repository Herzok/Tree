<?php
interface WriterInterface
{
    public function print(string $text, string $fileName);
}