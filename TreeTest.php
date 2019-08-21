<?php

use PHPUnit\Framework\TestCase;

require_once('Tree.php');

class TreeTest extends TestCase
{
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
       	$this->assertEquals($expected,$actual);
    }
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
		$this->assertEquals($expected,$actual);
	}

}
