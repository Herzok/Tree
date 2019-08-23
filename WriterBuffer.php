<?php
class WriterBuffer implements WriterInterface
{
    private $text = '';

    public function print(string $text)
    {
        print $this->text .= $text;
    }
}
