<?php

use PHPUnit\Framework\TestCase;

require_once('bootstrap.php');

class WriterTest extends TestCase
{
    private function getProperty(string $property, object $obj)
    {
        $property = new ReflectionProperty(get_class($obj), $property);
        $property->setAccessible(true);
        $result = $property->getValue($obj);
        return $result;
    }
    /**
     * Проверка переданного имени файла
     */
    public function testPropertyFileName()
    {
        $writer = new WriterFile('treefiles.txt');
        $property = $this->getProperty('fileName', $writer);
        $this->assertEquals($property, 'treefiles.txt');
    }
}
