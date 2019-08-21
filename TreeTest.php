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
    public function createReflectionMethod(string $class, string $method, bool $isShowFile)
    {
        $refMethod = new ReflectionMethod($class, $method);
        $tree = new Tree('tree', $isShowFile);
        $refMethod->setAccessible(true);
        return [$tree, $refMethod];
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
     * Проверка написания последней директории
     *
     * @throws ReflectionException
     */
    public function testPrintLastDir()
    {
        $resultCreateRefMethod = $this->createReflectionMethod('Tree', 'printFiles', false);
        $modelTree = $resultCreateRefMethod [0];
        $refMethod = $resultCreateRefMethod [1];
        ob_start();
        $refMethod->invokeArgs($modelTree, ['tree', 'data', 0, 0, []]);
        $actual = ob_get_contents();
        ob_end_clean();
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
        $resultCreateRefMethod = $this->createReflectionMethod('Tree', 'printFiles', false);
        $modelTree = $resultCreateRefMethod [0];
        $refMethod = $resultCreateRefMethod [1];
        ob_start();
        $refMethod->invokeArgs($modelTree, ['tree', 'data', 0 , 1, []]);
        $actual = ob_get_contents();
        ob_end_clean();
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
        $resultCreateRefMethod = $this->createReflectionMethod('Tree', 'printFiles', true);
        $modelTree = $resultCreateRefMethod [0];
        $refMethod = $resultCreateRefMethod [1];
        ob_start();
        $refMethod->invokeArgs($modelTree, ['tree/README.md', 'README.md', 0, 1, []]);
        $actual = ob_get_contents();
        ob_end_clean();
        $expected = "├── README.md (2073 bytes)\n";
        $this->assertEquals($expected, $actual);
    }

    /**
     * Проверка вывода пустого файла
     *
     * @throws ReflectionException
     */
    public function testEmptyPrintFile()
    {
        $resultCreateRefMethod = $this->createReflectionMethod('Tree', 'printFiles', true);
        $modelTree = $resultCreateRefMethod [0];
        $refMethod = $resultCreateRefMethod [1];
        ob_start();
        $refMethod->invokeArgs($modelTree, ['tree/data/empty.txt', 'empty.txt', 1, 2, [true]]);
        $actual = ob_get_contents();
        ob_end_clean();
        $expected = "\t├── empty.txt ( empty )\n";
        $this->assertEquals($expected, $actual);
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
     * Проверка правильности результата метода isLastDir
     *
     * @throws ReflectionException
     */
    public function testIsLastDir()
    {
        $resultCreateRefMethod = $this->createReflectionMethod('Tree', 'isLastDir', false);
        $modelTree = $resultCreateRefMethod [0];
        $refMethod = $resultCreateRefMethod [1];
        $result = $refMethod->invokeArgs($modelTree, [1, 1]);
        $this->assertTrue($result);
    }

    /**
     * Проверка вывода не пустого результата метода filterResultScanDir
     *
     * @throws ReflectionException
     */
    public function testResultScanDir()
    {
        $resultCreateRefMethod = $this->createReflectionMethod('Tree', 'filterResultScanDir', false);
        $modelTree = $resultCreateRefMethod [0];
        $refMethod = $resultCreateRefMethod [1];
        $result = $refMethod->invokeArgs($modelTree, ['tree']);
        $this->assertNotNull($result);
    }
}
