<?php


use PHPUnit\Framework\TestCase;

require_once('bootstrap.php');

class TreeTest extends TestCase
{
    /**
     * Создание ReflectionMethod
     *
     * @param  string $class
     * @param  string $method
     * @param  bool $isShowFile
     * @return array
     * @throws ReflectionException
     */
    private function getObjAndMethod(string $method)
    {
        $refMethod = new ReflectionMethod('Tree', $method);
        $writer = new WriterBuffer();
        $tree = new Tree($writer);
        $refMethod->setAccessible(true);
        return ['tree' => $tree,'method' => $refMethod];
    }

    private function getProperty(string $property, object $obj)
    {
        $property = new ReflectionProperty(get_class($obj), $property);
        $property->setAccessible(true);
        $result = $property->getValue($obj);
        return $result;
    }
    /**
     * Возвращение отступов
     *
     * @return array
     */
    public function prefixes()
    {
        return [["\t", [true]],
            ["\t│\t", [true, false]],
            ["\t│\t\t", [true, false, true]]];
    }

    /**
     * Проверка буферизованного вывода
     */
    private function getBufferResult(string $method, array $params)
    {
        $resultObjAndMethod = $this->getObjAndMethod($method);
        $result = $resultObjAndMethod ['method']->invokeArgs($resultObjAndMethod ['tree'], $params);
        return $result;
    }

    /**
     *  Возвращение результата выполнения метода
     *
     * @param string $method
     * @param bool $isShowFile
     * @param array $params
     * @return mixed
     * @throws ReflectionException
     */
    private function getSomeResult(string $method, bool $isShowFile, array $params)
    {
        $resultObjAndMethod = $this->getObjAndMethod($method, $isShowFile);
        return $resultObjAndMethod ['method']->invokeArgs($resultObjAndMethod ['tree'], $params);
    }

    /**
     * Проверка переданного объекта
     */
    public function testIsNullWriter()
    {
        $writer = new WriterFile();
        $tree = new Tree($writer);
        $property = $this->getProperty('writer', $tree);
        $this->assertEquals($property, $writer);
    }

    /**
     * Проверка на существование файла
     */
    public function testIsThereFile()
    {
        $writer = new WriterFile();
        $tree = new Tree($writer);
        $tree->show('tree', true);
        $this->assertFileExists('tree.txt');
    }

    /**
     * Проверка правильности результата метода isLastDir
     *
     * @throws ReflectionException
     */
    public function testIsLastDirRes()
    {
        $result = $this->getSomeResult('isLastDir', false, [1,1]);
        $this->assertTrue($result);
    }

    /**
     * Проверка вводимых параметров
     */
    public function testPathIsShowFiles()
    {
        $writer = new WriterNull();
        $tree = new Tree($writer);
        $tree->show('tree', true);
        $path = $this->getProperty('path', $tree);
        $isShowFiles = $this->getProperty('isShowFiles', $tree);
        $this->assertEquals($path, 'tree');
        $this->assertEquals($isShowFiles, true);
    }

    /**
     * Проверка вывода пустого файла
     *
     * @throws ReflectionException
     */
    public function testEmptyPrintFile()
    {
        $actual = $this->getBufferResult(
            'printFiles',
            ['tree/data/empty.txt', 'empty.txt', 0, 1, []]
        );
        $expected = "├── empty.txt ( empty )\n";
        $this->assertEquals($expected, $actual);
    }

    /**
     * Тест отступов
     *
     * @dataProvider prefixes
     * @throws ReflectionException
     */
    public function testPrefixesPrintFile(string $prefix, array $levelDirs)
    {
        $actual = $this->getBufferResult(
            'printFiles',
            ['tree/data/empty.txt', 'empty.txt', 0, 1, $levelDirs]
        );
        $expected = $prefix;
        $this->assertContains($expected, $actual);
    }

    /**
     * Проверка вывода не пустого результата метода filterResultScanDir
     *
     * @throws ReflectionException
     */
    public function testResultScanDir()
    {
        $result = $this->getSomeResult('filterResultScanDir', false, ['tree']);
        $this->assertNotNull($result);
    }

    /**
     * Проверка написания последней директории
     *
     * @throws ReflectionException
     */
    public function testPrintLastDir()
    {
        $actual = $this->getBufferResult(
            'printFiles',
            ['tree', 'data', 0, 0, []]
        );
        $expected = "└── data\n";
        $this->assertEquals($expected, $actual);
    }

    /**
     * Проверка написания не последней тиректории
     *
     * @throws ReflectionException
     */
    public function testPrintDir()
    {
        $actual = $this->getBufferResult(
            'printFiles',
            ['tree', 'data', 0, 1, []]
        );
        $expected = "├── data\n";
        $this->assertEquals($expected, $actual);
    }

    /**
     * Проверка вывода размера файла
     *
     * @throws ReflectionException
     */
    public function testSizePrintFile()
    {
        $actual = $this->getBufferResult(
            'printFiles',
            ['tree/README.md', 'README.md', 0, 1, []]
        );
        $expected = "├── README.md (2073 bytes)\n";
        $this->assertEquals($expected, $actual);
    }

    /**
     * Проверка дерева каталогов
     */
    public function testShowDirsTree()
    {
        $expected = "└── data
\t├── dist
\t│\t├── css
\t│\t├── html
\t│\t└── js
\t└── src
\t\t└── vue\n";
        $writer = new WriterStdout();
        $tree = new Tree($writer, 'tree.txt');
        ob_start();
        $tree->show('tree', false);
        $actual = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Проверка выводимой структуры полного дерева
     */
    public function testShowFullTree()
    {
        $expected = "├── README.md (2073 bytes)
└── data
\t├── dist
\t│\t├── css
\t│\t│\t└── app.css (14 bytes)
\t│\t├── html
\t│\t│\t└── index.html (15 bytes)
\t│\t└── js
\t│\t\t└── app.js (13 bytes)
\t├── empty.txt ( empty )
\t└── src
\t\t├── vue
\t\t│\t└── main.js (20 bytes)
\t\t└── zzz.txt (21 bytes)\n";
        $writer = new WriterStdout();
        $tree = new Tree($writer, 'tree.txt');
        ob_start();
        $tree->show('tree',true);
        $actual = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($expected, $actual);
    }
}
