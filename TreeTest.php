<?php


use PHPUnit\Framework\TestCase;

require_once 'Tree.php';

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
    private function createReflectionMethod(string $class, string $method, bool $isShowFile)
    {
        $refMethod = new ReflectionMethod($class, $method);
        $tree = new Tree('tree', $isShowFile);
        $refMethod->setAccessible(true);
        return [$tree, $refMethod];
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
     * Буферный вывод.
     */
    private function getReturnResult(string $class, string $method, bool $isShowFile, array $params)
    {
        $resultCreateRefMethod = $this->createReflectionMethod($class, $method, $isShowFile);
        ob_start();
        $resultCreateRefMethod [1]->invokeArgs($resultCreateRefMethod [0], $params);
        $actual = ob_get_clean();
        return $actual;
    }


    /**
     * Проверка правильности результата метода isLastDir
     *
     * @throws ReflectionException
     */
    public function testIsLastDir()
    {
        $resultCreateRefMethod = $this->createReflectionMethod('Tree', 'isLastDir', false);
        $result = $resultCreateRefMethod [1]->invokeArgs($resultCreateRefMethod [0], [1, 1]);
        $this->assertTrue($result);
    }

    /**
     * Проверка вводимых параметров
     */
    public function testPathIsShowFiles()
    {
        $tree = new Tree('tree', true);
        $this->assertTrue($tree->isShowFiles);
        $this->assertNotNull($tree->path);
    }

    /**
     * Проверка вывода пустого файла
     *
     * @throws ReflectionException
     */
    public function testEmptyPrintFile()
    {
        $actual = $this->getReturnResult(
            'Tree',
            'printFiles',
            true,
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
        $actual = $this->getReturnResult(
            'Tree',
            'printFiles',
            true,
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
        $resultCreateRefMethod = $this->createReflectionMethod('Tree', 'filterResultScanDir', false);
        $result = $resultCreateRefMethod [1]->invokeArgs($resultCreateRefMethod [0], ['tree']);
        $this->assertNotNull($result);
    }

    /**
     * Проверка написания последней директории
     *
     * @throws ReflectionException
     */
    public function testPrintLastDir()
    {
        $actual = $this->getReturnResult(
            'Tree',
            'printFiles',
            false,
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
        $actual = $this->getReturnResult(
            'Tree',
            'printFiles',
            false,
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
        $actual = $this->getReturnResult(
            'Tree',
            'printFiles',
            true,
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
        $tree = new Tree('tree', false);
        ob_start();
        $tree->show();
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
        $tree = new Tree('tree', true);
        ob_start();
        $tree->show();
        $actual = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($expected, $actual);
    }
}
