<?php

class Tree
{
    public $path;
    public $isShowFiles;
    public $writer;
    public $isPrintToFile;

    /**
     * Tree constructor.
     *
     * @param string $path
     * @param bool   $isShowFiles
     */
    /*public function __construct(string $path, bool $isShowFiles)
    {
        $this->path = $path;
        $this->isShowFiles = $isShowFiles;
    }*/

    public function show(string $path, bool $isShowFiles/*, object $writer*/, bool $isPrintToFile)
    {
        $this->path = $path;
        $this->isShowFiles = $isShowFiles;
        $this->writer = new TreeWrite();
        $this->isPrintToFile = $isPrintToFile;
        if (!is_dir($this->path)) {
            echo 'Директория не найдена';
        } else {
            if (count(glob($this->path . '/*')) > 0) {
                $this->printTree($this->path, []);
            } else {
                echo 'Директория пуста';
            }
        }
    }

    /**
     * Сканирование директории по текущему пути
     *
     * @param  string $path
     * @return array|false
     */
    private function filterResultScanDir(string $path)
    {
        $scanResult = scandir($path);
        if ($this->isShowFiles === true) {
            $files = array_diff($scanResult, ['.', '..']);
        } else {
            $files = array_filter(
                $scanResult,
                function ($file) use ($path) {
                    return !in_array($file, ['.', '..'])
                    && is_dir($path . "/" . $file);
                }
            );
        }
        $files = array_values($files);
        return $files;
    }

    /**
     * Вывод файлов и директорий
     *
     * @param string $fullPath
     * @param string $file
     * @param int    $index
     * @param int    $lastIndex
     * @param array  $lastDirs
     */
    private function printFiles(string $fullPath, string $file, int $index, int $lastIndex, array $lastDirs)
    {
        $result = '';
        foreach ($lastDirs as $lastDir) {
            $result .= ($lastDir === true ? "\t" : "│\t");
        }
        $result .= ($this->isLastDir($index, $lastIndex)
                ? '└── '
                : '├── ') . $file;
        if (!is_dir($fullPath)) {
            if (filesize($fullPath) > 0) {
                $result .= ' (' . filesize($fullPath) . ' bytes)';
            } else {
                $result .= ' ( empty )';
            }
        }
        $result .= "\n";
        ($this->isPrintToFile === true)
            ? $this->writer->writeToFile($result)
            : $this->writer->writeToWindow($result);

    }

    /**
     * Проверка на последнюю директорию
     *
     * @param  $index
     * @param  $lastIndex
     * @return mixed
     */
    private function isLastDir(int $index, int $lastIndex)
    {
        return $index === $lastIndex;
    }

    /**
     * Показ дерева каталогов
     *
     * @param string $path
     * @param array  $lastDirs
     */
    public function printTree(string $path, array $lastDirs)
    {
        $files = $this->filterResultScanDir($path);
        $lastIndex = count($files) - 1;
        foreach ($files as $i => $file) {
            $fullPath = $path . '/' . $file;
            $this->printFiles($fullPath, $file, $i, $lastIndex, $lastDirs);
            if (is_dir($fullPath)) {
                $lastDir = $this->isLastDir($i, $lastIndex);
                $newlastDirs = array_merge($lastDirs, [$lastDir]);
                $this->printTree($fullPath, $newlastDirs);
            }
        }
    }
}
